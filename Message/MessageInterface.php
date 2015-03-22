<?php
namespace Tavii\SQSJobQueue\Message;

use Tavii\SQSJobQueue\Job\JobInterface;

interface MessageInterface
{
    /**
     * get job class
     * @return JobInterface
     */
    public function getJob();

    /**
     * get message array
     * @return array
     */
    public function getMessage();

    /**
     * get queue url
     * @return string
     */
    public function getQueueUrl();

}