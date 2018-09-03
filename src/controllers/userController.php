<?php namespace controllers;

class userController
{
     public  function showView() { 
        require_once ROOT . 'views/userpanel.php';
        $dir = __FILE__;
        $url = explode("\\",$dir);
        $last = end($url);
        //header("Location:" . $last);
    }
    
    public function closeSession()
    {
        \config\Session::destroy();
        defaultController::closeView();
    }
    
}