<?php
namespace Tavii\SQSJobQueue\Storage;


class RedisStorage implements StorageInterface
{

    const KEY_BASE = "sqs_job_queue";

    private $redis;

    public function __construct(\Redis $redis)
    {
        $this->redis = $redis;
    }


    public function all()
    {
        $this->redis->get(self::KEY_BASE.'.*');
    }

    public function set($queue, $server, $procId, $status = self::SERVER_STATUS_RUN)
    {
        $key = self::KEY_BASE.".{$server}.{$queue}:{$procId}";
        return $this->redis->set($key, array(
            $server,
            $queue,
            $procId,
            $status
        ));
    }

    public function get($queue, $server = null, $procId = null)
    {
        $key = self::KEY_BASE.".{$server}.{$queue}:{$procId}";
        return $this->redis->get($key, array(
            $server,
            $queue,
            $procId,
            $status
        ));
    }

    public function remove($queue, $server = null, $procId = null)
    {
        $key = self::KEY_BASE.".{$server}.{$queue}:{$procId}";
        $this->redis->delete($key);
    }

    public function create(array $params = array())
    {
        return true;
    }

}