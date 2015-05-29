<?php
    function mvc_build_url($controller, $action, $params = [])
    {
        $baseurl = mvc_get_base_dir() . "/" . $controller . "/" . $action;
        
        if (is_array($params))
        {
            while (list(, $value) = each($params))
            {
                $baseurl = $baseurl . "/" . $value;
            }
        }
        else
        {
            $baseurl = $baseurl . "/" . $params;
        }

        return $baseurl;
    }


    function mvc_build_style_url($css)
    {
        $stylesdir = mvc_get_base_dir() . "/content/style/";
        $styles = mvc_get_files($stylesdir);

        foreach($styles as $style)
        {
            if ($style === $css)
            {
                return $stylesdir . $style;
            }
        }

        return "";
    }


    function mvc_build_script_url($script)
    {
        $scriptsdir = mvc_get_base_dir() . "/content/scripts/";
        $scripts = mvc_get_files($scriptsdir);

        foreach($scripts as $script)
        {
            if ($script === $script)
            {
                return $scriptsdir . $script;
            }
        }

        return "";
    }



    function mvc_redirect($controller, $action, $params = []) 
    {
        if($controller == "" || $controller == "Home")
        {
            $controller = new HomeController();
            $methods = (new ReflectionClass($controller))->getMethods();
            $params = array();
            $view = $controller->indexAction($params);
            $view->output();
            return;
        }
        /*Redirect's the user's browser to the supplied controller and action.*/
        $vars = array();
        $dir = "./controllers/";
        //Get Files contained within the Directory
        $files_from_dir = scandir($dir);
        //Check each file
        foreach($files_from_dir as $file)
        {
            if($file == '.' || $file == '..')
            {
                continue;
            }
            // if the controller is similar to the file name
            if(preg_match("/(" . strtolower($controller) . ")/" , strtolower($file)) )
            {
                //get declared classes to find the controller we want
                $classes = get_declared_classes();
                //var_dump($classes);
                foreach($classes as $class)
                {
                    //check to compare classes to the wanted controller
                    if(preg_match ("/(" . strtolower($class) . ")/", strtolower($file)))
                    {
                        //ensure there is a controller in the name
                        if(preg_match ( "/(controller)/", strtolower($class)))
                        {
                            //Grab methods from class
                            $methods = (new ReflectionClass($class))->getMethods();
                            //Go through Methods
                            foreach($methods as $method)
                            {
                                //IF method has the desired action
                                if(preg_match("/(" . $action . ")/" , $method))
                                {
                                    //Go through method to find desired function
                                    foreach($method as $item)
                                    {
                                        //Find desired function
                                        if(preg_match("/(" . strtolower($action) . ")/" , strtolower($item)))
                                        {
                                            //var_dump($class . " " . $item . " ". $params);
                                            $ControllerClass = New $class();
                                            $ControllerClass->$item($params)->output();
                                            return;
                                        }
                                    }
                                }
                            }
                        }
        
                
                    }
                }
            }
            //work around for bad urls
            if(preg_match("/(" . strtolower($action) . ")/" , strtolower($file)) )
            {
                //get declared classes to find the controller we want
                $classes = get_declared_classes();
                foreach($classes as $class)
                {
                    //check to compare classes to the wanted controller
                    if(preg_match ("/(" . strtolower($class) . ")/", strtolower($file)))
                    {
                        //ensure there is a controller in the name
                        if(preg_match ( "/(controller)/", strtolower($class)))
                        {
                            //Grab methods from class
                            $methods = (new ReflectionClass($class))->getMethods();
                            //Go through Methods
                            foreach($methods as $method)
                            {
                                //IF method has the desired action
                                if(preg_match("/(" . strtolower($params) . ")/" , strtolower($method)))
                                {
                                    //Go through method to find desired function
                                    foreach($method as $item)
                                    {
                                        //Find desired function
                                        if(preg_match("/(" . strtolower($params) . ")/" , strtolower($item)))
                                        {
                                            //var_dump($class . " " . $item . " ". $params);
                                            $ControllerClass = New $class();
                                            $ControllerClass->$item("")->output();
                                        }
                                    }
                                }
                            }
                        }
        
                
                    }
                }
            }
        }
    }
    
    function mvc_get_files($directory, $keepdotfiles = false)
    {
        $output = array();
        //var_dump($directory);
        if ($dh = opendir($directory))
        {
            while (($file = readdir($dh)) !== false)
            {
                if ($keepdotfiles == false &&
                    ($file == "." || $file == ".."))
                {
                    continue;
                }

                array_push($output, $file);
            }
        }

        return $output;
    }

    function mvc_get_base_dir()
    {
        $filelocation = $_SERVER['HTTP_HOST'];

        $dir = dirname($filelocation);

        return $dir;
    }
?> 