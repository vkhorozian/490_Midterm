<?php

//Richard Chipman 
//IT490 
//Professor Kehoe
//Group: Crypto Bros


error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
ini_set('display_errors',1);
//echo "<b>Results from Login.php</b><br><br>";

//Database Scripts

//include('../error_log.php');
require_once('connect.inc');//Call connectToDB() to make connection
//require_once ('../testRabbitMQClient.php');
require_once('simpleapi.php');
require_once ('database.php');


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function redirect ($message, $url)
{
    echo "<h1> $message </h1>";
    //user validation here put in the gatekeeper
    header("refresh: 2 ; url = $url");
    exit();
}


/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//Used to validate username and password entered at the front end

function doLogin($array){



    $resultsFound = false;
    $conn = connectToDb();
    $username = mysqli_real_escape_string($conn,$array['username']);
    $password = mysqli_real_escape_string ($conn,$array['password']);
    $currentEpochTime = time ();
    $validationQuery = "SELECT userID, username, password from authTable WHERE username = '".$username."'";
    $updateLastLogin = "update authTable SET lastLogin = '".$currentEpochTime."'";

    if($result = mysqli_query($conn, $validationQuery))
    {
        $all = mysqli_fetch_all($result);

        $all = array_filter($all);

        $ass = !empty($all);


        if($ass){	//True if a set of data is found
            $databaseUserID = ($all[0][0]);
            $databaseUsername = ($all[0][1]);
            $databasePasswordHash = ($all[0][2]);
            $resultsFound = true;
        }
            if($resultsFound){//Will not execute anything if the query returns nothing

                if($username == $databaseUsername)//The username is in the database
                {
                    echo("Username in Database");

                    if(password_verify($password, $databasePasswordHash))//Login will be successful
                    {

                    echo("valid password");
                    mysqli_query($conn, $updateLastLogin);

                    //send an array with the id and something that is true so i can redirect
                    $array = array();
                    $array['userID'] = "$databaseUserID";
                    $array['worked'] = true;

                    return $array;   //Gives ID to the front end for session validation

                    }

                    else{
                        return (" however password is not valid");
                    }
                }
                else {
            return ("Not a valid username");
            }
        }
    }
mysqli_close($conn);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//This function creates a user and also creates an wallet with the same ID for them
function insertUser($array)//must receive username password and email in array

{
    $conn = connectToDb();
    $username = $array['username'];
    $password = $array['password'];
    $email = $array['email'];


    $password_hash = password_hash($password, PASSWORD_BCRYPT);

    //Escape characters
    $username = mysqli_real_escape_string($conn, $username);
    $password_hash = mysqli_real_escape_string($conn, $password_hash);
    $email = mysqli_real_escape_string($conn, $email);

    //Check for valid username registration
    $checkUsernameQuery = "SELECT * from authTable where username = '$username'";
    $query = mysqli_query($conn, $checkUsernameQuery);
    $numrows = mysqli_num_rows($query);
    if($numrows > 0){//Need to send a message to the web server that this is not gunna happen
        $msg = "usernameError";
        return false;
    }

    while(true){//Randomize the userID

        $userID=(mt_rand(1,100));
        $checkTables = "SELECT userID from authTable where userID = '$userID'";
        $query = mysqli_query($conn, $checkTables);
        $numrows = mysqli_num_rows($query);

        if($numrows == 0){
            break;
        }
    }

    $insertQuery = "INSERT INTO authTable (userID, username, password, email, lastLogin) 
    VALUES ('$userID','$username', '$password_hash', '$email', '0')";

    $walletDefaultQuery = "INSERT INTO userWallet(userID, balance, bitCoinBalance, etheriumBalance, litecoinBalance, bitcoincashBalance, tronBalance)
    VALUES('$userID','100000.00000000','0.00000000','0.00000000','0.00000000','0.00000000','0.00000000')";

    $queryVal = mysqli_query($conn, $insertQuery);
    if ( $queryVal === false ) {
        echo 'error';
    }

    $queryVal2 = mysqli_query($conn, $walletDefaultQuery);
    if ( $queryVal2 === false ) {
        echo 'error';
    }
	mysqli_close($conn);
    return true;

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getCoinData()  // this is suposed to send the coin data to me i think back to the user so this is the function i call on the RBMQ
    //RBMQ server for chipman
{
    makeCoinTable(); // this should run a function which will trigger the make coin table script
    $conn = connectToDb();
    ini_set('display_errors', 1);

    $retrieveCoinData = "SELECT * from coinTable";


    if ($result = mysqli_query($conn, $retrieveCoinData)) {

        /* fetch associative array */
        $count = 0;

        //while ($all = mysqli_fetch_all($result)) {
        $all = mysqli_fetch_all($result, MYSQLI_ASSOC);
        //print_r($all);

        //}

        /* free result set */
        mysqli_free_result($result);
        return $all;
    }
    mysqli_close($conn);
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//this is going to be the function that will SELECT * FROM userWallets where "$_SESSION["userID"]" = userID; and then send back that array to the webserver to be displayed.


function getUserWalletData($userID){

    global $db;
    $query = "SELECT * FROM accounts WHERE userID = '$userID'";
    $statement = $db->prepare($query);
    $statement->execute();
    $walletArray = $statement->fetchAll();
    $statement->closeCursor();
    return $walletArray;

}






/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function makeCoinTable() // this is supposed to get an array from the api so i guess i need to run client here

{
    //$coinArr = updateCoinData();  //this is commented out rn but this is needed for the site to work the way it is supposed to
    $conn = ConnectToDb();
    $coinArr = runAPI("homepage"); //this is the one i set up rn just so you can use the run api

    ini_set('display_errors', 1);

    for ($i = 0; $i < 5; $i++){

        switch ($i) {

            case 0:
                $coin = $coinArr['bitcoin'];
                break;
            case 1:
                $coin = $coinArr['etherium'];
                break;
            case 2:
                $coin = $coinArr['litecoin'];
                break;
            case 3:
                $coin = $coinArr['bitcoincash'];
                break;
            case 4:
                $coin = $coinArr['tron'];
                break;
        }


        $symbol = $coin['FROMSYMBOL'];
        $coinID = $coin['COINID'];
        $coinPrice = $coin['PRICE'];
        $lastUpdate = $coin['LASTUPDATE'];
        $volume24 = $coin['VOLUME24HOUR'];
        $openDay = $coin['OPENDAY'];
        $highDay = $coin['HIGHDAY'];
        $lowDay = $coin['LOWDAY'];
        $changePCT24Hour = $coin['CHANGEPCT24HOUR'];
        $totalVolume24H = $coin['TOTALVOLUME24H'];

        //IF YOU WOULD LIKE TO CHECK THE VALUES//
        /*
        echo"***********************";
        echo(PHP_EOL);
        echo($symbol);
        echo(PHP_EOL);
        echo($coinID);
        echo(PHP_EOL);
        echo($coinPrice);
        echo(PHP_EOL);
        echo($lastUpdate);
        echo(PHP_EOL);
        echo($volume24);
        echo(PHP_EOL);
        echo($openDay);
        echo(PHP_EOL);
        echo($highDay);
        echo(PHP_EOL);
        echo($lowDay);
        echo(PHP_EOL);
        echo($changePCT24Hour);
        echo(PHP_EOL);
        echo($totalVolume24H);
        echo(PHP_EOL);
        */

        $insertCoinData = "INSERT INTO coinTable (symbol, coinID, coinPrice, lastUpdate, 24Volume, openDay, highDay, lowDay, changePct24Hour, totalVolume24H) VALUES (".$symbol.",'".$coinID."','".$coinPrice."','".$lastUpdate."','".$volume24."','".$openDay."','".$highDay."','".$lowDay."','".$changePCT24Hour."','".$totalVolume24H."')
	ON DUPLICATE KEY UPDATE
	coinPrice='".$coinPrice."', lastUpdate='".$lastUpdate."', 24Volume='".$volume24."', openDay='".$openDay."', highDay='".$highDay."', lowDay='".$lowDay."', changePct24Hour='".$changePCT24Hour."', totalVolume24H='".$totalVolume24H."'";

        //mysqli_query($conn,$insertCoinData) or die (mysqli_error($conn));

        if (!$conn->query($insertCoinData)) {
            echo "INSERT FAILED: (" . $conn->errno . ") " . $conn->error;
        }
    }


}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function updateCoinData() // this should actually run the client and grab the data from the api so i need something on dozas
    //RBMQ server that is going to accept an array of $array and use type runAPI

{

    $coin_criteria = array();

    $coin_criteria['type'] = "runAPI";
    $coin_criteria['$pageKey'] = "homepage";


    $updatedCoinArray = runClient($coin_criteria);

    ini_set('display_errors', 1);

    return $updatedCoinArray;

}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function buyCoins($userID, $coinSell, $coinBuy, $amount){
switch ($coinSell){
		case "USD"://Buying with USD
			$query = "SELECT balance, bitcoinBalance from userWallet WHERE userID = $userID";		
			break;

		case "BTC"://Buying with BTC
			if($coinBuy == 'ETH'){
				$query = "SELECT bitcoinBalance, etheriumBalance from userWallet WHERE userID = '".$userID."'";
			}

			elseif($coinBuy == 'TRX'){
				$query = "SELECT bitcoinBalance, tronBalance from userWallet WHERE userID = '".$userID."'";
			}

			elseif($coinBuy == 'BCH'){
				$query = "SELECT bitcoinBalance, bitcoincashBalance from userWallet WHERE userID = '".$userID."'";
			}

			elseif($coinBuy == 'XLM'){
				$query = "SELECT bitcoinBalance, stellarBalance from userWallet WHERE userID = '".$userID."'";
			}

			elseif($coinBuy == 'LTC'){
				$query = "SELECT bitcoinBalance, litecoinbalance from userWallet WHERE userID = '".$userID."'";
			}

			elseif($coinBuy == 'USD'){
				$query = "SELECT bitcoinBalance, balance from userWallet WHERE userID = '".$userID."'";
			}
			break;
		
		//Buying BTC with smaller Cryptos
		case "TRX":
			$query = "SELECT tronBalance, bitcoinBalance from userWallet WHERE userID = '".$userID."'";
			break;

		case "BCH":
			$query = "SELECT bitcoincashBalance, bitcoinBalance from userWallet WHERE userID = '".$userID."'";
			break;

		case "XLM":
			$query = "SELECT stellarBalance, bitcoinBalance from userWallet WHERE userID = '".$userID."'";
			break;

		case "LTC":
			$query = "SELECT litecoinBalance, bitcoinBalance from userWallet WHERE userID = '".$userID."'";
			break;

		case "ETH":
			$query = "SELECT etheriumBalance, bitcoinBalance from userWallet WHERE userID = '".$userID."'";
			break;
	}

$sql = connectToDb();//calls connect to receive mysqli connection

//QUERY TO GET VALUES OF THE COINS BEING EXCHANGED//
//Needs a special case for USD as the value will always be 1

	if($coinSell == "USD"){//Exchanging USD to BTC
	echo("Using balance to buy");
	$query2 = "SELECT coinPrice from coinTable where symbol = '".$coinBuy."'";

	if ($result1 = mysqli_query($sql, $query2)) {
                $all = mysqli_fetch_all($result1);
                print_r("Coin sold: USD");
                $soldPrice = 1;
                print_r(", Coin bought:");
                $boughtPrice = ($all[0][0]);
                print_r($boughtPrice);
        	}
	}

	elseif($coinBuy == "USD"){//Exchanging BTC for USD
	echo("Trading BTC FOR USD");
	$query2 = "SELECT coinPrice from coinTable where symbol = '".$coinSell."'";
       
	 if ($result1 = mysqli_query($sql, $query2)) {

                $all = mysqli_fetch_all($result1);
                print_r("Coin sold:");
                $soldPrice = ($all[0][0]);
                print_r($soldPrice);
                print_r(", Coin bought:");
                $boughtPrice = 1;
                print_r($boughtPrice);
       			}
	}

	else{//All other exchanges
	$query2 = "SELECT coinPrice from coinTable where (symbol = '".$coinSell."') OR (symbol = '".$coinBuy."')";
	
	//Use the chosen above query
	//QUERY TO GET VALUES OF THE COINS BEING EXCHANGED//

	if ($result1 = mysqli_query($sql, $query2)) {
        	$all = mysqli_fetch_all($result1, MYSQLI_ASSOC);
        	print_r("Coin sold:");
       		$soldPrice = ($all[0]['coinPrice']);
       		print_r($soldPrice);
		print_r(", Coin bought:");
		$boughtPrice = ($all[1]['coinPrice']);
		print_r($boughtPrice);
		}
	}

//QUERY TO GET THE BALANCES IN USER WALLET OF EACH COIN//
$sql = connectToDb();
	echo($query);
if ($result1 = mysqli_query($sql, $query)) {
	    $all2 = mysqli_fetch_all($result1);
	print_r($all2);

	$soldBalance = ($all2[0][0]);
	echo("Balance of sold coin:");
	echo($soldBalance);

	$boughtBalance = ($all2[0][1]);
	echo("Balance of bought coin:");
	print_r($boughtBalance);

        mysqli_free_result($result1);
	}

        //Determine if the user has necessary funds for purchase
        //$valueBuy is the value of the currency being purchased = queryResult2
        $valueBuy = ($boughtPrice * $amount);



        //$balanceValue the value of the currency exchanging for other
        $valueSell = ($soldPrice * $soldBalance);



	if ($valueBuy > $valueSell){//IF THIS STATEMENT IS TRUE THE USER DOES NOT HAVE ENOUGH FUNDS
	     echo("Cannot make the purchase");
	     //Return something to varouj that says you can't do this
	     return false;
	}

	else{

        //Number deducted from userWallet
        $deductionNumber = ($valueBuy/$soldPrice);//Total value of the purchased coin(s)/value of coin used to make purchase
        $newBuyBalance = ($boughtBalance + $amount);//Adds the userWallet balance to the purchased amount
        $newSellBalance = ($soldBalance - $deductionNumber);//Subtracts the userWallet balance by the needed amount

	echo("NEW BALANCE OF COIN 1 *AKA purchased coin");
	echo($newBuyBalance);

	echo("NEW BALANCE OF COIN 2 *AKA sold coin");
	print_r($newSellBalance);

	//UPDATE QUERIES DECLARATIONS

	switch ($coinSell){

                case "USD"://Buying with USD
                        $query3 = "UPDATE userWallet SET balance = '".$newSellBalance."', bitcoinBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        break;

                case "BTC"://Buying with BTC
                        if($coinBuy == 'ETH'){
                                $query3 = "UPDATE userWallet SET bitcoinBalance = '".$newSellBalance."', etheriumBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        }
                        elseif($coinBuy == 'TRX'){
                                $query3 = "UPDATE userWallet SET bitcoinBalance = '".$newSellBalance."', tronBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        }

                        elseif($coinBuy == 'BCH'){
                                $query3 = "UPDATE userWallet SET bitcoinBalance = '".$newSellBalance."',bitcoincashBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        }

                        elseif($coinBuy == 'XLM'){
                                $query3 = "UPDATE userWallet SET bitcoinBalance = '".$newSellBalance."', stellerBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        }

                        elseif($coinBuy == 'LTC'){
                                $query3 = "UPDATE userWallet SET bitcoinBalance = '".$newSellBalance."', litecoinBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        }

                        elseif($coinBuy == 'USD'){
                                $query3 = "UPDATE userWallet SET bitcoinBalance = '$newSellBalance', balance = '".$newBuyBalance."' where userID = '".$userID."'";
                        }
                        break;

                case "TRX":
                        $query3 = "UPDATE userWallet SET tronBalance = '".$newSellBalance."', balance = '".$newBuyBalance."' where userID = '".$userID."'";
                        break;

                case "BCH":
                        $query3 = "UPDATE userWallet SET bitcoincashBalance = '".$newSellBalance."', bitcoinBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        break;

                case "XLM":
                        $query3 = "UPDATE userWallet SET stellarBalance = '".$newSellBalance."', bitcoinBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        break;

                case "LTC":
                        $query3 = "UPDATE userWallet SET litecoinBalance = '".$newSellBalance."', bitcoinBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        break;

                case "ETH":
                        $query3 = "UPDATE userWallet SET etheriumBalance = '".$newSellBalance."', bitcoinBalance = '".$newBuyBalance."' where userID = '".$userID."'";
                        break;

}

	//QUERY TO UPDATE **ONLY EXECUTES WHEN BALANCE IS ENOUGH**//
        if ($result3 = mysqli_query($sql, $query3)) {
		echo("UPDATE WAS SUCCESSFUL!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!");
                }
}

mysqli_close($sql);
return "Coins were traded";

}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//How the front end should make calls to database function with array

//To insert a new user
$testArray = array(
	"username" => "test",
	"password" => "test",
	"email" => "test@test.com"
	);

//insertUser($testArray);

//makeCoinTable();



//How the front end should make calls to the database function authentification with array
/*
$loginTestArray = array(
        "username" => "test",
        "password" => "test",
);

$authentication  = doLogin($loginTestArray);
*/

//How to test buy sell needs 4 values in order: (userID, coinsold, coinbought, amount)
//amount = amount of buyCoin
//-amount = amount of buyCoin sold
//Simply put buying a negative amount is selling instead

//$value = buyCoins(22, 'BTC', 'USD', 1);
//buyCoins(63, 'BTC', 'ETH', 1);
//buyCoins(63, 'BTC', 'TRX', 1);
//buyCoins(63, 'BTC', 'BCH', 1);
//buyCoins(63, 'BTC', 'LTC', 1);
//buyCoins(63, 'USD', 'BTC', 1);
//buyCoins(63, 'ETH', 'BTC', 1);
//buyCoins(63, 'TRX', 'BTC', 1);
//buyCoins(63, 'BCH', 'BTC', 1);
//buyCoins(63, 'LTC', 'BTC', 1);




?>
