<?php

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

$sAccountName = $_POST['accountName'];
if ( empty($sAccountName) ){ sendResponse(-1, __LINE__, 'Empty'); }
if ( strlen($sAccountName) < 2 ) { sendResponse(-1, __LINE__, 'Name too short'); }


$sAccountType = $_POST['accountType'];
$sAccountCurrency = $_POST['currency'];

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

// create structure in the data ******************

$jAccounts = $jData->data->$sUserId->accounts;

$jAccounts->$sAccountName = new stdClass();
$jAccount = new stdClass();
$jAccount->balance = 0;
$jAccount->name = ucfirst($sAccountName);
$jAccount->type = ucfirst($sAccountType);
$jAccount->currency = strtoupper($sAccountCurrency);
$jAccounts->$sAccountName = $jAccount;

$sData = json_encode( $jData, JSON_PRETTY_PRINT );
file_put_contents('../data/clients.json', $sData);

sendResponse(1, __LINE__, 'Success');


// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }