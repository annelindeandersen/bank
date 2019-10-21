<?php

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  echo 'You must login to use this api';
}
$sUserId = $_SESSION['sUserId'];

$sOldPassword = $_POST['oldPassword'] ?? '';

$sNewPassword = $_POST['newPassword'] ?? '';
if( strlen($sNewPassword) < 4 ){ sendResponse(-2, __LINE__, 'Password must be between 4 and 50 characters'); }
if( strlen($sNewPassword) > 50 ){ sendResponse(-2, __LINE__, 'Password must be between 4 and 50 characters'); }

$sConfirmPassword = $_POST['confirmPassword'] ?? '';

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ){ echo 'JSON could not be read'; }
$jInnerData = $jData->data;

// check if password and phone match the user
if( !password_verify( $sOldPassword, $jInnerData->$sUserId->password )  ){ sendResponse(-1, __LINE__, 'Password is incorrect'); }

if ( $sNewPassword != $sConfirmPassword ) {
    sendResponse(0, __LINE__, 'New password does not match confirmed password.'); 
}

if ( $sOldPassword == $sNewPassword ) {
    sendResponse(1, __LINE__, 'New password cannot be the same as the old.'); 
}

if ( $sNewPassword == $sConfirmPassword ) {
    $jInnerData->$sUserId->password = password_hash( $sNewPassword, PASSWORD_DEFAULT );
    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents('../data/clients.json', $sData);
    sendResponse( 2, __LINE__ , 'Password has been changed' );
}

// ****************************************************

function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }