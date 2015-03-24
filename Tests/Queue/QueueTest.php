<?php
namespace Tavii\SQSJobQueue\Queue;

use Phake;
use Aws\Sqs\SqsClient;
use Tavii\SQSJobQueue\Job\Job;

class QueueTest extends \PHPUnit_Framework_TestCase
{
    private $client;

    public function setUp()
    {
        $this->client = Phake::mock('Aws\Sqs\SqsClient');
    }

    /**
     * @test
     */
    public function キューに保存に保存することができる()
    {
        $name = 'test';
        $job = Phake::mock('Tavii\SQSJobQueue\Job\Job');
        Phake::when($job)->getName()
            ->thenReturn($name);
        Phake::when($job)->getArgs()
            ->thenReturn(array('test','teset2'));

        Phake::when($this->client)->getQueueUrl(array('QueueName' => $name))
            ->thenReturn(array(
                'QueueUrl' => 'test'
            ));


        $queue = new Queue($this->client);
        $queue->send($job);

        Phake::verify($this->client)->getQueueUrl(array('QueueName' => $name));
        Phake::verify($this->client)->sendMessage($this->isType('array'));
        Phake::verify($job)->getName();
        Phake::verify($job)->getArgs();

    }

    /**
     * @test
     */
    public function キューからジョブを取り出すことができる()
    {
        $name = "test_job";
        $args = array(
            'Body' => json_encode(array(
                'className' => 'Tavii\SQSJobQueue\Queue\DummyJob',
                'args' => array('a' => '1' , 'b' => 2)
            ))
        );

        $collection = Phake::mock('Guzzle\Common\Collection');
        Phake::when($collection)->getPath('Messages/*')
            ->thenReturn($args);

        Phake::when($this->client)->getQueueUrl(array('QueueName' => $name))
            ->thenReturn(array(
                'QueueUrl' => 'test'
            ));

        Phake::when($this->client)->receiveMessage(array(
            'QueueUrl' => 'test'
            ))->thenReturn($collection);

        $queue = new Queue($this->client);
        $job = $queue->receive($name);

        $this->assertInstanceOf('Tavii\SQSJobQueue\Message\MessageInterface', $job);
        Phake::verify($collection)->getPath('Messages/*');
        Phake::verify($this->client)->getQueueUrl(array('QueueName' => $name));
        Phake::verify($this->client)->receiveMessage(array('QueueUrl' => 'test'));
    }

    /**
     * @test
     */
    public function キューを削除する()
    {
        $message = Phake::mock('Tavii\SQSJobQueue\Message\Message');
        Phake::when($message)->getMessage()
            ->thenReturn(array(
                'QueueUrl' => '/hoge/fuga',
                'ReceiptHandle' => 'Receipt'
            ));

        Phake::when($this->client)->deleteMessage(array(
            'QueueUrl' => '/hoge/fuga',
            'ReceiptHandle' => 'Receipt',
        ));

        $queue = new Queue($this->client);
        $queue->delete($message);

    }
}

class DummyJob extends Job
{
    /**
     * @param array $args
     * @return booelan
     */
    public function run()
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'test_job';
    }

}