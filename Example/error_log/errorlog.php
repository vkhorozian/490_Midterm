<?php
//following lines sets the ini values to record messages
ini_set('display_errors', 1);
ini_set("log_errors", 1);
ini_set("error_log", dirname(__FILE__).'/log.txt'); // returns the directory the file log.txt is in while writing the error to it.
error_reporting(E_ALL);
require 'log.txt';


?>
