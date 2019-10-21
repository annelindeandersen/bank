<?php

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

$sCreditCardType = $_POST['cardType'];

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if ( $sCreditCardType == 'chooseType' ) { sendResponse(-1, __LINE__, 'error'); }

if ( $sCreditCardType != 'chooseType' ) { 

// create structure in the data ******************

$jCreditCards = $jData->data->$sUserId->creditCards;

$sCreditCardId = rand(10000, 99999);
$jCreditCards->$sCreditCardId = new stdClass();
$jCreditCard = new stdClass();
$jCreditCard->type = $sCreditCardType;
$jCreditCard->regnr = rand(1000, 9999);
$jCreditCard->cardnr = rand(1000000000, 9999999999);
$jCreditCards->$sCreditCardId = $jCreditCard;
$jCreditCard->balance = 0;
$jCreditCard->active = 1;
echo json_encode($jCreditCard);

$sData = json_encode( $jData, JSON_PRETTY_PRINT );
file_put_contents('../data/clients.json', $sData);

sendResponse(1, __LINE__, 'Success');

}

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }