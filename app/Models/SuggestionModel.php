<?php

namespace App\Models;

use CodeIgniter\Model;

class SuggestionModel extends Model{

    protected $table              = 'suggestions';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['author', 'name', 'email', 'title', 'message', 'status'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
    public function getSuggestions($id = null)
    {
        $this->select('suggestions.*, users.name as author_name, users.lastname as author_lastname, users.email as author_email, users.photo as author_photo');
        $this->join('users', 'users.id = suggestions.author', 'left');

        if($id !== null){
            return $this->where('suggestions.id', $id)->first();
        }

        return $this->orderBy('suggestions.created_at', 'DESC')->findAll();
    }

    public function createSuggestion($author, $name, $email, $title, $message)
    {
        $data = [
            'author'     => $author,
            'name'       => $name,
            'email'      => $email,
            'title'      => $title,
            'message'    => $message,
            'status'     => 'new'
        ];

        return $this->insert($data);
    }

    public function updateSuggestion($id, $status)
    {
        return $this->update($id, [
            'status' => $status,
        ]);
    }
    
    public function deleteSuggestion($id)
    {
        return $this->delete(['id' => $id]);
    }
}
