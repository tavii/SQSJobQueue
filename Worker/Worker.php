<?php
namespace Tavii\SQSJobQueue\Worker;

use Tavii\SQSJobQueue\Exception\RuntimeException;
use Tavii\SQSJobQueue\Job\JobName;
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
    public function run(JobName $jobName)
    {
        $message = $this->queue->receive($jobName);
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
    public function start(JobName $jobName, $sleep = 5, $prefix = null)
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
            $this->storage->set($jobName, $server, $pid);
        } else {
            while(true) {
                $this->run($jobName);
                sleep($sleep);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function stop(JobName $jobName, $pid = null, $prefix = null, $force = false)
    {
        if (function_exists('gethostname')) {
            $server = gethostname();
        } else {
            $server = php_uname('n');
        }
        $processes = $this->storage->find($jobName, $server, $pid);
        foreach ($processes as $process) {

            if (!$process instanceof EntityInterface) {
                throw new RuntimeException('no support data type.');
            }

            if (posix_kill($process->getProcId(), 3)) {
                $this->storage->remove($process->getJobName(), $process->getServer(), $process->getProcId());
            }
        }

        if ($force) {
            $this->storage->removeForce($jobName, $server);
        }

    }
}