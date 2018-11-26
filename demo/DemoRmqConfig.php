<?php

use Hobocta\RMQ\RMQConnectionConfig;
use Hobocta\RMQ\RMQQueueConfig;

class DemoRmqConfig
{
    public function getRmqConnectionConfig()
    {
        $connectionConfig = new RMQConnectionConfig();

        $connectionConfig
            ->setHost('localhost')
            ->setPort(5672)
            ->setUser('guest')
            ->setPassword('guest');

        return $connectionConfig;
    }

    public function getRmqQueueConfig()
    {
        $queueConfig = new RMQQueueConfig();

        $queueConfig
            ->setQueue('test8')
            ->setPassive(false)
            ->setDurable(true)
            ->setExclusive(false)
            ->setAutoDelete(false);

        return $queueConfig;
    }
}
