<?php
namespace Tavii\SQSJobQueue\Worker;

use Phake;

class WorkerTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function ワーカーを実行する()
    {
        $name = 'test';

        $queue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $storage = Phake::mock('Tavii\SQSJobQueue\Storage\DoctrineStorage');
        $message = Phake::mock('Tavii\SQSJobQueue\Message\Message');
        $job = Phake::mock('Tavii\SQSJobQueue\Job\Job');

        Phake::when($queue)->pull($name)
            ->thenReturn($message);

        Phake::when($message)->getJob()
            ->thenReturn($job);

        Phake::when($job)->run()
            ->thenReturn(true);

        $worker = new Worker($queue, $storage);
        $actual = $worker->run($name);

        $this->assertTrue($actual);
        Phake::verify($queue)->pull($name);
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
        $storage = Phake::mock('Tavii\SQSJobQueue\Storage\DoctrineStorage');

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
        $queue = Phake::mock('Tavii\SQSJobQueue\Queue\Queue');
        $storage = Phake::mock('Tavii\SQSJobQueue\Storage\DoctrineStorage');
        Phake::when($storage)->get($name, 'test.com', null)
            ->thenReturn(array(
                array(
                    'queue' => $name,
                    'server' => 'test.com',
                    'proc_id' => 1234
                )
            ));

        $worker = new Worker($queue, $storage);
        $worker->stop($name);

        Phake::verify($storage)->get($name, "test.com", null);
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