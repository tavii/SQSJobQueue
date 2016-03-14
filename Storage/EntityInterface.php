<?php
namespace Tavii\SQSJobQueue\Storage;


use Tavii\SQSJobQueue\Queue\QueueName;

/**
 * Interface EntityInterface
 * @package Tavii\SQSJobQueue\Storage
 */
interface EntityInterface
{
    /**
     * @return string
     */
    public function getQueue();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return QueueName
     */
    public function getQueueName();

    /**
     * @return string
     */
    public function getServer();

    /**
     * @return int
     */
    public function getProcId();

}