<?php

// if it is NOT set take me back to customers page
if( !isset( $_GET['id'] )) {
    header('Location: ../admin.php');
}

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);

if ( $jData == null ) { echo 'json cannot be read'; }

// loop through customers and find id match
foreach( $jData->data as $jClientId => $jClient ) {

    foreach( $jClient->loans as $jLoanId => $jLoan ) {
        
        if ( $jLoanId == $_GET['id'] ) {

            // flip the active key to 0 - maths: true false flips
            $jLoan->active = ! $jLoan->active;

            if ( $jLoan->active == false ) {
                $jLoan->active = 0;
                $jClient->balance -= $jLoan->amount;
            }

            if ( $jLoan->active == true ) {
                $jLoan->active = 1;
                $jClient->balance += $jLoan->amount;
            }

            // convert json back to text and 
            $sData = json_encode($jData, JSON_PRETTY_PRINT);
            
            // save data back to file
            file_put_contents('../data/clients.json', $sData);
            
            // redirect user to view-customers.php 
            header ('Location: ../admin.php#loans');
            exit;
        }
    }
}

echo 'no match';