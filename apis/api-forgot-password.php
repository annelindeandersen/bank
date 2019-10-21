<?php

$sData = file_get_contents('../data/clients.json');
$jData = json_decode( $sData );
if ( $jData == null ) { sendResponse(0, __LINE__); }

$sPhone = $_GET['txtForgotPhone'] ?? '';
if( empty($sPhone) ){ sendResponse(0, __LINE__);  }

$jInnerData = $jData->data;

if ( !$jInnerData->$sPhone ) {
    sendResponse(0, __LINE__);
}

// SUCCESS
$sEmail = $jInnerData->$sPhone->email;
$validationKey = $jInnerData->$sPhone->activationKey;

    mail( $sEmail, 'Password reset', "Click the link www.lindedesigns.dk/bank/apis/api-reset-password.php?validation-key=$validationKey&phone=$sPhone and use your new password: password12345" );
    sendResponse(1, __LINE__);

// *******************************************************
  
  function sendResponse( $bStatus, $iLineNumber ){
    echo '{"status":'.$bStatus.', "code":'.$iLineNumber.'}';
    exit;
  }

