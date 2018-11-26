<?php

use Hobocta\RMQ\RMQService;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

$rmqService = new RMQService('test9');

$rmqService->connect();

$messageString = json_encode(array(
    'title' => 'Random title ' . uniqid(),
    'timestamp' => time(),
));

$message = new AMQPMessage(
    $messageString,
    array('delivery_mode' => 2)
);

$rmqService->publish($message);

$rmqService->close();
