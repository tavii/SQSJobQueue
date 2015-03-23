<?php
namespace Tavii\SQSJobQueue\Worker;

use Phake;
use Tavii\SQSJobQueue\Storage\EntityInterface;
use Tavii\SQSJobQueue\Storage\StorageInterface;

class WorkerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function ワーカーを実行する()
    {
        $name = 'test';

        $queue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $storage = Phake::mock('Tavii\SQSJobQueue\Worker\TestStorage');
        $message = Phake::mock('Tavii\SQSJobQueue\Message\Message');
        $job = Phake::mock('Tavii\SQSJobQueue\Job\Job');

        Phake::when($queue)->receive($name)
            ->thenReturn($message);

        Phake::when($message)->getJob()
            ->thenReturn($job);

        Phake::when($job)->run()
            ->thenReturn(true);

        $worker = new Worker($queue, $storage);
        $actual = $worker->run($name);

        Phake::verify($queue)->receive ($name);
        Phake::verify($message)->getJob();
        Phake::verify($job)->run();
        Phake::verify($queue)->delete($message);
    }

    /**
     * @test
     */
    public function ワーカーを実行させる()
    {
        $name = "test";
        $queue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $storage = Phake::mock('Tavii\SQSJobQueue\Worker\TestStorage');

        $worker = new Worker($queue, $storage);
        $worker->start($name);

        Phake::verify($storage)->set($name, "test.com", 234);
    }

    /**
     * @test
     */
    public function ワーカーを停止させる()
    {


        $name = "test";
        $entity = new TestEntity($name, 'test.com', 1234);

        $queue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $storage = Phake::mock('Tavii\SQSJobQueue\Worker\TestStorage');
        Phake::when($storage)->find($name, 'test.com', null)
            ->thenReturn(array(
                $entity
            ));

        $worker = new Worker($queue, $storage);
        $worker->stop($name);

        Phake::verify($storage)->find($name, "test.com", null);
        Phake::verify($storage)->remove($name, "test.com", 1234);

    }
}


function pcntl_fork() {
    return 234;
}

function gethostname() {
    return 'test.com';
}

function posix_kill($pid, $num) {
    return true;
}

class TestStorage implements StorageInterface
{
    /**
     * @return array
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @return array
     */
    public function find($queue, $server = null, $procId = null)
    {
        // TODO: Implement find() method.
    }

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @return void
     */
    public function set($queue, $server, $procId)
    {
        // TODO: Implement set() method.
    }

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @return array
     */
    public function get($queue, $server, $procId)
    {
        // TODO: Implement get() method.
    }

    /**
     * @param string $queue
     * @param string $server
     * @param string $procId
     * @return mixed
     */
    public function remove($queue, $server, $procId)
    {
        // TODO: Implement remove() method.
    }


}

class TestEntity implements EntityInterface
{

    private $queue;

    private $server;

    private $procId;

    /**
     * @param $queue
     * @param $server
     * @param $procId
     */
    public function __construct($queue, $server, $procId)
    {
        $this->queue = $queue;
        $this->server = $server;
        $this->procId = $procId;
    }

    /**
     * @return string
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @return string
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * @return int
     */
    public function getProcId()
    {
        return $this->procId;
    }

}