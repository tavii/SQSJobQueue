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
     * @param string $server
     * @param int $procId
     * @return array
     */
    public function find($queue, $server = null, $procId = null);

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @return void
     */
    public function set($queue, $server, $procId);

    /**
     * @param string $queue
     * @param string $server
     * @param int $procId
     * @return array
     */
    public function get($queue, $server, $procId);

    /**
     * @param string $queue
     * @param string $server
     * @param string $procId
     * @return mixed
     */
    public function remove($queue, $server, $procId);

}