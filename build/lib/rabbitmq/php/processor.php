<?php
/**
 * Created by PhpStorm.
 * User: olg
 * Date: 24.12.14
 * Time: 18:24
 */

$myFile = fopen("/var/www/rabbitmq/php/logs/processor_" . getmypid() . "_log.txt", "w");

$urlPublish = 'http://rabbitmq.api.local/api/v1/publish';
$urlReceive = 'http://rabbitmq.api.local/api/v1/receive';

fwrite($myFile, 'php processor pid: ' . getmypid()  . "\n\n");

while (1) {

    $ch = curl_init();

    curl_setopt($ch,CURLOPT_URL, $urlPublish);
    curl_setopt($ch,CURLOPT_POST, 1);
    curl_setopt($ch,CURLOPT_POSTFIELDS, 'message=message test ' . date('d-m-Y H:i:s')  . "\n");
    $result = curl_exec($ch);
    fwrite($myFile, 'Publish message: "message test ' . date('d-m-Y H:i:s')  . "\n");

    sleep(mt_rand(1, 10));

    curl_setopt($ch,CURLOPT_URL, $urlReceive);
    curl_setopt($ch,CURLOPT_POST, 0);
    $result = curl_exec($ch);
    curl_close ( $ch );
    fwrite($myFile, 'Retrieve message: "' . implode(' | ', $result) . '"' . "\n");
    sleep(mt_rand(1, 10));
}
fclose($myFile);