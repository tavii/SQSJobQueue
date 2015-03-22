<?php
namespace Tavii\SQSJobQueue\Worker;


use Tavii\SQSJobQueue\Exception\RuntimeException;

interface WorkerInterface
{
    /**
     * ワーカーを実行する
     * @param string $name キュー名
     * @return boolean
     */
    public function run($name);

    /**
     * ワーカーを常駐させる
     * @param string $name キュー名
     * @return void
     * @throws RuntimeException
     */
    public function start($name);

    /**
     * 常駐しているワーカーを停止させる
     * @param string $name
     * @return void
     */
    public function stop($name, $pid = null);
}