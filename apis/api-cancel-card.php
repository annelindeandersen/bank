<?php

// if it is NOT set take me back to login
if( !isset( $_GET['id'] )) {
    header('Location: ../index.php#login');
}

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if ( $jData == null ) { echo 'json cannot be read'; exit;}

// loop through customers and find id match
foreach( $jData->data as $jClientId => $jClient ) {

    foreach( $jClient->creditCards as $sCreditCardId => $jCreditCard ) {
        
        if ( $sCreditCardId == $_GET['id'] ) {

            // flip the active key to 0 - maths: true false flips
            $jCreditCard->active = ! $jCreditCard->active;

            if ( $jCreditCard->active == false ) {
                $jCreditCard->active = 0;
                // remove from data???
            }

            if ( $jCreditCard->active == true ) {
                $jCreditCard->active = 1;
            }

            // convert json back to text and 
            $sData = json_encode($jData, JSON_PRETTY_PRINT);
            
            // save data back to file
            file_put_contents('../data/clients.json', $sData);
            
            // redirect
            header ('Location: ../profile.php');
            exit;
        }
    }
}

echo 'no match';