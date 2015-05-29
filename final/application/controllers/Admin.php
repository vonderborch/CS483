<?php

class Admin extends CI_Controller
{
    //// constructs the admin controller
    public function __construct()
    {
        parent::__construct();
        
        // Load libraries
        $this->load->database();

        // Load helpers
        $this->load->helper('url');
            
        // Load models
        $this->load->model('PostModel', 'post');
        $this->load->model('UserModel', 'user');
        $this->load->model('CommentModel', 'comments');
        $this->load->model('GameModel', 'game');
    }
    
    //// Index Action: default homescreen for the website
    public function Index()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        // Get data from model
        $data['posts'] = $this->post->GetAll();
        
        // Load views
        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }
    
    //// AdminIndex Action: default homescreen for the admin screen
    public function AdminIndex()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4) {
            // Load views
            $this->load->view('header');
            $this->load->view('admin');
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// ListPosts Action: lists all posts in an admin-specific style
    public function ListPosts()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 3) {
            // Get data from model
            $data['posts'] = $this->post->GetAll();
        
            // Load views
            $this->load->view('header');
            $this->load->view('adminlist', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// AdminUsers Action: lists all users in an admin-specific style
    public function AdminUsers()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 0)) {
            // Get data from model
            $data['users'] = $this->user->GetAll();
        
            // Load views
            $this->load->view('header');
            $this->load->view('listusers', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// DeletePost Action: deletes a post
    public function DeletePost()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        $post = $this->post->GetById($this->uri->segment(3));
        if (isset($_SESSION["username"]) && ($_SESSION["rank"] == 0 || $_SESSION["username"] == $post->author)) {
            $post = new PostModel();
            $post->id = $this->uri->segment(3);
            if ($post->Delete()) {
                redirect('Admin/ListPosts', 'location');
            }
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// DeleteUser Action: deletes a user
    public function DeleteUser()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] == 0) {
            $user = new UserModel();
            $user->id = $this->uri->segment(3);
            if ($user->Delete()) {
                redirect('Admin/AdminUsers', 'location');
            }
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// EditUser Action: edits a user
    public function EditUser()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && (($_SESSION["rank"] == 2 || $_SESSION["rank"] == 0) ||
                                             ($_SESSION["id"] == $this->uri->segment(3)))) {
            if ($_POST) 
            {
                // Build post object
                $user = new UserModel();
                $user->id = $this->uri->segment(3);
                $user->username = $this->input->post('username', TRUE);
                $user->firstname = $this->input->post('firstname', TRUE);
                $user->lastname = $this->input->post('lastname', TRUE);
                $user->email = $this->input->post('email', TRUE);
                $user->password = $this->input->post('password', TRUE);
                $user->theme = $this->input->post('theme', TRUE);
                $user->rank = $this->input->post('rank', TRUE);
            
                $srank = intval($_SESSION["rank"]);
                $urank = intval($user->rank);
                if ($srank < $urank) {
                    $user->rank = $srank;
                }
            
                // Save post to database
                if ($user->Save()) {
                    $_SESSION["id"] = $user->id;
                    $_SESSION["username"] = $user->username;
                    $_SESSION["fname"] = $user->firstname;
                    $_SESSION["lname"] = $user->lastname;
                    $_SESSION["email"] = $user->email;
                    $_SESSION["rank"] = $user->rank;
                    $_SESSION["theme"] = $user->theme;

                    redirect('Admin/AdminUsers', 'location');
                }
            }
        
            // Get post from database
            $id = $this->uri->segment(3);
            $user = $this->user->GetById($id);
        
            // Initialize form
            $this->load->helper('form');
            $data['action'] = site_url('Admin/EditUser/'.$id);
            $data['id'] = $user->id;
            $data['username'] = $user->username;
            $data['firstname'] = $user->firstname;
            $data['lastname'] = $user->lastname;
            $data['email'] = $user->email;
            $data['password'] = $user->password;
            $data['theme'] = $user->theme;
            $data['rank'] = $user->rank;

            // Load views	
            $this->load->view('header');
            $this->load->view('upsertuser', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// AddUser Action: adds a user
    public function AddUser()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && ($_SESSION["rank"] == 2 || $_SESSION["rank"] == 0)) {
            if ($_POST) 
            {
                // Build post object
                $user = new UserModel();
                $user->username = $this->input->post('username', TRUE);
                $user->firstname = $this->input->post('firstname', TRUE);
                $user->lastname = $this->input->post('lastname', TRUE);
                $user->email = $this->input->post('email', TRUE);
                $user->password = $this->input->post('password', TRUE);
                $user->theme = $this->input->post('theme', TRUE);
                $user->rank = $this->input->post('rank', TRUE);
            
                $srank = intval($_SESSION["rank"]);
                $urank = intval($user->rank);
                if ($srank < $urank) {
                    $user->rank = $srank;
                }

                // Save post to database
                if ($user->Save()) {
                    redirect('Admin/AdminUsers', 'location');
                }
            }
        
            // Initialize form
            $this->load->helper('form');
            $data['action'] = site_url('Admin/AddUser/');
            $data['username'] = NULL;
            $data['firstname'] = NULL;
            $data['lastname'] = NULL;
            $data['email'] = NULL;
            $data['password'] = NULL;
            $data['theme'] = NULL;
            $data['rank'] = NULL;

            // Load views	
            $this->load->view('header');
            $this->load->view('upsertuser', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// Create Action: creates a new post
    public function Create()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 2) {
            if($_POST)
            {
                // Build post object
                $post = new PostModel();
                $post->title = $this->input->post('title', TRUE);
                $post->content = $this->input->post('content', TRUE);
            
                // Save post to database
                if ($post->Save()) {
                    redirect('Admin/ListPosts', 'location');
                }
            }
    
            // Load helpers
            $this->load->helper('form');
    
            // Initialize form
            $data['action'] = site_url('Admin/Create');
            $data['title'] = NULL;
            $data['content'] = NULL;
        
            // Load views	
            $this->load->view('header');
            $this->load->view('upsert', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// Update Action: updates a post
    public function Update()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 2) {
            if ($_POST) 
            {
                // Build post object
                $post = new PostModel();
                $post->id = $this->uri->segment(3);
                $post->title = $this->input->post('title', TRUE);
                $post->content = $this->input->post('content', TRUE);
            
                // Save post to database
                if ($post->Save()) {
                    redirect('Admin/ListPosts', 'location');
                }
            }
        
            // Get post from database
            $id = $this->uri->segment(3);
            $post = $this->post->GetById($id);
        
            // Initialize form
            $this->load->helper('form');
            $data['action'] = site_url('Admin/Update/'.$id);
            $data['title'] = $post->title;
            $data['content'] = $post->content;

            // Load views	
            $this->load->view('header');
            $this->load->view('upsert', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// AdminGames Action: shows a list of all games
    public function AdminGames()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] == 0) {
            // Get data from model
            $data['games'] = $this->game->GetAll();
        
            // Load views
            $this->load->view('header');
            $this->load->view('adminlistgames', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// DeleteGame Action: deletes a game
    public function DeleteGame()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] == 0) {
            $game = new GameModel();
            $game->id = $this->uri->segment(3);
            if ($game->Delete()) {
                redirect('Admin/AdminGames', 'location');
            }
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// UpdateGame Action: updates a game
    public function UpdateGame()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] == 0) {
            if ($_POST) 
            {
                // Build post object
                $game = new GameModel();
                $game->id = $this->uri->segment(3);
                $game->name = $this->input->post('name', TRUE);
                $game->description = $this->input->post('description', TRUE);
                $game->engine = $this->input->post('engine', TRUE);
            
                // Save post to database
                if ($game->Save()) {
                    redirect('Admin/AdminGames', 'location');
                }
            }
        
            // Get post from database
            $id = $this->uri->segment(3);
            $game = $this->game->GetById($id);
        
            // Initialize form
            $this->load->helper('form');
            $data['action'] = site_url('Admin/UpdateGame/'.$id);
            $data['name'] = $game->name;
            $data['description'] = $game->description;
            $data['engine'] = $game->htmlengine;

            // Load views	
            $this->load->view('header');
            $this->load->view('upsertgame', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
    
    //// CreateGame Action: creates a game
    public function CreateGame()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] == 0) {
            if($_POST)
            {
                // Build post object
                $game = new GameModel();
                $game->name = $this->input->post('name', TRUE);
                $game->description = $this->input->post('description', TRUE);
                $game->htmlengine = $this->input->post('engine', TRUE);
            
                // Save post to database
                if ($game->Save()) {
                    redirect('Admin/AdminGames', 'location');
                }
            }
    
            // Load helpers
            $this->load->helper('form');
    
            // Initialize form
            $data['action'] = site_url('Admin/CreateGame');
            $data['name'] = NULL;
            $data['description'] = NULL;
            $data['engine'] = NULL;
        
            // Load views	
            $this->load->view('header');
            $this->load->view('upsertgame', $data);
            $this->load->view('footer');
        }
        else {
            redirect('Blog/Index', 'location');
        }
    }
}