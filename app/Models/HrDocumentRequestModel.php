<?php

namespace App\Models;

use CodeIgniter\Model;

class HrDocumentRequestModel extends Model
{
    protected $table         = 'hr_document_requests';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'nexus_request_id', 'nexus_id', 'user_id', 'document_key', 'document_name',
        'status', 'notes', 'rejection_reason',
    ];

    public function findByNexusRequestId(int $nexusRequestId): ?object
    {
        return $this->where('nexus_request_id', $nexusRequestId)->first();
    }

    public function listPendingForUser(int $userId): array
    {
        return $this->where('user_id', $userId)
            ->whereIn('status', ['requested', 'rejected'])
            ->orderBy('updated_at', 'DESC')
            ->findAll();
    }

    public function listForUser(int $userId, int $limit = 50): array
    {
        return $this->where('user_id', $userId)
            ->orderBy('updated_at', 'DESC')
            ->findAll($limit);
    }
}
