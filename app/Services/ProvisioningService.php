<?php

namespace App\Services;

use App\Exceptions\ProvisioningException;

/**
 * Provisions a new employee in external systems (GLPI and Mailcow) right
 * after they are saved to the local database.
 *
 * Required .env variables:
 *   GLPI_URL          – Base URL of GLPI, e.g. https://helpdesk.example.mx
 *   GLPI_APP_TOKEN    – App-Token generated in GLPI → Setup → API
 *   GLPI_USER_TOKEN   – User-Token of the API service account
 *   MAILCOW_URL       – Base URL of Mailcow, e.g. https://mail.example.mx
 *   MAILCOW_API_KEY   – API key from Mailcow → Access → API
 */
class ProvisioningService
{
    private string $glpiUrl;
    private string $glpiAppToken;
    private string $glpiUserToken;
    private string $mailcowUrl;
    private string $mailcowApiKey;

    public function __construct()
    {
        // Read credentials from .env; empty strings signal a misconfiguration.
        $this->glpiUrl       = rtrim((string) env('GLPI_URL', ''), '/');
        $this->glpiAppToken  = (string) env('GLPI_APP_TOKEN', '');
        $this->glpiUserToken = (string) env('GLPI_USER_TOKEN', '');
        $this->mailcowUrl    = rtrim((string) env('MAILCOW_URL', ''), '/');
        $this->mailcowApiKey = (string) env('MAILCOW_API_KEY', '');
    }

    // -------------------------------------------------------------------------
    // Public API
    // -------------------------------------------------------------------------

    /**
     * Create a user in GLPI via its REST API.
     *
     * Expected keys in $data:
     *   nombre, apellidos, email, telefono, celular, numero_empleado, password
     *
     * @throws ProvisioningException on any GLPI API error
     */
    public function createGlpiUser(array $data): void
    {
        $this->guardConfig('GLPI', $this->glpiUrl, $this->glpiAppToken, $this->glpiUserToken);

        // Open an authenticated GLPI session; always close it, even on failure.
        $sessionToken = $this->glpiInitSession();

        try {
            // Map internal field names to the GLPI REST schema.
            $payload = [
                'input' => [
                    'firstname'           => $data['nombre']          ?? '',
                    'realname'            => $data['apellidos']        ?? '',
                    'phone'               => $data['telefono']         ?? '',
                    'mobile'              => $data['celular']          ?? '',
                    'registration_number' => $data['numero_empleado']  ?? '',
                    'password'            => $data['password']         ?? '',
                    'password2'           => $data['password']         ?? '',
                    // _useremails is a special GLPI field that creates a linked e-mail address.
                    '_useremails'         => [$data['email'] ?? ''],
                ],
            ];

            $response = \Config\Services::curlrequest()->request(
                'POST',
                $this->glpiUrl . '/apirest.php/User',
                [
                    'headers' => [
                        'App-Token'     => $this->glpiAppToken,
                        'Session-Token' => $sessionToken,
                        'Content-Type'  => 'application/json',
                    ],
                    'body' => json_encode($payload),
                ]
            );

            $status = $response->getStatusCode();

            if ($status < 200 || $status >= 300) {
                log_message('error', '[ProvisioningService] GLPI createUser HTTP ' . $status . ' – ' . $response->getBody());
                throw new ProvisioningException('GLPI: error al crear usuario (HTTP ' . $status . ').');
            }

            log_message('info', '[ProvisioningService] Usuario creado en GLPI: ' . ($data['email'] ?? ''));

        } finally {
            // Kill the session regardless of success or failure.
            $this->glpiKillSession($sessionToken);
        }
    }

    /**
     * Create a mailbox in Mailcow via its REST API.
     *
     * Expected keys in $data:
     *   nombre, apellidos, email, password
     *
     * @throws ProvisioningException on any Mailcow API error
     */
    public function createMailcowMailbox(array $data): void
    {
        $this->guardConfig('Mailcow', $this->mailcowUrl, $this->mailcowApiKey);

        $email = $data['email'] ?? '';

        // Split "user@domain.com" into its two components.
        $parts = explode('@', $email, 2);
        if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
            throw new ProvisioningException('Mailcow: el email "' . $email . '" no es válido.');
        }

        [$localPart, $domain] = $parts;

