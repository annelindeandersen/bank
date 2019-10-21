<?php

session_start();

//TODO: Check if the user is logged -DONE
if ( !isset($_SESSION['sUserId']) ) {
    header('Location: ../login');
}

$sUserId = $_SESSION['sUserId'];
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

//TODO: Check if json is valid - DONE
if ( $jData == null ) { echo 'JSON not valid'; }

$jInnerData = $jData->data;

$jTransactionsNotRead = $jInnerData->$sUserId->transactionsNotRead;

echo json_encode($jTransactionsNotRead);

// TODO: Delete what is inside the transactionsNotRead
if ( !empty($jTransactionsNotRead) ) {
    // echo 'set to empty';
    $jTransactionsNotRead = json_decode('{}');
    $sData = json_encode($jData, JSON_PRETTY_PRINT);
    file_put_contents('../data/clients.json', $sData);
}