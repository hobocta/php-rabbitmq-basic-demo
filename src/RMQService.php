<?php

namespace Hobocta\RMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RMQService
{
    private $queueName;
    /**
     * @var AMQPStreamConnection
     */
    private $connection;
    /**
     * @var AMQPChannel
     */
    private $channel;
    private $exchange;

    /**
     * RMQService constructor.
     *
     * @param $queueName
     */
    public function __construct($queueName) {
        $this->queueName = $queueName;
    }

    public function connect()
    {
        // @todo вынести настройки в конфиг
        $this->connection = new AMQPStreamConnection(
            'localhost',
            5672,
            'guest',
            'guest'
        );

        $this->channel = $this->connection->channel();

        $this->exchange = sprintf('%s_exchange', $this->queueName);

        $this->channel->exchange_declare(
            $this->exchange,
            'fanout',
            false,
            true,
            false
        );

        $this->channel->queue_declare(
            $this->queueName,
            false,
            true,
            false,
            false
        );

        $this->channel->queue_bind($this->queueName, $this->exchange);
    }

    public function publish(AMQPMessage $message)
    {
        $this->channel->basic_publish($message, $this->exchange);
    }

    public function close()
    {
        $this->channel->close();
        $this->connection->close();
    }

    /**
     * @param $callback
     * @throws \ErrorException
     */
    public function consume($callback)
    {
        register_shutdown_function(array($this, 'close'));

        $this->channel->basic_qos(null, 1, null);

        $this->channel->basic_consume(
            $this->queueName,
            '',
            false,
            false,
            false,
            false,
            $callback
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
