<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class MovieController implements IController
{
    
    public function indexAction($params)
    {
        mvc_redirect("home", "index");
    }
    
    public function detailsAction($params)
    {
        $repo = new MovieRepository();
        $vm = $repo->getMovieDetails($params[0]);
        return new View($vm, 'details');
    }
    
    public function getName()
    {
        return "Movie";
    }
}