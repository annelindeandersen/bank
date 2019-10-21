<?php 

// Get the values of the inputs with post method
// Validate length and numbers/characters + password hash

$sName = $_POST['txtSignupName'] ?? '';
if( empty($sName) ){ sendResponse(0, __LINE__, "You must type a name");  }
if( strlen($sName) < 2 ){ sendResponse(0, __LINE__,  "Name must be at least 2 characters"); }
if( strlen($sName) > 20 ){ sendResponse(0, __LINE__, "Name must be no more than 20 characters"); }

$sLastName = $_POST['txtSignupLastName'] ?? '';
if( empty($sLastName) ){ sendResponse(0, __LINE__, "You must type a last name");  }
if( strlen($sLastName) < 2 ){ sendResponse(0, __LINE__,  "Last name must be at least 2 characters");  }
if( strlen($sLastName) > 20 ){ sendResponse(0, __LINE__, "Last name must be no more than 20 characters"); }

$sPhone = $_POST['txtSignupPhone'] ?? '';
if( empty($sPhone) ){ sendResponse(0, __LINE__, "You must type phone number");  }
if( strlen($sPhone) != 8 ){ sendResponse(0, __LINE__, "Phone number needs to be 8 numbers long"); }
if( intval($sPhone) < 10000000 ){ sendResponse(0, __LINE__, "Phone number must be at least 10000000"); }
if( intval($sPhone) > 99999999 ){ sendResponse(0, __LINE__, "Phone number must be no more than 99999999"); }

$sEmail = $_POST['txtSignupEmail'] ?? '';
if( empty($sEmail) ){ sendResponse(0, __LINE__);  }
if( !filter_var( $sEmail, FILTER_VALIDATE_EMAIL ) ){ sendResponse(0, __LINE__, "This is not a correct email");  }

$sPassword = $_POST['txtSignupPassword'] ?? '';
if( empty($sPassword) ){ sendResponse(0, __LINE__, "Password cannot be empty");  }
if( strlen($sPassword) < 4 ){ sendResponse(0, __LINE__, "Password must be at least 4 characters"); }
if( strlen($sPassword) > 50 ){ sendResponse(0, __LINE__, "Password cannot be more than 50 characters"); }

$sConfirmPassword = $_POST['txtSignupConfirmPassword'] ?? '';
if( empty($sConfirmPassword) ){ sendResponse(0, __LINE__, "Confirm password cannot be empty");  }
if( $sPassword != $sConfirmPassword ){ sendResponse(0, __LINE__, "Confirm password must match password");  }

// *******************************************************

// get json file and validate
$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
if ( $jData == null ) { sendResponse(0, __LINE__); }
$jInnerData = $jData->data;

// create variables for json objects
$jClient = new stdClass(); // or json_decode('{}')
$jClient->name = $sName;
$jClient->lastName = $sLastName;
$jClient->email = $sEmail;
$jClient->password = password_hash( $sPassword, PASSWORD_DEFAULT );
$jClient->cpr = $sCpr;
$jClient->balance = 0;
$jClient->active = 0;
$jClient->loginAttemptsLeft = 3;
$jClient->lastLoginAttempt = time();
$validationKey = $jClient->activationKey = uniqid();

$jClient->accounts = new stdClass();
$jClient->requests = new stdClass();

$jClient->transactionsNotRead = new stdClass();
$jClient->transactions = new stdClass();
$jInnerData->$sPhone = $jClient;
$sData = json_encode( $jData, JSON_PRETTY_PRINT );
if( $sData == null   ){ sendResponse(0, __LINE__); }
file_put_contents( '../data/clients.json', $sData );

// *******************************************************

// SUCCESS

// to, subject and message
if( mail( $sEmail, 'Activation Key', "www.lindedesigns.dk/bank/apis/api-activation.php?validation-key=$validationKey&phone=$sPhone" ) ) {
  //header('Location: ../index.php#activation');
  sendResponse(1, __LINE__);
  echo "Activation email sent to $sEmail";
  exit;
} else { echo 'Cannot send activation email'; exit; }


function sendResponse( $bStatus, $iLineNumber, $sMessage ){
  echo '{"status":'.$bStatus.', "code":'.$iLineNumber.', "message":"'.$sMessage.'"}';
  exit;
}
