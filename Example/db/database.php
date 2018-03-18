<?php
$dsn = 'mysql:host=localhost;dbname=it490';
$username = 'root';
$password = 'root';
try {
    $db = new PDO($dsn, $username, $password);
} catch (PDOException $e) {
    $error_message = $e->getMessage();
    echo $error_message;
    exit();
}
?>