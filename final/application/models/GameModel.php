<?php

class GameModel extends CI_Model
{
    public $id;
    public $name;
    public $description;
    public $upvotes = 0;
    public $downvotes = 0;
    public $voters;
    public $date;
    public $htmlengine;

    public function __construct()
    {
        parent::__construct();
    }

    public function GetAll()
    {
        $query = $this->db->get('games');
        return $query->result();
    }

    public function GetByID($id)
    {
        $query = $this->db->get_where('games', array('id' => $id));
        return $query->row();
    }

    private function Insert($comment)
    {
        return $this->db->insert('games', $this);
    }

    private function Update($comment)
    {
        $this->db->set('name', $this->name);
        $this->db->set('description', $this->description);
        $this->db->where('id', $this->id);
        return $this->db->update('games');
    }

    public function Delete()
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('games');
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
        return $this->db->update('games');
    }
}

?>