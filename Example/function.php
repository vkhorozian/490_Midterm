<?php


function redirect ($message, $url)
{
    echo $message;
    header("refresh: 5 ; url = $url");
    exit();
}

function doLogin($array)
{
    $username = $array['username'];
    $password = $array['password'];

    {
        if($username != "test" and $password != "test") {
            return false;
        } else {
            return true;
        }
    }
}

?>