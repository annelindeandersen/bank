<?php
session_start();
if(!isset($_SESSION['sUserId'])){
    header('Location: index.php');
}
$sUserId = $_SESSION['sUserId'];
$sMessage = $_POST['txt-message'];

$sUserId = $sUserId == '29647715' ? '31111111' : '29647715';
file_put_contents( "../data/chat-$sUserId.txt", $sMessage );
echo $sMessage;