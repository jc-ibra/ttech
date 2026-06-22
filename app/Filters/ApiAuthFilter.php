<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class ApiAuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $apiKey = env('NEXUS_API_KEY');

        if (empty($apiKey)) {
            return service('response')
                ->setStatusCode(500)
                ->setJSON([
                    'exito'        => false,
                    'error_codigo' => 'CONFIG_ERROR',
                    'mensaje'      => 'API key no configurada en el servidor',
                ]);
        }

        $authHeader = $request->getHeaderLine('Authorization')
            ?: ($_SERVER['HTTP_AUTHORIZATION']          ?? '')
            ?: ($_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');

        if (!preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'exito'        => false,
                    'error_codigo' => 'NO_AUTORIZADO',
                    'mensaje'      => 'Token de autenticación requerido',
                ]);
        }

        if (!hash_equals($apiKey, trim($matches[1]))) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'exito'        => false,
                    'error_codigo' => 'NO_AUTORIZADO',
                    'mensaje'      => 'Token de autenticación inválido',
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
