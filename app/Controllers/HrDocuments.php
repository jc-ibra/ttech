<?php

namespace App\Controllers;

use App\Models\HrDocumentRequestModel;

/**
 * Employee-facing HR document upload from Mi espacio.
 */
class HrDocuments extends BaseController
{
    public function upload(int $id)
    {
        $user = session()->get('user');
        if (! $user) {
            return $this->respondWithCsrf(['ok' => false, 'error' => 'No autenticado']);
        }

        $model = new HrDocumentRequestModel();
        $row   = $model->find($id);
        if (! $row || (int) $row->user_id !== (int) $user->id) {
            return $this->respondWithCsrf(['ok' => false, 'error' => 'Solicitud no encontrada']);
        }
        if (! in_array($row->status, ['requested', 'rejected'], true)) {
            return $this->respondWithCsrf(['ok' => false, 'error' => 'Esta solicitud no admite carga']);
        }

        $file = $this->request->getFile('document');
        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $this->respondWithCsrf(['ok' => false, 'error' => 'Archivo inválido']);
        }

        $mime = $file->getMimeType() ?: 'application/octet-stream';
        $bin  = file_get_contents($file->getTempName());
        if ($bin === false || $bin === '') {
            return $this->respondWithCsrf(['ok' => false, 'error' => 'No se pudo leer el archivo']);
        }

        $nexusBase  = rtrim((string) env('NEXUS_BASE_URL', ''), '/');
        $nexusToken = (string) env('NEXUS_API_TOKEN', '');
        if ($nexusBase === '' || $nexusToken === '') {
            return $this->respondWithCsrf([
                'ok'    => false,
                'error' => 'Nexus no está configurado en local (NEXUS_BASE_URL / NEXUS_API_TOKEN)',
            ]);
        }

        $payload = [
            'nexus_id'       => (string) $row->nexus_id,
            'original_name'  => $file->getClientName(),
            'mime'           => $mime,
            'content_base64' => base64_encode($bin),
        ];

        $url  = $nexusBase . '/api/v1/employees/document-requests/' . (int) $row->nexus_request_id . '/upload';
        $curl = curl_init($url);
        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer ' . $nexusToken,
                'Content-Type: application/json',
                'Accept: application/json',
            ],
            CURLOPT_POSTFIELDS     => json_encode($payload),
            CURLOPT_TIMEOUT        => 60,
        ]);
        $response = curl_exec($curl);
        $http     = (int) curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $err      = curl_error($curl);
        curl_close($curl);

        if ($err) {
            return $this->respondWithCsrf(['ok' => false, 'error' => 'Error al contactar Nexus: ' . $err]);
        }

        $data = json_decode((string) $response, true);
        if ($http >= 400 || ! is_array($data) || ($data['status'] ?? '') !== 'ok') {
            $msg = is_array($data) ? (string) ($data['message'] ?? 'Nexus rechazó el archivo') : 'Nexus rechazó el archivo';
            return $this->respondWithCsrf(['ok' => false, 'error' => $msg]);
        }

        $model->update($row->id, ['status' => 'uploaded', 'rejection_reason' => null]);

        return $this->respondWithCsrf([
            'ok'      => true,
            'message' => 'Documento enviado a RRHH',
            'status'  => 'uploaded',
        ]);
    }
}
