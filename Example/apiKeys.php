<?php

// List all of the api strings needed to run api requests
function keySelector($pageKeys)
{

    switch ($pageKeys)
    {

        case "homepage" :
            $homepageAPIkey = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=BTC,ETH,LTC,BCH,TRX,XLM&tsyms=BTC,USD';
            return $homepageAPIkey;
        case "candleHour" :
            $candleHourAPIkey = 'place the key here';
            return $candleHourAPIkey;
    }
}


/*function candleDay($coinRequested)
{
    $coinperDay = 'https://min-api.cryptocompare.com/data/pricemultifull?fsyms=' .= $coinRequested .= '&tsyms=BTC'; // this is for later on when we need to have a smart way
//of dynamicly completing strings for the api keys baised on a specified value being sent from the php host
    return $coinperDay;
}*/
?>
