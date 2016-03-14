<?php
namespace Tavii\SQSJobQueue\Worker;


use Tavii\SQSJobQueue\Exception\RuntimeException;
use Tavii\SQSJobQueue\Queue\QueueName;

interface WorkerInterface
{
    /**
     * ワーカーを実行する
     * @param QueueName $queueName キュー名
     * @return boolean
     */
    public function run(QueueName $queueName);

    /**
     * ワーカーを常駐させる
     * @param QueueName $queueName キュー名
     * @return void
     * @throws RuntimeException
     */
    public function start(QueueName $queueName, $sleep = 5);

    /**
     * 常駐しているワーカーを停止させる
     * @param QueueName $queueName
     * @param int $pid
     * @param bool $force
     * @return void
     */
    public function stop(QueueName $queueName, $pid = null, $force = false);
}