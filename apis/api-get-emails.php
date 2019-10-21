<?php

ini_set('display_errors', 1);

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ($jData == null) { sendResponse(-1, __LINE__, 'json cannot be read'); }

$checkbox = $_POST['checkbox'];
if ( $checkbox != 'yes' ) { sendResponse(-1, __LINE__, 'not able to sign up'); }
if ( $checkbox == 'yes' ) {
    mail( $jData->data->$sUserId->email, 'Successfull signup', 'You have now signed up for newsletters from Bank of Linde' );
    sendResponse(1, __LINE__, 'You signed up');
}

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }