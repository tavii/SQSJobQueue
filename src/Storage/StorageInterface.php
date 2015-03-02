<?php
namespace Tavii\SQSJobQueue\Storage;

interface StorageInterface
{
    const SERVER_STATUS_RUN = 10;
    const SERVER_STATUS_CLOSE = 20;
    const SERVER_STATUS_UNKNOWN = 40;

    public function all();

    public function set($queue, $server, $procId, $status = self::SERVER_STATUS_RUN);

    public function get($queue, $server = null, $procId = null);

    public function remove($queue, $server = null, $procId = null);


    public function create(array $params = array());
}