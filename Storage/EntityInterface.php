<?php
namespace Tavii\SQSJobQueue\Storage;


use Tavii\SQSJobQueue\Queue\QueueName;

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
    public function getJobName();

    /**
     * @return string
     */
    public function getServer();

    /**
     * @return int
     */
    public function getProcId();

}