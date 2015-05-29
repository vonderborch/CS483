<?php

class Movie
{
    public $film_id;
    public $title;
    public $description;
    public $actors;
    public $categories;
    public $release_year;
    
    public function __construct() 
    {
        $this->actors = array();
        $this->categories = array();
        $this->film_id = 0;
        $this->title = "";
        $this->description = "";
        $this->release_year = "";
    }
}
