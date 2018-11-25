<?php

namespace Hobocta\RMQ;

class RMQQueueConfig
{
    private $queue;
    private $passive = false;
    private $durable = false;
    private $exclusive = false;
    private $auto_delete = true;

    public function setQueue($queue)
    {
        $this->queue = $queue;
        return $this;
    }

    public function getQueue()
    {
        return $this->queue;
    }

    public function setPassive($passive)
    {
        $this->passive = $passive;
        return $this;
    }

    public function isPassive()
    {
        return $this->passive;
    }

    public function setDurable($durable)
    {
        $this->durable = $durable;
        return $this;
    }

    public function isDurable()
    {
        return $this->durable;
    }

    public function setExclusive($exclusive)
    {
        $this->exclusive = $exclusive;
        return $this;
    }

    public function isExclusive()
    {
        return $this->exclusive;
    }

    public function setAutoDelete($auto_delete)
    {
        $this->auto_delete = $auto_delete;
        return $this;
    }

    public function isAutoDelete()
    {
        return $this->auto_delete;
    }
}
