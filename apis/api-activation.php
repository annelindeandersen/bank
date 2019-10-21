<?php

//echo 'activation page';

// get the validation key and the phone
$validationKey = $_GET['validation-key'];
$sPhone = $_GET['phone'];

// get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { sendResponse(0, __LINE__); }
$jInnerData = $jData->data;

// check if they match the client 
if ($jInnerData->$sPhone != $sPhone &&
    $jInnerData->$sPhone->activationKey != $validationKey ) {
        sendResponse(0, __LINE__);
    } 

//change their active to 1 
$jInnerData->$sPhone->active = 1;

//upload to the data file
$sData = json_encode( $jData, JSON_PRETTY_PRINT );
file_put_contents( '../data/clients.json', $sData );
//success redirect to login page
header('Location: ../index.php#login');

// ****************************************************

function sendResponse( $bStatus, $iLineNumber ){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.'}';
    exit;
  }
  
