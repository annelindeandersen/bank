<?php

session_start();

// check if session is set
if ( !isset($_SESSION['sUserId']) ) {
    header('Location: index.php#login');
}

$sSubject = $_POST['subject'];
if( strlen($sSubject) < 2 ){ sendResponse(0, __LINE__, 'Subject must be at least 2 characters in length'); }

$sMessage = $_POST['message'];
if( strlen($sMessage) < 2 ){ sendResponse(0, __LINE__, 'Message must be at least 2 characters in length'); }

//get data
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { echo 'JSON cannot be read'; }

$jInnerData = $jData->data;

// loop through data and find emails
foreach ( $jInnerData as $jClientId => $jClient ) {

    mail( $jClient->email, $sSubject, $sMessage );
    sendResponse(1, __LINE__, "mails sent to $jClient->email");
    
    // if ( $sPhone != $jClientId ) { sendResponse(-1, __LINE__, 'Wrong phone number'); }
    // if ( !in_array( $sPhone, $jInnerData ) ) { sendResponse(-1, __LINE__, 'Wrong phone number'); }
    
    // echo "mails sent to $jClient->email";
    // header('Location: ../admin.php');
    
}

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }