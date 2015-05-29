<?php

class CommentModel extends CI_Model
{
    public $id;
    public $postid;
    public $author;
    public $contents;
    public $date;
    public $upvotes = 0;
    public $downvotes = 0;
    public $voters;

    public function __construct()
    {
        parent::__construct();
    }

    public function GetAll()
    {
        $query = $this->db->get('comments');
        return $query->result();
    }

    public function GetByID($id)
    {
        $query = $this->db->get_where('comments', array('id' => $id));
        return $query->row();
    }

    public function GetAllForPost($postid)
    {
        $this->db->order_by('date', 'desc');
        $query = $this->db->get_where('comments', array('postid' => $postid));
        return $query->result();
    }

    public function GetNumberOfCommentsForPost($postid)
    {
        $this->db->order_by('date', 'desc');
        $query = $this->db->get_where('comments', array('postid' => $postid));
        return $query->num_rows();
    }

    private function Insert($comment)
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        $this->author = $_SESSION["username"];
        return $this->db->insert('comments', $this);
    }

    private function Update($comment)
    {
        $this->db->set('contents', $this->contents);
        $this->db->where('id', $this->id);
        return $this->db->update('comments');
    }

    public function Delete()
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('comments');
    }

    public function Save()
    {
        if (isset($this->id))
        {
            return $this->Update();
        }
        else
        {
            return $this->Insert();
        }
    }

    public function Vote()
    {
        $this->db->set('upvotes', $this->upvotes);
        $this->db->set('downvotes', $this->downvotes);
        $this->db->set('voters', $this->voters);
        $this->db->where('id', $this->id);
        return $this->db->update('comments');
    }
}

?>