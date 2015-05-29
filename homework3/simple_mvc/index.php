<?php

    include_once("controllers/IController.php");
    include_once("controllers/ErrorController.php");
    include_once("controllers/HomeController.php");
    include_once("controllers/MovieController.php");
    include_once("utilities/view.php");
    include_once("utilities/mvc_functions.php");
    include_once("models/Movie.php");
    include_once("models/MovieRepository.php");
    include_once("models/queries/DatabaseConfig.php");
    include_once("models/queries/DatabaseFactory.php");
    include_once("models/queries/QueryBase.php");
    include_once("models/queries/MoviesQuery.php");

    function getCurrentUri()
    {
        $basepath = implode('/', array_slice(explode('/', $_SERVER['SCRIPT_NAME']), 0, -1)) . '/';
        $uri = substr($_SERVER['REQUEST_URI'], strlen($basepath));
        if (strstr($uri, '?')) $uri = substr($uri, 0, strpos($uri, '?'));
        $uri = '/' . trim($uri, '/');
        return $uri;
    }
 
    $base_url = getCurrentUri();
    $routes = array();
    $routes = explode('/', $base_url);
    foreach($routes as $route)
    {
        if(trim($route) != '')
            array_push($routes, $route);
    }
    $control = $routes[1];
    if($control == "")
    {
        mvc_redirect("", "", "");
    }
    else
    {
        $action = $routes[2];
        $params = [];
        //var_dump($routes);
        for($i = 3; $i < count($routes); $i++)
        {
            //var_dump($i);
            array_push($params, $routes[$i]);
        }
        mvc_redirect($control, $action, $params);
    
    }
?>