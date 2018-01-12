<?php
/**
 * Created by wyq
 * Date: 17-6-2
 * Time: 下午9:10
 */

require_once __DIR__ . '/../../vendor/autoload.php';

// edit aliyun-mns-config.ini first
$ini_array = parse_ini_file(__DIR__ . "/aliyun-mns-config.ini");
$accessId = $ini_array["accessId"];
$accessKey = $ini_array["accessKey"];
$endPoint = $ini_array["endPoint"];
$topicName = $ini_array["topicName"];
$signName = $ini_array["signName"];
$templateCode = $ini_array["templateCode"];
$phone = $ini_array["phone"];

$aliyunSms = new \Oakhope\AliyunSMS($accessId, $accessKey, $endPoint, $topicName, $signName);

var_dump($aliyunSms->sendOne($templateCode, $phone, ['code' => '123123', 'product' => 'appname']));