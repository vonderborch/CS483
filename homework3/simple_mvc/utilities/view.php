<?php

class View
{
    protected $view_file;
    protected $view_layout;
    protected $view_bag = array();
    protected $mvc_header = "";
    protected $mvc_body = "";

    public function __construct($data, $name = "home")
    {
        $this->view_bag = $data;
        
        $filebaselayout = "./views/layout.php";
        if (file_exists($filebaselayout) == false)
        {
            $filebaselayout = "";
        }

        $basedir = "./views/";
        $file = "./views/home/index.php";
        $name = strtolower($name);
        if ($dh = opendir($basedir))
        {
            while (($file = readdir($dh)) !== false)
            {
                $file = strtolower($file);
                if (filetype($basedir . $file) == "dir")
                {
                    if ($file == $name)
                    {
                        $basedir2 = $basedir . $file;
                        if ($dh2 = opendir($basedir2))
                        {
                            $this->view_layout = $basedir2 . "/layout.php";
                            if (file_exists($this->view_layout) == false)
                            {
                                $this->view_layout = $filebaselayout;
                            }

                            while (($file2 = readdir($dh2)) !== false)
                            {
                                if ($file2 != "." && $file2 != "..")
                                {
                                    $this->view_file = $basedir2 . "/" . $file2;
                                }
                            }
                        }
                        break;
                    }
                }
            }
            closedir($dh);
        }
    }

    public function set($key, $value)
    {
        $this->view_bag[$key] = $value;
    }

    public function get($key)
    {
        return $this->view_bag[$key];
    }

    public function output()
    {
        extract($this->view_bag);
        ob_start();
        $mvc_header = $this->mvc_header;
        $mvc_body = $this->mvc_body;
        $view_bag = $this->view_bag;
        if ($this->view_layout != "")
        {
            include($this->view_layout);
        }
        include($this->view_file);
        $output = ob_get_contents();
        ob_end_clean();
        echo $output;

        mvc_get_base_url();
    }
}

?>