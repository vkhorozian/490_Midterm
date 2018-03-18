<?php
//include('breakForDatabase.php');
include_once('apiKeys.php');
//Change absolute path

function runAPI($pageKeys)
{
    /*if ($pageKeys == "homepage") {
        $service_url = keySelector("$pageKeys");
        //or break im not to sure
    } elseif ($pageKeys == "test") {
        return "test worked";
    }*/

    switch ($pageKeys) {
        case "homepage" :
            $service_url = keySelector("$pageKeys");
            break; //or break im not to sure
        case "test" :
            return "worked";
    }

    $service_url = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,ETH,LTC,BCH,TRX,XLM&tsyms=BTC,USD';

    //$service_url = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,ETH,LTC,BCH,TRX,&tsyms=BTC,USD';
    $curl = curl_init($service_url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);

    if ($curl_response === false) {
        $info = curl_getinfo($curl);
        curl_close($curl);
        die('error occured during curl exec. Additioanl info: ' . var_export($info));
    }

    curl_close($curl);

    $decoded = json_decode($curl_response); //decoded is my variable with the array inside of it

    //print_r($decoded);
    //include('breakForDatabase.php');

    $theArray = breakUpJson($decoded);

    //print_r($theArray);
    if (isset($decoded->response->status) && $decoded->response->status == 'ERROR') {
        die('error occured: ' . $decoded->response->errormessage);
    }
    echo 'response ok!';
    return $theArray; // this is the array that you used
}


function breakUpJson($decoded) {
    for($i = 0; $i < 7; $i++)
    {
        if($i==0){		//Bitcoin
            $coinType = "BTC";
            $currency = "USD";
            $bitcoinArr = varDumping($coinType, $currency, $decoded, $i);
        }
        elseif($i==1){	//Etherium
            $coinType = "ETH";
            $currency = "BTC";
            $etheriumArr = varDumping($coinType, $currency, $decoded, $i);
        }
        elseif($i==2){	//Litecoin
            $coinType = "LTC";
            $currency = "BTC";
            $litecoinArr = varDumping($coinType, $currency, $decoded, $i);
        }
        elseif($i==3){	//Bitcoincash
            $coinType = "BCH";
            $currency = "BTC";
            $bitcoincashArr = varDumping($coinType, $currency, $decoded, $i);
        }
        elseif($i==4){	//Tron
            $coinType = "TRX";
            $currency = "BTC";
            $tronArr = varDumping($coinType, $currency, $decoded, $i);
        }
        elseif($i==5){	//Steller
            $coinType = "XLM";
            $currency = "BTC";
            $stellerArr = varDumping($coinType, $currency, $decoded, $i);
        }
        //Will execute when the other arrays are populated and return the multi dimensional array
        if($i == 6){

            //Coin case array holds the 5 arrays that have the values of each coin we need
            $coinCase = array(
                "bitcoin" => $bitcoinArr,
                "etherium" => $etheriumArr,
                "litecoin" => $litecoinArr,
                "bitcoincash" => $bitcoincashArr,
                "tron" => $tronArr,
                "steller" => $stellerArr
            );

            return $coinCase;
        }
    }
}


function varDumping($coinType, $currency, $decoded, $i){
    //Currency variable can be "USD" or "BTC"

    ob_start();
    $symbol =(var_export($decoded->RAW->$coinType->$currency->FROMSYMBOL, true));

    $price = (var_export($decoded->RAW->$coinType->$currency->PRICE, true));

    $lastUpdate = (var_export($decoded->RAW->$coinType->$currency->LASTUPDATE, true));

    $volume = (var_export($decoded->RAW->$coinType->$currency->VOLUME24HOUR, true));

    $open = (var_export($decoded->RAW->$coinType->$currency->OPENDAY, true));

    $high = (var_export($decoded->RAW->$coinType->$currency->HIGHDAY, true));

    $low = (var_export($decoded->RAW->$coinType->$currency->LOWDAY, true));

    $change = (var_export($decoded->RAW->$coinType->$currency->CHANGEPCT24HOUR,true));

    $totalVolume = (var_export($decoded->RAW->$coinType->$currency->TOTALVOLUME24H, true));

    $arrayToReturn = array(
        "FROMSYMBOL" => $symbol,
        "COINID" => $i,
        "PRICE" => $price,
        "LASTUPDATE" => $lastUpdate,
        "VOLUME24HOUR" => $volume,
        "OPENDAY" => $open,
        "HIGHDAY" => $high,
        "LOWDAY" => $low,
        "CHANGEPCT24HOUR" => $change,
        "TOTALVOLUME24H" => $totalVolume
    );

    return $arrayToReturn;


}


?>