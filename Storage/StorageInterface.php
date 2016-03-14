<?php
namespace Tavii\SQSJobQueue\Storage;

interface StorageInterface
{
    const SERVER_STATUS_RUN = 10;
    const SERVER_STATUS_CLOSE = 20;
    const SERVER_STATUS_UNKNOWN = 40;

    /**
     * @return array
     */
    public function all();

    /**
     * @param string $queue
     * @param string|null $server
     * @param string|null $procId
     * @param string|null $prefix
     * @return mixed
     */
    public function find($queue, $server = null, $procId = null, $prefix = null);

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @param string $prefix
     * @return void
     */
    public function set($queue, $server, $procId, $prefix);

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @param string $prefix
     * @return array
     */
    public function get($queue, $server, $procId, $prefix);

    /**
     * @param string $queue
     * @param string $server
     * @param string $procId
     * @param string $prefix
     * @return mixed
     */
    public function remove($queue, $server, $procId, $prefix);


    /**
     * @param $queue
     * @param $server
     * @return void
     */
    public function removeForce($queue, $server);


}