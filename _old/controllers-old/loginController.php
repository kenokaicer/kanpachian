<?php namespace controllers;

Class loginController extends baseController 
{
    
    
        public  function showView() { 
        require_once ROOT . 'views/login.php';
    }
    
    public function redirect()
    {
        \controllers\defaultController::showView();
    }
    public function user()
    {
         \config\Session::destroy();
        defaultController::closeView();
    }
    public function login($datos)
    {
      $usuario = \dao\userDao::get()->autenticar($datos);

      if($usuario!=null)
            {
                 $this->createSessionVars($usuario);
                     adminController::showView();
            }
            else
            {
                errorController::get()->usuarioIncorrecto();
            }
    }
    public function register($datos)
    {
        $usuario = \dao\userDao::get()->register($datos);
 
      if($usuario!=null)
            {
                     createSeassonVars($usuario);
                     
                 if($usuario[0]['Administrador']!=1)
                      userController::showView();
                  else
                      adminController::showView(); 
            }
            
               // require_once ROOT . 'views/login.php';
        
    }
    
    public function createSessionVars($usuario)
    {

        \config\Session::setSession('id', $usuario[0]['id']);
        \config\Session::setSession('username', $usuario[0]['username']);
        \config\Session::setSession('admin', $usuario[0]['admin']);
        
        // var_dump($expression);
        
        
    }
    
    
}