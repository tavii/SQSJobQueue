<?php
namespace Tavii\SQSJobQueue\Message;

use Tavii\SQSJobQueue\Job\JobInterface;

class Message implements MessageInterface
{
    /**
     * @var array
     */
    private $message;

    /**
     * @var JobInterface
     */
    private $job;

    /**
     * @param array $message
     * @param JobInterface $job
     */
    public function __construct(array $message, JobInterface $job)
    {
        $this->message = $message;
        $this->job = $job;
    }

    public function getJob()
    {
        return $this->job;
    }

    public function getMessage()
    {
        return $this->message;
    }

}