<?php
namespace Tavii\SQSJobQueue\Worker;

use Phake;
use Tavii\SQSJobQueue\Job\JobName;
use Tavii\SQSJobQueue\Storage\EntityInterface;
use Tavii\SQSJobQueue\Storage\EntityJobNameTrait;
use Tavii\SQSJobQueue\Storage\StorageInterface;

class WorkerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function ワーカーを実行する()
    {
        $name = new JobName('test');

        $queue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $storage = Phake::mock('Tavii\SQSJobQueue\Worker\TestStorage');
        $message = Phake::mock('Tavii\SQSJobQueue\Message\Message');
        $job = Phake::mock('Tavii\SQSJobQueue\Job\Job');

        Phake::when($queue)->receive($name)
            ->thenReturn($message);

        Phake::when($message)->getJob()
            ->thenReturn($job);

        Phake::when($job)->execute()
            ->thenReturn(true);

        $worker = new Worker($queue, $storage);
        $actual = $worker->run($name);

        Phake::verify($queue)->receive ($name);
        Phake::verify($message)->getJob();
        Phake::verify($job)->execute();
        Phake::verify($queue)->delete($message);
    }

    /**
     * @test
     */
    public function ワーカーを実行させる()
    {
        $name = new JobName("test");
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


        $name = new JobName("test");
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
        Phake::verify($storage)->remove($this->isInstanceOf(JobName::class), "test.com", 1234);

    }

    /**
     */
    public function ワーカーを強制的に停止させる()
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
        $worker->stop($name, null, true);

        Phake::verify($storage)->find($name, "test.com", null);
        Phake::verify($storage)->remove($name, "test.com", 1234);
        Phake::verify($storage)->removeForce($name, "test.com");
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
     * {@inheritdoc}
     */
    public function all()
    {
        // TODO: Implement all() method.
    }

    /**
     * {@inheritdoc}
     */
    public function find(JobName $jobName, $server = null, $procId = null)
    {
        // TODO: Implement find() method.
    }

    /**
     * {@inheritdoc}
     */
    public function set(JobName $jobName, $server, $procId)
    {
        // TODO: Implement set() method.
    }

    /**
     * {@inheritdoc}
     */
    public function get(JobName $jobName, $server, $procId)
    {
        // TODO: Implement get() method.
    }

    /**
     * {@inheritdoc}
     */
    public function remove(JobName $jobName, $server, $procId)
    {
        // TODO: Implement remove() method.
    }

    /**
     * {@inheritdoc}
     */
    public function removeForce(JobName $jobName, $server) {}



}

class TestEntity implements EntityInterface
{
    use EntityJobNameTrait;

    private $queue;

    private $server;

    private $procId;

    private $prefix;

    /**
     * {@inheritdoc}
     */
    public function __construct($queue, $server, $procId)
    {
        $this->queue = $queue;
        $this->server = $server;
        $this->procId = $procId;
    }

    /**
     * {@inheritdoc}
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * {@inheritdoc}
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * {@inheritdoc}
     */
    public function getProcId()
    {
        return $this->procId;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefix()
    {
        return $this->prefix;
    }


}