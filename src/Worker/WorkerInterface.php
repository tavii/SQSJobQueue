<?php
namespace Tavii\SQSJobQueue\Worker;


interface WorkerInterface
{
    /**
     * ワーカーを実行する
     * @param string $name キュー名
     * @return mixed
     */
    public function run($name);

    /**
     * ワーカーを常駐させる
     * @param string $name キュー名
     * @return mixed
     */
    public function start($name);

    /**
     * 常駐しているワーカーを停止させる
     * @param string $name
     * @return mixed
     */
    public function stop($name, $pid = null);
}