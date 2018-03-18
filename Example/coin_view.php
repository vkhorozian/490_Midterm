<?php

session_start();

error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
//echo "<b>Results from coin_view.php</b><br><br>";

include('db/rickys_scripts.php');
//include_once ("function.php");
//include('simpleapi.php'); // this is giving me an array called cooin list when i call a function
//include_once('api.php');

/*
 * Created by PhpStorm.
 * User: vkhor
 * Date: 4/18/2017
 * Time: 4:59 PM
 */


echo "user ID is = " . $_SESSION["userID"];



///$pageKeys = ; // this is the cases that the API switch statement to decide what API key you need for this webpage

$array = getCoinData(); // this is the function i run to recieve all of the coins from the api

//print_r($array);
//
//$coinList = runHome();

//print_r($coinList);

//print_r($array);


//test array of arrays see how to print this using a for loop
$arr1 = array(1, 2, 3, 4);
$arr2 = array(11, 22, 33, 44);
$arr3 = array(111, 222, 333, 444);
$mid1 = array($arr1,$arr2,$arr3);

$arr11 = array(12, 22, 32, 42);
$arr22 = array(112, 222, 332, 442);
$arr33 = array(1112, 2222, 3332, 4442);
$mid2 = array($arr11,$arr22,$arr33);

$arr111 = array(13, 23, 33, 43);
$arr222 = array(113, 223, 333, 443);
$arr333 = array(1113, 2223, 3333, 4443);
$mid3 = array($arr111,$arr222,$arr333);

$kingarr = array($mid1,$mid2,$mid3);



//echo "\n" . "\n" . "\n" . "\n";
/*
for($i = 0 ; $i < 3 ; $i++){ // mid1
    //echo $kingarr[$i] . "\n";
    for ($j = 0; $j < 3 ; $j++){ //arr1
       // echo $kingarr[$j] . "\n";
        for ($z = 0 ; $z < 4 ; $z++) // 1
            echo $array[$i][$j][$z] . "\n";
    }
}


//////////////////////////////////////////////////

    <section>
    <?php if ($array->num_rows > 0): ?>
        <table>
            <tr>
                <th>Coin Symbol</th>
                <th>Price</th>
                <th>Volume</th>
                <th>Percent Change</th>
            </tr>

            <?php while($row = $array->fetch_assoc()) : ?>
            <tr>
                <td><?php $row[""] ?></td>
                <td><?php $row["Email"] ?></td>
                <td><?php $row["Email"] ?></td>
            </tr>

        </table>
    </section>


foreach ($array as $key => $value) :
    echo $key . $value['bitcoin'];
endforeach;



( [0] => Array (
        [symbol] => BTC
        [coinID] => 0
        [coinPrice] => 8227.32000000
        [lastUpdate] => 1521256154
        [24Volume] => 118609.84186979
        [openDay] => 8283.23000000
        [highDay] => 8325.23000000
        [lowDay] => 8111.77000000
        [changePct24Hour] => 2.17874313
        [totalVolume24H] => 484145.31620195
)

*/


//foreach ($coinList as $key => $value) :
 //   echo $coins['bitcoin'];
//endforeach;

//$stuff = "stufffasljkdfhalkjwsehf";
//$transactions = admin_get_trans_data();

//print_r($array);

$coin_name = array('Bitcoin', 'Etherium', 'Litecoin', 'BitcoinCash', 'Tron');

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
                                <th>Coin Symbol</th>
                                <th>Price</th>
                                <th>Volume</th>
                                <th>Percent Change</th>
                                <th>Trade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 0; foreach ($array as $coin_array => $value) : ?>
                                <tr>
                                    <td><center><?php echo $coin_name[$count]; ?></center></td>
                                    <td><center><?php echo $value['symbol']; ?></center></td>
                                    <td><center><?php echo $value['coinPrice'] ." BTC"; ?></center></td>
                                    <td><center><?php echo number_format($value['24Volume'], 2); ?></center></td>
                                    <td><center><?php echo number_format($value['changePct24Hour'], 2)."%"; ?></center></td>

                                    <td>
                                        <form action="buy_sell.php" method="post">
                                            <button type="submit" class="btn btn-block" name="coin_symbol"
                                                    id="coin_symbol" value="<?php echo $value['symbol']; ?>" >Trade <?php echo $value['symbol']; ?></button>
                                        </form>
                                    </td>

                                </tr>
                                <?php $count++; endforeach; ?>
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

