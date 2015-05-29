<?php

class UserModel extends CI_Model
{
    public $id;
    public $firstname;
    public $lastname;
    public $username;
    public $email;
    public $password;
    public $theme;
    public $rank;
    public $date;

    public function __construct()
    {
        parent::__construct();
    }

    public function GetAll()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    public function GetByID($id)
    {
        $query = $this->db->get_where('users', array('id' => $id));
        return $query->row();
    }

    public function GetByUserName($name)
    {
        $query = $this->db->get_where('users', array('username' => $name));
        return $query->row();
    }

    private function Update($user)
    {
        $this->db->set('username', $this->username);
        $this->db->set('firstname', $this->firstname);
        $this->db->set('lastname', $this->lastname);
        $this->db->set('email', $this->email);
        $this->db->set('password', $this->password);
        $this->db->set('theme', $this->theme);
        $this->db->set('rank', $this->rank);
        $this->db->where('id', $this->id);
        return $this->db->update('users');
    }

    private function Insert($user)
    {
        return $this->db->insert('users', $this);
    }

    public function Delete()
    {
        $this->db->where('id', $this->id);
        return $this->db->delete('users');
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
}

?>