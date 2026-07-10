<?php

namespace App\Models;

use CodeIgniter\Model;

class FeedCommentModel extends Model{

    protected $table              = 'feed_comments';
    protected $primaryKey         = 'id';
    protected $useAutoIncrement   = true;
    protected $returnType         = "object";
    protected $useSoftDeletes     = true;
    protected $allowedFields      = ['author', 'content', 'feed'];
    protected $useTimestamps      = true;
    protected $createdField       = 'created_at';
    protected $updatedField       = 'updated_at';
    protected $deletedField       = 'deleted_at';
    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;
    
    public function getFeedComments($feed)
    {
        $this->select('feed_comments.*, CONCAT(users.name, " ", users.lastname) as author_name, users.photo as author_photo');
        $this->join('users', 'users.id = feed_comments.author', 'left');
        $this->where('feed_comments.feed', $feed);
        return $this->orderBy('feed_comments.created_at', 'DESC')->findAll();
    }

    public function getFeedComment($id)
    {
        $this->select('feed_comments.*, CONCAT(users.name, " ", users.lastname) as author_name, users.photo as author_photo');
        $this->join('users', 'users.id = feed_comments.author', 'left');
        return $this->where('feed_comments.id', $id)->first();
    }

    public function getFeedCommentsCount($feed)
    {
        return $this->where('feed', $feed)->countAllResults();
    }

    public function createFeedComment($author, $feed, $content)
    {
        $data = [
            'author'     => $author,
            'feed'       => $feed,
            'content'    => $content
        ];

        return $this->insert($data);
    }

    public function updateFeedComment($id, $content)
    {
        return $this->update($id, [
            'content' => $content,
        ]);
    }
    
    public function deleteFeedComment($id)
    {
        $sql = "DELETE FROM feed_comments WHERE id = :id:";
        $this->db->query($sql, ['id' => $id]);
        return $this->db->affectedRows();
    }
}
