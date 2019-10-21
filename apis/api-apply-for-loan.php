<?php

ini_set('display_errors', 1);

session_start();
if( !isset($_SESSION['sUserId'] ) ){
  sendResponse(-1, __LINE__, 'You must login to use this api');
}
$sUserId = $_SESSION['sUserId'];

$sLoanAmount = $_POST['loanAmount'];
if ( empty($sLoanAmount)) { sendResponse(-1, __LINE__, 'You must type an amount to get a loan.'); }
if ( $sLoanAmount <= 0 ) { sendResponse(-1, __LINE__, 'Amount must be more than 0.'); }
if ( $sLoanAmount > 1000000 ) { sendResponse(-1, __LINE__, 'Amount must be less than this.'); }
if ( !ctype_digit($sLoanAmount) ) { sendResponse(-1, __LINE__, 'Amount must be a number.'); }

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ($jData == null) { sendResponse(-1, __LINE__, 'cannot read json'); }
$jInnerData = $jData->data;

// create structure in the data ******************

// if ( !empty($sLoanAmount)) {

$jLoans = $jInnerData->$sUserId->loans;

$sLoanId = rand(100, 999);
$jLoans->$sLoanId = new stdClass();
$jLoan = new stdClass();
$jLoan->amount = $sLoanAmount;
$jLoan->active = 0;
$jLoans->$sLoanId = $jLoan;
 echo json_encode($jLoans);

$sData = json_encode( $jData, JSON_PRETTY_PRINT );
file_put_contents('../data/clients.json', $sData);

sendResponse(1, __LINE__, 'Success');
// }

// **************************************************
function sendResponse($iStatus, $iLineNumber, $sMessage){
    echo '{"status":'.$iStatus.', "code":'.$iLineNumber.',"message":"'.$sMessage.'"}';
    exit;
  }
