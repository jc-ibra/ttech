<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedModel extends Model{

    protected $table              = 'feed';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['author', 'body_content', 'file_path', 'comments_active', 'image_path', 'likes_count', 'likes_detail'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
    private function baseFeedQuery()
    {
        // El autor es una cuenta de login (users). Estas cuentas no tienen
        // puesto (ocupation), así que author_ocupation queda vacío. LEFT JOIN
        // para no ocultar publicaciones cuyo autor ya no exista.
        $this->select('feed.*, CONCAT(users.name, " ", users.lastname) as author_name, users.photo as author_photo, \'\' as author_ocupation, COUNT(feed_comments.id) as comments_count');
        $this->join('users', 'users.id = feed.author', 'left');
        $this->join('feed_comments', 'feed_comments.feed = feed.id', 'left');
        $this->groupBy('feed.id');
    }

    public function getFeeds($id = null)
    {
        $this->baseFeedQuery();

        if ($id !== null) {
            $resp = $this->where('feed.id', $id)->findAll();
            return $resp[0];
        }

        return $this->orderBy('feed.created_at', 'DESC')->findAll();
    }
    
    public function getFeedsText()
    {
        $this->baseFeedQuery();
        $this->where('feed.file_path', null);
        $this->where('feed.image_path', null);

        return $this->orderBy('feed.created_at', 'DESC')->findAll();
    }
    
    public function getFeedsWithImage()
    {
        $this->baseFeedQuery();
        $this->where('feed.image_path IS NOT NULL', null, false);
    
        return $this->orderBy('feed.created_at', 'DESC')->findAll();
    }
   
    public function getFeedsWithFile()
    {
        $this->baseFeedQuery();
        $this->where('feed.file_path IS NOT NULL', null, false);
    
        return $this->orderBy('feed.created_at', 'DESC')->findAll();
    }

    public function createFeed($author, $body_content, $file_path = NULL, $image_path = NULL, $comments_active = 1)
    {
        $data = [
            'author'        => $author,
            'body_content'  => $body_content,
            'file_path'     => $file_path,
            'image_path'    => $image_path,
            'comments_active' => $comments_active
        ];

        return $this->insert($data);
    }
    
    public function deleteFeed($id)
    {
        $sql = "DELETE FROM feed WHERE id = :id:";
        $this->db->query($sql, ['id' => $id]);
        return $this->db->affectedRows();
    }
}
