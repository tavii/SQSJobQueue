<?php
namespace Tavii\SQSJobQueue\Worker;

use Tavii\SQSJobQueue\Exception\RuntimeException;
use Tavii\SQSJobQueue\Queue\Queue;
use Tavii\SQSJobQueue\Queue\QueueInterface;
use Tavii\SQSJobQueue\Storage\StorageInterface;

class Worker implements WorkerInterface
{
    /**
     * @var QueueInterface
     */
    private $queue;

    /**
     * @var StorageInterface
     */
    private $storage;

    public function __construct(QueueInterface $queue, StorageInterface $storage)
    {
        $this->queue = $queue;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function run($name)
    {
        $message = $this->queue->receive($name);
        if (is_null($message)) {
            return false;
        }

        if ($message->getJob()->run()) {
            $this->queue->delete($message);
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function start($name, $sleep = 5)
    {
        $pid = pcntl_fork();
        if ($pid === -1) {
            throw new RuntimeException('Could not fork the process');
        } elseif ($pid > 0) {
            if (function_exists('gethostname')) {
                $server = gethostname();
            } else {
                $server = php_uname('n');
            }
            $this->storage->set($name, $server, $pid);
        } else {
            while(true) {
                $this->run($name);
                sleep($sleep);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stop($name, $pid = null)
    {
        if (function_exists('gethostname')) {
            $server = gethostname();
        } else {
            $server = php_uname('n');
        }
        $processes = $this->storage->get($name, $server, $pid);
        foreach ($processes as $process) {
            if (posix_kill($process['proc_id'], 3)) {
                $this->storage->remove($process['queue'], $process['server'], (int)$process['proc_id']);
            }
        }
    }
}