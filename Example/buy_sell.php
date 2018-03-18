<?php
session_start();

require_once('db/rickys_scripts.php');

$symbol_name = filter_input(INPUT_POST, 'coin_symbol');  echo $symbol_name . '<br>';

$cleansed_symbol = substr($symbol_name, -3);

$wallet_array = getUserWalletData($_SESSION['userID']);

print_r($wallet_array);

//<?php echo $symbol_name

?>
<!DOCTYPE html>
<html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="UTF-8">
    <meta name="viewport" content="device-width, initial-scale =1">
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="Crypto.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <title>The Buy/Sell Page</title>
    <script src="chart/angular.js"></script>
    <script src="chart/jquery.js"></script>


    <style>
        .jumbotron {
            background-color: darkcyan;
        }
        .vertical-center {
            min-height: fit-content;
            min-height: 100vh;
            display: flex;
        }
        .form-control {
            text-align: center;
        }
        .container {
            background-color: white ;
            width: 30%;
        }
        .h2 {
            font-color: white;
        }
        .jumbotron h1,
        .jumbotron .h1 {
            font-size: 4.5em;
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
<h1>The Crypto Bros</h1>
<body>
<div class="container">
    <div class="col-md-5">>
        <div class="widget-preview-content" style="width: 320px; min-height: 320px;" ng-class="{'preview-feed-widget': forms.type == 5, 'preview-header-widget': forms.type == 6 || forms.type == 7 || forms.type == 8 || forms.type == 9 || forms.type == 11 || forms.type == 12}" id="widget-preview-content"><script async="" type="text/javascript" id="ccc-ws-999">(function() {
                    baseUrl = "https://widgets.cryptocompare.com/"; // allows use of the widget
                    var scripts = document.getElementsByTagName("script");
                    var embedder = document.getElementById('ccc-ws-999');

                    (function (){
                        var appName = encodeURIComponent(window.location.hostname);
                        if(appName==""){appName="local";}
                        var s = document.createElement('script');
                        s.type = 'text/javascript';

                        var theUrl = baseUrl+'serve/v1/coin/chart?fsym=<?php echo $cleansed_symbol?>&tsym=USD';// api call
                        s.src = theUrl + ( theUrl.indexOf("?") >= 0 ? "&" : "?") + 'app=' + appName;
                        embedder.parentNode.appendChild(s);
                    })();
                })();
            </script>
        </div>
    </div>

    <form  method="post" action = "sell.php">
        <div class="form-group">
            <div class="cols-lg-6">
                <input class=form-control type=text name="sell_amount" id="sell_amount" required="required" placeholder="Sell Amount">
            </div>
        </div>
        <button type="submit" class="btn btn-info btn-block login-button">Sell <?php echo $cleansed_symbol?></button>
    </form>
    <form  method="post" action = "buy.php">
        <div class="form-group">
            <div class="cols-lg-6">
                <input class=form-control type=text name="buy_amount" id="buy_amount" required="required" placeholder="Buy Amount">
            </div>
        </div>
        <button type="submit" class="btn btn-info btn-block login-button">Buy <?php echo $cleansed_symbol?></button>
    </form>

</div>
<!-- these files are needed for the data to update -->
<script src="bokunopiko_files/socket.js"></script>
<script src="bokunopiko_files/jquery.js"></script>
<script src="bokunopiko_files/ccc-streamer-utilities.js"></script>
<script src="bokunopiko_files/stream.js"></script>


</body>
</html>