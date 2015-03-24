<?php
namespace Tavii\SQSJobQueue\Message;

use Tavii\SQSJobQueue\Job\JobInterface;

interface MessageInterface
{
    /**
     * @return JobInterface
     */
    public function getJob();

    /**
     * @return array
     */
    public function getMessage();

    /**
     * @return string
     */
    public function getQueueUrl();

}