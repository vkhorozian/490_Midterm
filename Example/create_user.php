<?php
//session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
echo "<b>Results from Login.php</b><br><br>";

include_once ('db/rickys_scripts.php');
/**
 * Created by PhpStorm.
 * User: vkhor
 * Date: 4/26/2017
 * Time: 6:25 PM
 */

    $user_name = filter_input(INPUT_POST, 'user_name');
    $password = filter_input(INPUT_POST, 'password');
    $email = filter_input(INPUT_POST, 'email');



    $user_data = array();
    $user_data['username'] = "$user_name";
    $user_data['password'] = "$password";
    $user_data['email'] = "$email";

    print_r($user_data);

    $responce = insertUser($user_data);

    if($responce === true){

        $message = "added user";
        $url = "login.html";

        redirect($message,$url);

    }else{

        $message = "Error Making username, Please Try Again";
        $url = "add_user.html";

        redirect($message,$url);
    }



?>


