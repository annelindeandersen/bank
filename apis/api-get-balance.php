<?php

session_start();

$sUserId = $_SESSION['sUserId'];

$sData = file_get_contents('../data/clients.json');
$jData = json_decode($sData);
$jInnerData = $jData->data;

$iAmount = $_GET['amount'] ?? '';
$jInnerData->$sUserId->balance -= intval($iAmount);

echo $jInnerData->$sUserId->balance;
