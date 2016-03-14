<?php
namespace Tavii\SQSJobQueue\Storage;

use Tavii\SQSJobQueue\Queue\QueueName;

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
     * @param QueueName $queueName
     * @param string|null $server
     * @param string|null $procId
     * @return EntityInterface
     */
    public function find(QueueName $queueName, $server = null, $procId = null);

    /**
     * @param QueueName $queueName
     * @param string $server
     * @param int $procId
     * @param string $prefix
     * @return void
     */
    public function set(QueueName $queueName, $server, $procId);

    /**
     * @param QueueName $queueName
     * @param string $server
     * @param int $procId
     * @param string $prefix
     * @return array
     */
    public function get(QueueName $queueName, $server, $procId);

    /**
     * @param QueueName $queueName
     * @param string $server
     * @param string $procId
     * @param string $prefix
     * @return mixed
     */
    public function remove(QueueName $queueName, $server, $procId);


    /**
     * @param QueueName $queueName
     * @param $server
     * @return void
     */
    public function removeForce(QueueName $queueName, $server);


}