        // Map internal field names to the Mailcow mailbox schema.
        $payload = [
            'local_part'      => $localPart,
            'domain'          => $domain,
            'password'        => $data['password'] ?? '',
            'password2'       => $data['password'] ?? '',
            'name'            => trim(($data['nombre'] ?? '') . ' ' . ($data['apellidos'] ?? '')),
            'active'          => 1,
            'force_pw_update' => 0,
            'tls_enforce_in'  => 0,
            'tls_enforce_out' => 0,
            'quota'           => 1024, // MB; adjust as needed
        ];

        $response = \Config\Services::curlrequest()->request(
            'POST',
            $this->mailcowUrl . '/api/v1/add/mailbox',
            [
                'headers' => [
                    'X-API-Key'    => $this->mailcowApiKey,
                    'Content-Type' => 'application/json',
                ],
                'body' => json_encode($payload),
            ]
        );

        $status = $response->getStatusCode();
        $body   = $response->getBody();

        if ($status < 200 || $status >= 300) {
            log_message('error', '[ProvisioningService] Mailcow createMailbox HTTP ' . $status . ' – ' . $body);
            throw new ProvisioningException('Mailcow: error al crear buzón (HTTP ' . $status . ').');
        }

        // Mailcow returns a JSON array even on logical errors; inspect the first element.
        $decoded = json_decode($body, true);
        if (is_array($decoded) && isset($decoded[0]['type']) && $decoded[0]['type'] === 'error') {
            $msg = $decoded[0]['msg'] ?? 'error desconocido';
            log_message('error', '[ProvisioningService] Mailcow API error: ' . $msg);
            throw new ProvisioningException('Mailcow: ' . $msg);
        }

        log_message('info', '[ProvisioningService] Buzón creado en Mailcow: ' . $email);
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    /**
     * Open a GLPI REST session and return the session_token string.
     *
     * @throws ProvisioningException when the session cannot be opened
     */
    private function glpiInitSession(): string
    {
        $response = \Config\Services::curlrequest()->request(
            'GET',
            $this->glpiUrl . '/apirest.php/initSession',
            [
                'headers' => [
                    'App-Token'     => $this->glpiAppToken,
                    // GLPI accepts either user_token or Basic auth; we use the token form.
                    'Authorization' => 'user_token ' . $this->glpiUserToken,
                    'Content-Type'  => 'application/json',
                ],
            ]
        );

        $status = $response->getStatusCode();

        if ($status < 200 || $status >= 300) {
            log_message('error', '[ProvisioningService] GLPI initSession HTTP ' . $status . ' – ' . $response->getBody());
            throw new ProvisioningException('GLPI: no se pudo iniciar sesión en la API (HTTP ' . $status . ').');
        }

        $decoded = json_decode($response->getBody(), true);

        if (empty($decoded['session_token'])) {
            log_message('error', '[ProvisioningService] GLPI initSession: session_token ausente en la respuesta.');
            throw new ProvisioningException('GLPI: respuesta de initSession inválida.');
        }

        return $decoded['session_token'];
    }

    /**
     * Close a GLPI REST session. Errors here are non-critical and only logged.
     */
    private function glpiKillSession(string $sessionToken): void
    {
        try {
            \Config\Services::curlrequest()->request(
                'GET',
                $this->glpiUrl . '/apirest.php/killSession',
                [
                    'headers' => [
                        'App-Token'     => $this->glpiAppToken,
                        'Session-Token' => $sessionToken,
                        'Content-Type'  => 'application/json',
                    ],
                ]
            );
        } catch (\Throwable $e) {
            log_message('warning', '[ProvisioningService] GLPI killSession falló: ' . $e->getMessage());
        }
    }

    /**
     * Throw early if required environment variables are missing.
     * Accepts 3 or 4 arguments (url + one or two tokens).
     *
     * @throws ProvisioningException
     */
    private function guardConfig(string $service, string ...$values): void
    {
        foreach ($values as $value) {
            if ($value === '') {
                throw new ProvisioningException(
                    $service . ': faltan variables de entorno de configuración. '
                    . 'Verifica ' . strtoupper($service) . '_URL, '
                    . strtoupper($service) . '_APP_TOKEN, etc. en el .env.'
                );
            }
        }
    }
}
