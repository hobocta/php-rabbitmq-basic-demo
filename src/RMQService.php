<?php

namespace Hobocta\RMQ;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

class RMQService
{
    private $connectionConfig;
    private $queueConfig;
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
     * @param RMQConnectionConfig $connectionConfig
     * @param RMQQueueConfig $queueConfig
     */
    public function __construct(
        RMQConnectionConfig $connectionConfig,
        RMQQueueConfig $queueConfig
    ) {
        $this->connectionConfig = $connectionConfig;
        $this->queueConfig = $queueConfig;
    }

    public function connect()
    {
        $this->connection = new AMQPStreamConnection(
            $this->connectionConfig->getHost(),
            $this->connectionConfig->getPort(),
            $this->connectionConfig->getUser(),
            $this->connectionConfig->getPassword()
        );

        $this->channel = $this->connection->channel();

        $this->exchange = sprintf('%s_exchange', $this->queueConfig->getQueue());

        $this->channel->exchange_declare(
            $this->exchange,
            'fanout',
            $this->queueConfig->isPassive(),
            $this->queueConfig->isDurable(),
            $this->queueConfig->isAutoDelete()
        );

        $this->channel->queue_declare(
            $this->queueConfig->getQueue(),
            $this->queueConfig->isPassive(),
            $this->queueConfig->isDurable(),
            $this->queueConfig->isExclusive(),
            $this->queueConfig->isAutoDelete()
        );

        $this->channel->queue_bind($this->queueConfig->getQueue(), $this->exchange);
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
     * @param $consumerTag
     * @param $callback
     * @throws \ErrorException
     */
    public function consume($consumerTag, $callback)
    {
        register_shutdown_function(array($this, 'close'));

        $this->channel->basic_qos(null, 1, null);

        $this->channel->basic_consume(
            $this->queueConfig->getQueue(),
            $consumerTag,
            false,
            false,
            $this->queueConfig->isExclusive(),
            false,
            $callback
        );

        while (count($this->channel->callbacks)) {
            $this->channel->wait();
        }
    }
}
