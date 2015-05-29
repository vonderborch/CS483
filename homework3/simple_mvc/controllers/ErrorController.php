<?php
class ErrorController implements IController
{
    public function indexAction($params)
    {
        return new View();
    }
    
    public function getName()
    {
        return "Error";
    }
}