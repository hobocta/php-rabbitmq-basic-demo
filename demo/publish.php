<?php

use Hobocta\RMQ\RMQService;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

// @todo
require_once __DIR__ . '/DemoRmqConfig.php';
$demoRmqConfig = new DemoRmqConfig;

$rmqService = new RMQService($demoRmqConfig->getRmqConnectionConfig(), $demoRmqConfig->getRmqQueueConfig());

$rmqService->connect();

$message = new AMQPMessage('Body ' . rand(), array('delivery_mode' => 2));

$rmqService->publish($message);

$rmqService->close();
