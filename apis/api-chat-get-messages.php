<?php

session_start();

if(!isset($_SESSION['sUserId'])){
    header('Location: index.php');
}
$sUserId = $_SESSION['sUserId'];

$sMessages = file_get_contents("../data/chat-$sUserId.txt");
file_put_contents("../data/chat-$sUserId.txt", '');
echo $sMessages;
