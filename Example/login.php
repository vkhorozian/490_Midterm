<?php
/*
 * Every PHP session has a timeout value — a duration, measured in seconds — which determines how long a session should remain alive in the absence of any user activity.
 *  You can adjust this timeout duration by changing the value of session.gc_maxlifetime variable in the PHP configuration file (php.ini).
 */

session_start();

//include_once("../../RBMQlibs/rabbitMQLib.inc");
//include_once("../../RBMQlibs/testRabbitMQClient.php");

include('db/rickys_scripts.php');

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
echo "<b>Results from Login.php</b><br><br>";



$user_name = filter_input(INPUT_POST, 'user_name');  echo "the username is ".$user_name.'<br>';
$password = filter_input(INPUT_POST, 'password'); echo "the password is " .$password.'<br>';


$user_data = array();
$user_data['username'] = "$user_name";
$user_data['password'] = "$password";


$responce = doLogin($user_data); // this is an array userid[#], worked[true]


$_SESSION["userID"] = $responce['userID'];

//$type = "login";
//$client = runClient($type, $user_name, $password);
/*$user_data['type'] = "$type";
$responce = runClient($user_data);*/


if($responce['worked'] === true){

        $message = "Coin view here i come!!!";
        $url = "coin_view.php";
        redirect($message,$url);
}else{

        $message = "You need to make an account dirtbag!!";
        $url = "login.html";
        redirect($message,$url);

}


/*

try{
    $message = "This is the redirect";
    $url = "login.html";
    redirect($message,$url);
}catch (Exception $e) {
    echo 'Caught exception: ',  $e->getMessage(), "\n";
}




function redirect ($message, $url)
{
    echo $message;
    header("refresh: 5 ; url = $url");
    exit();
}
*/
?>


