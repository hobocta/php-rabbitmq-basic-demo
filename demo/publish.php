<?php

use Hobocta\RMQ\RMQService;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

// @todo
require_once __DIR__ . '/DemoRmqConfig.php';
$demoRmqConfig = new DemoRmqConfig;

$rmqService = new RMQService($demoRmqConfig->getRmqConnectionConfig(), $demoRmqConfig->getRmqQueueConfig());

$rmqService->connect();

$messageString = json_encode(array(
    'title' => 'Random title ' . rand(),
    'anyData' => rand(),
));

$message = new AMQPMessage(
    $messageString,
    array('delivery_mode' => 2)
);

$rmqService->publish($message);

$rmqService->close();
