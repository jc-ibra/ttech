<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\AlertModel;
use App\Models\HrDocumentRequestModel;
use App\Models\UserModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Inbound API from Nexus for HR document request lifecycle events.
 */
class HrDocuments extends BaseController
{
    protected $hrDocs;
    protected $users;
    protected $alerts;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->hrDocs = new HrDocumentRequestModel();
        $this->users  = new UserModel();
        $this->alerts = new AlertModel();
    }

    private function json(array $data, int $status = 200): ResponseInterface
    {
        return $this->response->setStatusCode($status)->setJSON($data);
    }

    // POST /api/v1/hr-documents/notify
    public function notify()
    {
        $body = $this->request->getJSON(true) ?? [];

        $nexusId   = trim((string) ($body['nexus_id'] ?? ''));
        $requestId = (int) ($body['request_id'] ?? 0);
        $event     = trim((string) ($body['event'] ?? ''));
        $status    = trim((string) ($body['status'] ?? 'requested'));
        $docName   = trim((string) ($body['document_name'] ?? 'Documento'));

        if ($nexusId === '' || $requestId <= 0) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'CAMPO_REQUERIDO',
                'mensaje'      => 'nexus_id y request_id son requeridos',
            ], 400);
        }

        $user = $this->users->where('nexus_id', $nexusId)->first();
        if (! $user) {
            return $this->json([
                'exito'        => false,
                'error_codigo' => 'USUARIO_NO_ENCONTRADO',
                'mensaje'      => 'No hay usuario de intranet con ese nexus_id',
            ], 404);
        }

        $existing = $this->hrDocs->findByNexusRequestId($requestId);
        $payload  = [
            'nexus_request_id' => $requestId,
            'nexus_id'         => $nexusId,
            'user_id'          => (int) $user->id,
            'document_key'     => trim((string) ($body['document_key'] ?? '')) ?: null,
            'document_name'    => $docName,
            'status'           => $status !== '' ? $status : 'requested',
            'notes'            => trim((string) ($body['notes'] ?? '')) ?: null,
            'rejection_reason' => trim((string) ($body['rejection_reason'] ?? '')) ?: null,
        ];

        if ($existing) {
            $this->hrDocs->update($existing->id, $payload);
        } else {
            $this->hrDocs->insert($payload);
        }

        $message = match ($event) {
            'created'   => "RRHH solicitó el documento: {$docName}",
            'accepted'  => "Tu documento «{$docName}» fue aceptado",
            'rejected'  => "Tu documento «{$docName}» fue rechazado",
            'cancelled' => "Se canceló la solicitud de «{$docName}»",
            default     => "Actualización de documento: {$docName}",
        };

        $this->alerts->createAlert(
            'hr_document',
            $message,
            (int) $user->id,
            json_encode([
                'request_id'    => $requestId,
                'document_name' => $docName,
                'status'        => $status,
                'event'         => $event,
            ])
        );

        return $this->json([
            'exito'   => true,
            'mensaje' => 'Notificación registrada',
        ]);
    }
}
