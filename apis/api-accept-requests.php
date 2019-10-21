<?php

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

// ****************************************************

// if it is NOT set take me back to customers page
if( !isset( $_GET['id'] )) {
    header('Location: ../index.php#login');
}

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if ( $jData == null ) { echo 'json cannot be read'; }

$jInnerData = $jData->data;

foreach ( $jInnerData->$sUserId->requests as $jRequestId => $jRequest ) {

    if ( $_GET['id'] == $jRequestId ) {
        // flip the status to 1. True false flip
        $jRequest->status = ! $jRequest->status;

        // get phone number to transfer to
        $sPhone = $jRequest->phone;
        $iAmount = $jRequest->amount;

        if ( $jRequest->status == 1 || $jRequest->status == true ) {
            $jRequest->status = 1;
             // take money back from account and add to own
             $jInnerData->$sPhone->balance += $iAmount;
             $jInnerData->$sUserId->balance -= $iAmount;
             echo 'transfer is done';

             // save to data
             $sData = json_encode($jData, JSON_PRETTY_PRINT);
             file_put_contents('../data/clients.json', $sData);
             header('Location: ../profile.php');
             exit;
        }

        if ( $jRequest->status == 0 || $jRequest->status == false ) {
            $jRequest->status = 0;
            // take money back from account and add to own
            $jInnerData->$sPhone->balance -= $iAmount;
            $jInnerData->$sUserId->balance += $iAmount;
            echo 'amount has been taken back';

            // save to data
            $sData = json_encode($jData, JSON_PRETTY_PRINT);
            file_put_contents('../data/clients.json', $sData);
            header('Location: ../profile.php');
            exit;
        }

    }
}

echo 'no match';