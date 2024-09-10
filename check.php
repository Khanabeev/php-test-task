<?php

require __DIR__ . '/vendor/autoload.php';

$operation = new \App\Operations\GoodsReturnOperation();

$_REQUEST['data'] = [
    'notificationType' => 1,
    'complaintId' => 1,
    'complaintNumber' => '00001',
    'resellerId' => 1,
    'clientId' => 2,
    'creatorId' => 3,
    'expertId' => 4,
    'consumptionId' => 1,
    'consumptionNumber' => '00001',
    'agreementNumber' => '00001',
    'date' => date('Y-m-d'),
    'differences' => ['from' => 2, 'to' => 1],
];

$result = $operation->doOperation();
var_dump('1) --------------------');
var_dump($result);


$_REQUEST['data'] = [
    'notificationType' => 2,
    'complaintId' => 1,
    'complaintNumber' => '00001',
    'resellerId' => 1,
    'clientId' => 2,
    'creatorId' => 3,
    'expertId' => 4,
    'consumptionId' => 1,
    'consumptionNumber' => '00001',
    'agreementNumber' => '00001',
    'date' => date('Y-m-d'),
    'differences' => ['from' => 2, 'to' => 1],
];

$result = $operation->doOperation();
var_dump('2) --------------------');
var_dump($result);