<?php
namespace Tavii\SQSJobQueue\Queue;

use Tavii\SQSJobQueue\Job\JobInterface;
use Tavii\SQSJobQueue\Message\MessageInterface;

interface QueueInterface
{

    public function pull($name);

    public function push(JobInterface $job);

    public function delete(MessageInterface $message);

}