<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class HomeController implements IController
{
    
    public function indexAction($params)
    {
        $repo = new MovieRepository();
        $result = $repo->getMovies();
        return new View($result);
    }
    
    public function getName()
    {
        return "Home";
    }
}