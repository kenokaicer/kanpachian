<?php namespace Controllers\Ajax; 

require_once "../../config/Config.php";
require_once "../../config/Autoload.php";
use Config\Autoload as Autoload;
use Dao\BD\UserDao as UserDao;

Autoload::start();

if(isset($_POST['function'])){
    $func = $_POST['function'];
}
else {
    //error
    echo "error, function not set";
}

if(isset($_POST['value'])){
    $var = $_POST['value'];
}
else {
    //error
    echo "error, value not set";
}

if($func == "usernameExist"){
    try{
        $userDao = new UserDao();
        
        $user = $userDao->getByUsername($var, "disabled");

        if(is_null($user)){
            $exist = false;
        }else{
            $exist = true;
        }

        echo json_encode($exist);
    }catch (Exception $ex){
        echo json_encode($ex->getMessage());
    }
}



?>