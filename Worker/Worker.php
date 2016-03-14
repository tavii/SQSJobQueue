<?php
namespace Tavii\SQSJobQueue\Worker;

use Tavii\SQSJobQueue\Exception\RuntimeException;
use Tavii\SQSJobQueue\Queue\QueueName;
use Tavii\SQSJobQueue\Queue\QueueInterface;
use Tavii\SQSJobQueue\Storage\EntityInterface;
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

    /**
     * Worker constructor.
     * @param QueueInterface $queue
     * @param StorageInterface $storage
     */
    public function __construct(QueueInterface $queue, StorageInterface $storage)
    {
        $this->queue = $queue;
        $this->storage = $storage;
    }

    /**
     * {@inheritdoc}
     */
    public function run(QueueName $queueName)
    {
        $message = $this->queue->receive($queueName);
        if (is_null($message)) {
            return false;
        }

        if ($message->getJob()->execute()) {
            $this->queue->delete($message);
            return true;
        }
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function start(QueueName $queueName, $sleep = 5, $prefix = null)
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
            $this->storage->set($queueName, $server, $pid);
        } else {
            while(true) {
                $this->run($queueName);
                sleep($sleep);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stop(QueueName $queueName, $pid = null, $prefix = null, $force = false)
    {
        if (function_exists('gethostname')) {
            $server = gethostname();
        } else {
            $server = php_uname('n');
        }
        $processes = $this->storage->find($queueName, $server, $pid);
        foreach ($processes as $process) {

            if (!$process instanceof EntityInterface) {
                throw new RuntimeException('no support data type.');
            }

            if (posix_kill($process->getProcId(), 3)) {
                $this->storage->remove($process->getJobName(), $process->getServer(), $process->getProcId());
            }
        }

        if ($force) {
            $this->storage->removeForce($queueName, $server);
        }

    }
}