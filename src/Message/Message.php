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
     * @var string
     */
    private $queueUrl;

    /**
     * @param array $message
     * @param JobInterface $job
     * @param string $queueUrl
     */
    public function __construct(array $message, JobInterface $job, $queueUrl)
    {
        $this->message = $message;
        $this->job = $job;
        $this->queueUrl = $queueUrl;
    }

    /**
     * {@inheritdoc}
     */
    public function getJob()
    {
        return $this->job;
    }

    /**
     * {@inheritdoc}
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueueUrl()
    {
        return $this->queueUrl;
    }

}