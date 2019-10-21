<?php

ini_set('user_agent', 'any');
//ini_set('display_errors', 0);

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

// ****************************************************


$iAmount = $_POST['amount'];
if( empty( $iAmount ) ){ sendResponse(-1, __LINE__, 'Amount is missing'); }
if( strlen( $iAmount ) <= 0 ){ sendResponse(-1, __LINE__, 'Amount must be at least 1'); }
if( !ctype_digit($iAmount)){ sendResponse(-1, __LINE__, 'Amount can only contain numbers');  }

$sPhone = $_POST['phone'];
if( empty( $sPhone ) ){ sendResponse(-1, __LINE__, 'Phone missing'); }
if( strlen($sPhone) != 8 ){ sendResponse(-1, __LINE__, 'Phone must be 8 characters in length'); }
if( !ctype_digit($sPhone) ){ sendResponse(-1, __LINE__, 'Phone can only contain numbers');  }

$sMessage = $_POST['message'];
if( empty( $sMessage ) ){ sendResponse(-1, __LINE__, 'Message is missing'); }
if( strlen($sMessage) > 25 ){ sendResponse(-1, __LINE__, 'Message cannot be more than 25 characters'); }

// check if phone is in bank
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { sendResponse(-1, __LINE__, 'Cannot convert data to JSON'); }
$jInnerData = $jData->data;

if (! $jInnerData->$sPhone ) {
    sendResponse( -1, __LINE__ , 'Phone does not exist' ); 
}

if ( $sPhone == $sUserId ) {
  sendResponse(0, __LINE__, 'Sorry, you cannot request money from yourself.');
}

// create structure of requests
$jRequests = $jInnerData->$sPhone->requests;

$jRequestId = rand(1000, 9999);
$jRequests->$jRequestId = new stdClass();
$jRequest = new stdClass();
$jRequest->phone = $sUserId;
$jRequest->amount = $iAmount;
$jRequest->status = 0;
$jRequest->message = $sMessage;
$jRequests->$jRequestId = $jRequest;


//save to file
$sData = json_encode($jData, JSON_PRETTY_PRINT);
if( $sData == null   ){ sendResponse(-1, __LINE__, 'Cannot read JSON data'); }
file_put_contents('../data/clients.json', $sData);

sendResponse( 1, __LINE__ , "Requested amount of $iAmount kr,- has been sent to $sPhone" );

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
  echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
  exit;
}