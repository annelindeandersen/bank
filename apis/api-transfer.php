<?php

ini_set('user_agent', 'any');
//ini_set('display_errors', 0);

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

if( empty( $_GET['phone'] ) ){ sendResponse(-1, __LINE__, 'Phone missing'); }
if( empty( $_GET['amount'] ) ){ sendResponse(-1, __LINE__, 'Amount is missing'); }
if( empty( $_GET['message'] ) ){ sendResponse(-1, __LINE__, 'Message is missing'); }

// validate phone
$sPhone = $_GET['phone'] ?? '';
if( strlen($sPhone) != 8 ){ sendResponse(-1, __LINE__, 'Phone must be 8 characters in length'); }
if( !ctype_digit($sPhone) ){ sendResponse(-1, __LINE__, 'Phone can only contain numbers');  }

// validate amount
$iAmount = $_GET['amount'] ?? '';
if( strlen($iAmount) <= 0 ){ sendResponse(-1, __LINE__, 'Amount must be at least 1'); }
if( !ctype_digit($iAmount)){ sendResponse(-1, __LINE__, 'Amount can only contain numbers');  }
$iAmount = 100;

// Validate the message!
$sMessage = $_GET['message'] ?? '';
if( strlen($sMessage) > 50 ){ sendResponse(-1, __LINE__, 'Message cannot be more than 50 characters'); }

// get data and validate
$sData = file_get_contents('../data/clients.json');
$jData = json_decode( $sData );

if( $jData == null){ sendResponse(-1, __LINE__, 'Cannot convert data to JSON');  }

$jInnerData = $jData->data;

if ( $iAmount > $jInnerData->$sUserId->balance ) { sendResponse(0, __LINE__, 'Sorry, you do not have enough money in your account.'); }

// if phone number does not exist in own bank
if( !$jInnerData->$sPhone ){ 
  $jListOfBanks = fnjGetListOfBanksFromCentralBank();
  // loop through the list
  // connect to each bank
  foreach( $jListOfBanks as $sKey => $jBank ){
    // echo $jBank->url;
    // echo $jBank->key;
    $sUrl = $jBank->url.'/apis/api-handle-transaction.php?phone='.$sPhone.'&amount='.$iAmount.'&message='.$sMessage;
    // echo $sUrl.'<br>';
    $sBankResponse = file_get_contents($sUrl);
    $jBankResponse = json_decode($sBankResponse);
    
    if( $jBankResponse->status == 1 && 
        $jBankResponse->code && 
        $jBankResponse->message ) { 
        // Take money from the logged user
        $jInnerData->$sUserId->balance -= $iAmount;
        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        file_put_contents('../data/clients.json', $sData);
        sendResponse( 1, __LINE__ , $jBankResponse->message );
     }
     
    }
    sendResponse( 2, __LINE__ , 'Phone does not exist' );
}


// Continue transfering the money **********************

// Take money from the logged user
$jInnerData->$sUserId->balance -= $iAmount;
//echo json_encode($iAmount);

// Give it to the corresponding phone  
$jInnerData->$sPhone->balance += $iAmount;
//save to file
$sData = json_encode($jData, JSON_PRETTY_PRINT);
file_put_contents('../data/clients.json', $sData);

sendResponse( 1, __LINE__ , 'Phone registered locally and transaction was successfull' );

// getListOfBanksFromCentralBank();

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
  echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
  exit;
}

// **************************************************
function fnjGetListOfBanksFromCentralBank(){
  // get the list of banks
  $sData = file_get_contents('https://ecuaguia.com/central-bank/api-get-list-of-banks.php?key=1111-2222-3333');
  return json_decode($sData);
}