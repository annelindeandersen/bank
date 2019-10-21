<?php

ini_set('display_errors', 0);

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if ( $jData == null ) { fnvSendResponse( -1, __LINE__, 'Cannot convert data file to JSON' ); }

$jInnerData = $jData->data;

$sPhoneFromOtherServer = $_GET['phone'];
$iAmountFromOtherServer = $_GET['amount'];
$sMessageFromOtherServer = $_GET['message'];


//if phone exists in this bank - get the amount and the message
if( !$jInnerData->$sPhoneFromOtherServer ) {
    fnvSendResponse( 0, __LINE__, 'Phone not registered in Bank of Linde' );
}

// give the amount to the registered phone
$jInnerData->$sPhoneFromOtherServer->balance += $iAmountFromOtherServer;

// create transaction object
$jTransaction->date = time();
$jTransaction->amount = $iAmountFromOtherServer;
$jTransaction->name = 'Name'; 
$jTransaction->lastName = 'Last name'; 
$jTransaction->fromPhone = $sPhoneFromOtherServer;
$jTransaction->message = $sMessageFromOtherServer;

$sTransactionUniqueId = uniqid();

$jInnerData->$sPhoneFromOtherServer->transactions->$sTransactionUniqueId = $jTransaction;
$jInnerData->$sPhoneFromOtherServer->transactionsNotRead->$sTransactionUniqueId = $jTransaction;

// Upload new amount back in data
$sData = json_encode( $jData, JSON_PRETTY_PRINT );
file_put_contents('../data/clients.json', $sData);

//SUCCESS MESSAGE
fnvSendResponse( 1, __LINE__, 'Transaction success from Bank of Linde' );


/**************************************** */

function fnvSendResponse( $iStatus, $iLineNumber, $sMessage ) {
    echo '{"status": '.$iStatus.', "code": '.$iLineNumber.', "message":'.$sMessage.'}';
    exit;
}