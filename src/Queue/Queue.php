<?php
namespace Tavii\SQSJobQueue\Queue;

use Aws\Sqs\SqsClient;
use Tavii\SQSJobQueue\Job\JobInterface;
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
     * @param string $name queue name
     */
    public function __construct(SqsClient $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function push(JobInterface $job)
    {
        return $this->client->sendMessage([
            'QueueUrl' => $job->getName(),
            'MessageBody' => json_encode([
                'name' => $job->getName(),
                'args' => $job->getArgs()
            ])
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function pull($name)
    {
        $queueUrl = $this->client->getQueueUrl(array(
            'QueueName' => $name
        ));

        $result = $this->client->receiveMessage(array(
            'QueueUrl' => $queueUrl['QueueUrl']
        ));
        $messages = $result->getPath('Messages/*');

        if (!empty($messages)) {
            $args = json_decode($messages['Body'], true);
            $job = new $args['name']($args['args']);
            return new Message($messages, $job);
        }
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function delete(MessageInterface $message)
    {
        $messageArray = $message->getMessage();
        return $this->client->deleteMessage([
            'QueueUrl' => $messageArray['QueueUrl'],
            'ReceiptHandle' => $messageArray['ReceiptHandle']
        ]);
    }


}