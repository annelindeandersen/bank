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

    if ( $jClientId == $_GET['id'] ) {
       
        // flip the active key to 0 - maths: true false flips
        $jClient->active = ! $jClient->active;

        if ( $jClient->active == false ) {
            $jClient->active = 0;
        }

        // convert json back to text and 
        $sData = json_encode($jData, JSON_PRETTY_PRINT);
        
        // save data back to file
        file_put_contents('../data/clients.json', $sData);
        
        // redirect user to view-customers.php 
        header ('Location: ../admin.php');
        exit;
    }
}

echo 'no match';