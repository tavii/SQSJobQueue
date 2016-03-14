<?php
namespace Message;


use Tavii\SQSJobQueue\Message\Message;
use Tavii\SQSJobQueue\Job\booelan;
use Tavii\SQSJobQueue\Job\Job;

class MessageTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function メッセージオブジェクトを生成することができる()
    {
        $job = new DummyJob(array('test' => '1'));
        $args = array(
            1 => 'test'
        );
        $queueUrl = '/path/to/url';
        $message = new Message($args, $job, $queueUrl);
        $this->assertEquals($job, $message->getJob());
        $this->assertInternalType('array', $message->getMessage());
        $this->assertEquals($args, $message->getMessage());
        $this->assertEquals($queueUrl, $message->getQueueUrl());
    }
}

class DummyJob extends Job
{
    /**
     * @param array $args
     * @return booelan
     */
    protected function run()
    {
        return true;
    }

    public function getPrefix()
    {
        return 'message_test';
    }


    /**
     * @return string
     */
    public function getName()
    {
        return 'dummy_job';
    }

}