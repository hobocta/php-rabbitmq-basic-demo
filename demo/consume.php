<?php

use Hobocta\RMQ\RMQService;
use PhpAmqpLib\Message\AMQPMessage;

require_once __DIR__ . '/../vendor/autoload.php';

// @todo
require_once __DIR__ . '/DemoRmqConfig.php';
$demoRmqConfig = new DemoRmqConfig;

$rmqService = new RMQService($demoRmqConfig->getRmqConnectionConfig(), $demoRmqConfig->getRmqQueueConfig());

$rmqService->connect();

$callback = function (AMQPMessage $message) {
    // @todo
    echo sprintf(
        "Body: '%s'. Properties: '%s'" . PHP_EOL,
        $message->getBody(),
        json_encode($message->get_properties())
    );

    /** @var \PhpAmqpLib\Channel\AMQPChannel $channel */
    $channel = $message->delivery_info['channel'];
    $channel->basic_ack($message->delivery_info['delivery_tag']);
};

try {
    $rmqService->consume('consumer8', $callback);
} catch (ErrorException $e) {
    // @todo logging
    die(sprintf('Exception message: %s (%s:%s)', $e->getMessage(), $e->getFile(), $e->getLine()));
}
