<?php
namespace Tavii\SQSJobQueue\Worker;


use Tavii\SQSJobQueue\Exception\RuntimeException;
use Tavii\SQSJobQueue\Job\JobName;

interface WorkerInterface
{
    /**
     * ワーカーを実行する
     * @param JobName $jobName キュー名
     * @return boolean
     */
    public function run(JobName $jobName);

    /**
     * ワーカーを常駐させる
     * @param JobName $jobName キュー名
     * @return void
     * @throws RuntimeException
     */
    public function start(JobName $jobName, $sleep = 5);

    /**
     * 常駐しているワーカーを停止させる
     * @param JobName $jobName
     * @param int $pid
     * @param bool $force
     * @return void
     */
    public function stop(JobName $jobName, $pid = null, $force = false);
}