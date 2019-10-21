<?php


// get data of clients and validate json
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { sendResponse(0, __LINE__); }

$jInnerData = $jData->data;

// get field inputs and validate
$sPhone = $_POST['txtLoginPhone'] ?? '';
if ( empty($sPhone) ) { sendResponse(-2, __LINE__, 'empty'); }
if( strlen($sPhone) != 8 ){ sendResponse(-2, __LINE__, 'has to be 8 characters'); }
if( intval($sPhone) < 10000000 ){ sendResponse(-2, __LINE__, 'at least 10000000'); }
if( intval($sPhone) > 99999999 ){ sendResponse(-2, __LINE__, 'no more than 99999999'); }

$sPassword = $_POST['txtLoginPassword'] ?? '';
if( empty($sPassword) ){ sendResponse(-2, __LINE__, 'empty'); }
if( strlen($sPassword) < 4 ){ sendResponse(-2, __LINE__, 'has to be at least 4'); }
if( strlen($sPassword) > 50 ){ sendResponse(-2, __LINE__, 'no more than 50'); }

// check if password and phone match the user
// if( !password_verify( $sPassword, $jInnerData->$sPhone->password )  ){ sendResponse(0, __LINE__); }

// login attempts **********************************

if ( $jInnerData->$sPhone->loginAttemptsLeft == 0 ) {
  $iSecondsSinceLastLoginAttempt = $jInnerData->$sPhone->lastLoginAttempt+30 - time();

  if( $iSecondsSinceLastLoginAttempt <= 0 ) {

    if ( !password_verify( $sPassword, $jInnerData->$sPhone->password )) { 

      if ( $jInnerData->$sPhone->loginAttemptsLeft <= 0 ){
        $jInnerData->$sPhone->loginAttemptsLeft = 3;
        $jInnerData->$sPhone->lastLoginAttempt = time();
        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        file_put_contents( '../data/clients.json', $sData );
      }
      sendResponse(-1, __LINE__,"password wrong, try again");
     } 

    $jInnerData->$sPhone->loginAttemptsLeft = 3;
    $jInnerData->$sPhone->lastLoginAttempt = 0;
    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents( '../data/clients.json', $sData );

    sendResponse(3, __LINE__,'SUCCESS, you are logged in');

  }
  sendResponse(-1, __LINE__,"Please wait {$iSecondsSinceLastLoginAttempt} seconds to login again ");
}

if ( !password_verify( $sPassword, $jInnerData->$sPhone->password ) ) {
  $jInnerData->$sPhone->loginAttemptsLeft --;
  $jInnerData->$sPhone->lastLoginAttempt = time();
  $sData = json_encode($jData, JSON_PRETTY_PRINT);
  file_put_contents( '../data/clients.json', $sData );

  sendResponse(1, __LINE__, "You have {$jInnerData->$sPhone->loginAttemptsLeft} attempts left");
}

// is the user active? *********************************


if ( $jInnerData->$sPhone->active == 0 ) { sendResponse(2, __LINE__, 'Not active'); }

// *******************************************************

// SUCCESS
session_start();
$_SESSION['sUserId'] = $sPhone;


sendResponse(3, __LINE__, "successss");
//header('Location: ../profile');

function sendResponse( $bStatus, $iLineNumber, $sMessage ){
  echo '{"status":'.$bStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
  exit;
}

