<?php
namespace Tavii\SQSJobQueue\Queue;

use Tavii\SQSJobQueue\Job\JobInterface;
use Tavii\SQSJobQueue\Queue\QueueName;
use Tavii\SQSJobQueue\Message\Message;
use Tavii\SQSJobQueue\Message\MessageInterface;

/**
 * Interface QueueInterface
 * @package Tavii\SQSJobQueue\Queue
 */
interface QueueInterface
{
    /**
     * キューからジョブを取り出す
     *
     * @param QueueName $name
     * @return Message
     */
    public function receive(QueueName $name);

    /**
     * Jobをキューに登録する
     *
     * @param JobInterface $job
     * @return mixed
     */
    public function send(JobInterface $job);

    /**
     * キューを削除する
     * @param MessageInterface $message
     * @return mixed
     */
    public function delete(MessageInterface $message);

}