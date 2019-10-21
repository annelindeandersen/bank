<?php

session_start();

// check if session is set
if ( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php#login');
}

$sUserId = $_SESSION['sUserId'];

$sId = $_POST['phone'];
if( empty( $sId ) ){ sendResponse(-1, __LINE__, 'Phone missing'); }
if( strlen($sId) != 8 ){ sendResponse(-1, __LINE__, 'Phone must be 8 characters in length'); }
if( !ctype_digit($sId) ){ sendResponse(-1, __LINE__, 'Phone can only contain numbers');  }

$iAmount = $_POST['amount'];
if( empty( $iAmount ) ){ sendResponse(0, __LINE__, 'Amount is missing'); }
if( strlen($iAmount) <= 0 ){ sendResponse(0, __LINE__, 'Amount must be at least 1'); }
if( !ctype_digit($iAmount)){ sendResponse(0, __LINE__, 'Amount can only contain numbers');  }

//get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { echo 'JSON cannot be read'; }

$jInnerData = $jData->data;

// loop through data and find match
foreach ( $jInnerData as $jClientId => $jClient ) {
    if ( $jClientId == $sId ) {
        $jClient->balance += $iAmount;
        $sData = json_encode( $jData, JSON_PRETTY_PRINT );
        file_put_contents( '../data/clients.json', $sData );
        sendResponse(1, __LINE__, 'Success!');
    }
}

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }