<?php

class Blog extends CI_Controller
{
    //// constructs the blog controller
    public function __construct()
    {
        parent::__construct();
        
        // Load libraries
        $this->load->database();

        // Load helpers
        $this->load->helper('url');
            
        // Load models
        $this->load->model('PostModel', 'post');
        $this->load->model('CommentModel', 'comments');
        $this->load->model('UserModel', 'user');
        $this->load->model('GameModel', 'game');
    }
    
    //// Index Action: default homescreen for the website
    public function Index()
    {
        // Get data from model
        $data['posts'] = $this->post->GetAll();
        
        // Load views
        $this->load->view('header');
        $this->load->view('index', $data);
        $this->load->view('footer');
    }
    
    //// GameList Action: shows a list of all games
    public function GameList()
    {
        // Get data from model
        $data['games'] = $this->game->GetAll();
        
        // Load views
        $this->load->view('header');
        $this->load->view('gameslist', $data);
        $this->load->view('footer');
    }
    
    //// Game Action: displays a game (differently depending on the engine)
    public function Game()
    {
        $id = $this->uri->segment(3);
        $game = $this->game->GetById($id);
        $data['games'] = $game;

        if ($game->htmlengine == "ApolloFramework") {
            $this->load->view('gameheader');
            $this->load->view('list', $data);
            $this->load->view('footer');
        }
        else {
            $this->load->view("".$game->name."", $data);
        }
    }
    
    //// ListPosts Action: lists all posts
    public function ListPosts()
    {
        // Get data from model
        $data['posts'] = $this->post->GetAll();
        
        // Load views
        $this->load->view('header');
        $this->load->view('list', $data);
        $this->load->view('footer');
    }
    
    //// Read Action: displays a post
    public function Read()
    {
        // Get id from uri
        $id = $this->uri->segment(3);
        
        // Get data from model
        $data['post'] = $this->post->GetById($id);
        $data['comments'] = $this->comments->GetAllForPost($id);
        $data['author'] = $this->user->GetByUserName($this->post->GetById($id)->author);
        $data['commentcount'] = $this->comments->GetNumberOfCommentsForPost($id);
        
        // Load views
        $this->load->view('header');
        $this->load->view('read', $data);
        $this->load->view('footer');
    }
    
    //// CreateComment Action: creates a comment
    public function CreateComment()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4) {
            if($_POST)
            {
                // Build comment object
                $ncomment = new CommentModel();
                $id = $this->uri->segment(3);
                $ncomment->postid = $id;
                $ncomment->contents = $this->input->post('content', TRUE);
            
                // Save post to database
                if ($ncomment->Save()) {
                    redirect('blog/Read/'.$id, 'location');
                }
            }
            // get post data
            $id = $this->uri->segment(3);
    
            // Load helpers
            $this->load->helper('form');
    
            // Initialize form
            $data['action'] = site_url('blog/CreateComment/'.$id);
            $data['content'] = NULL;
        
            // Load views	
            $this->load->view('header');
            $this->load->view('addcomment', $data);
            $this->load->view('footer');
        }
        else {
            $id = $this->uri->segment(3);
            redirect('Blog/Read/'.$id, 'location');
        }
    }
    
    //// DeleteComment Action: deletes a comment
    public function DeleteComment()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4) {
            $comment = new CommentModel();
            $comment->id = $this->uri->segment(4);
            if ($comment->Delete()) {
                $id = $this->uri->segment(3);
                redirect('Blog/Read/'.$id, 'location');
            }
        }
        else {
            $id = $this->uri->segment(3);
            redirect('Blog/Read/'.$id, 'location');
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
                redirect('Blog/Index', 'location');
            }
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
                    redirect('Blog/Read/'.$post->id, 'location');
                }
            }
        
            // Get post from database
            $id = $this->uri->segment(3);
            $post = $this->post->GetById($id);
        
            // Initialize form
            $this->load->helper('form');
            $data['action'] = site_url('Blog/Update/'.$id);
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
    
    //// VotePost Action: votes for a post
    public function VotePost()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4) {
            // Build post object
            $post = new PostModel();
            $post->id = $this->uri->segment(3);
            $vote = $this->uri->segment(4);

            $postvoters = $this->post->GetById($post->id);
            $post->upvotes = $postvoters->upvotes;
            $post->downvotes = $postvoters->downvotes;

            $post->voters = $postvoters->voters.$_SESSION["id"]."&";

            if ($vote == '1') {
                $post->upvotes = $post->upvotes + 1;
            }
            elseif ($vote == '-1') {
                $post->downvotes = $post->downvotes + 1;
            }
            
            // Save post to database
            if ($post->Vote()) {
                redirect('Blog/Read/'.$post->id, 'location');
            }
        }
    }
    
    //// VoteComment Action: votes for a comment
    public function VoteComment()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4) {
            $postid = $this->uri->segment(3);
            $commentid = $this->uri->segment(4);
            $vote = $this->uri->segment(5);
            // Build post object
            $comment = new CommentModel();
            $comment->id = $commentid;
            $vote = $vote;

            $commentvoters = $this->comments->GetById($comment->id);
            $comment->upvotes = $commentvoters->upvotes;
            $comment->downvotes = $commentvoters->downvotes;

            $comment->voters = $commentvoters->voters.$_SESSION["id"]."&";

            if ($vote == '1') {
                $comment->upvotes = $comment->upvotes + 1;
            }
            elseif ($vote == '-1') {
                $comment->downvotes = $comment->downvotes + 1;
            }
            
            // Save post to database
            if ($comment->Vote()) {
                redirect('Blog/Read/'.$postid, 'location');
            }
        }
    }
    
    //// VoteGame Action: votes for a game
    public function VoteGame()
    {
        if(!isset($_SESSION)) {
            session_start();
        }
        if (isset($_SESSION["username"]) && $_SESSION["rank"] < 4) {
            $gameid = $this->uri->segment(3);
            $vote = $this->uri->segment(4);
            // Build post object
            $game = new GameModel();
            $game->id = $gameid;
            $vote = $vote;

            $gamevoters = $this->game->GetById($game->id);
            $game->upvotes = $gamevoters->upvotes;
            $game->downvotes = $gamevoters->downvotes;

            $game->voters = $gamevoters->voters.$_SESSION["id"]."&";

            if ($vote == '1') {
                $game->upvotes = $game->upvotes + 1;
            }
            elseif ($vote == '-1') {
                $game->downvotes = $game->downvotes + 1;
            }
            
            // Save post to database
            if ($game->Vote()) {
                redirect('Blog/GameList/', 'location');
            }
        }
    }
}