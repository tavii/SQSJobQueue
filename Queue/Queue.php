<?php
namespace Tavii\SQSJobQueue\Queue;

use Aws\Sqs\SqsClient;
use Tavii\SQSJobQueue\Job\JobInterface;
use Tavii\SQSJobQueue\Job\JobName;
use Tavii\SQSJobQueue\Message\Message;
use Tavii\SQSJobQueue\Message\MessageInterface;

class Queue implements QueueInterface
{
    /**
     * Aamazon SQS Client
     * @var SqsClient
     */
    private $client;

    /**
     * @param SqsClient $client
     */
    public function __construct(SqsClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function send(JobInterface $job)
    {
        $queueUrl = $this->client->getQueueUrl(array(
            'QueueName' => $job->getJobName()->getQueueName()
        ));

        return $this->client->sendMessage(array(
            'QueueUrl' => $queueUrl['QueueUrl'],
            'MessageBody' => json_encode(array(
                'className' => $job->getClassName(),
                'args' => $job->getArgs()
            ))
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function receive(JobName $jobName)
    {
        $queueUrl = $this->client->getQueueUrl(array(
            'QueueName' => $jobName->getQueueName()
        ));

        $result = $this->client->receiveMessage(array(
            'QueueUrl' => $queueUrl['QueueUrl']
        ));
        $messages = $result->getPath('Messages/*');

        if (!empty($messages)) {
            $args = json_decode($messages['Body'], true);
            $job = new $args['className']($args['args']);
            return new Message($messages, $job, $queueUrl['QueueUrl']);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(MessageInterface $message)
    {
        $messageArray = $message->getMessage();
        return $this->client->deleteMessage(array(
            'QueueUrl' => $message->getQueueUrl(),
            'ReceiptHandle' => $messageArray['ReceiptHandle']
        ));
    }


}