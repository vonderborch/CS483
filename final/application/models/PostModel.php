<?php

class PostModel extends CI_Model
{
    public $id;
    public $title;
    public $content;
    public $date;
    public $author;
    public $upvotes = 0;
    public $downvotes = 0;
    public $voters;

    public function __construct()
    {
        parent::__construct();
    }

    public function GetAll()
    {
        $this->db->order_by('date', 'desc');
        $query = $this->db->get('posts');
        return $query->result();
    }

    public function GetByID($id)
    {
        $query = $this->db->get_where('posts', array('id' => $id));
        return $query->row();
    }

    private function Insert($post)
    {
        session_start();
        $this->author = $_SESSION["username"];
        return $this->db->insert('posts', $this);
    }

    private function Update($post)
    {
        $this->db->set('title', $this->title);
        $this->db->set('content', $this->content);
        $this->db->where('id', $this->id);
        return $this->db->update('posts');
    }

    public function Delete()
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('posts');
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
        return $this->db->update('posts');
    }
}

?>