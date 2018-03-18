<?php
session_start();
require_once('db/rickys_scripts.php');

$wallet_array = getUserWalletData($_SESSION['userID']);

print_r($wallet_array);

?>

<!DOCTYPE html5>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="device-width, initial-scale =1">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="Crypto.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>

    <title> Homepage </title>

    <style>
        .jumbotron {
            background-color: darkcyan;
        }
        .vertical-center {
            min-height: 100%;
            min-height: 100vh;
            display: flex;
        }
        .fontForme{
            text-align: center;
        }
        .td{
            text-align: center;
        }
        .page-header{
            text-align: center;
        }
        .form-control {
            text-align: center;
        }
        .container {
            background-color: white;
            width: 50%;
        }
        .h2 {
            font-color: white;
        }
        .jumbotron h1,
        .jumbotron .h1 {
            font-size: 2.1em;
            font-family: 'Pacifico', cursive;
        }
        .jumbotron p,
        .jumbotron .p {
            font-size: 0.8em;
        }
        .signup-session h2,
        .signup-session .h2 {
            font-color: rgb(0,0,0);
            font-family: 'Pacifico', cursive;
            text-align: center;
            line-height: 1.13;
            height: 34px;
            font-size: 100px;
        }
    </style>

</head>
<body>
<div class="jumbotron vertical-center">
    <div class="container">

        <div class="page-header">
            <h1> Coin View </h1>
        </div>
        <main>
            <section>
                <table class="table table-striped table-bordered table-hover table-condensed">
                    <thead>
                    <tr>
                        <th>Coin Name</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </section>
            <form action="user_wallet.php" method="post">
                <button type="submit" class="btn btn-block" name="coin_symbol"
                        id="">Jump To Wallet</button>
            </form>
        </main>
    </div>
</body>

<script src="https://ajax.googleapis.com/ajas/libs/jquery/2.1.3/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script scr="js/bootstrap.min.js"></script>

</html>
