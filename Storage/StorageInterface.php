<?php
namespace Tavii\SQSJobQueue\Storage;

use Tavii\SQSJobQueue\Job\JobName;

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
     * @param JobName $jobName
     * @param string|null $server
     * @param string|null $procId
     * @return EntityInterface
     */
    public function find(JobName $jobName, $server = null, $procId = null);

    /**
     * @param JobName $jobName
     * @param string $server
     * @param int $procId
     * @param string $prefix
     * @return void
     */
    public function set(JobName $jobName, $server, $procId);

    /**
     * @param JobName $jobName
     * @param string $server
     * @param int $procId
     * @param string $prefix
     * @return array
     */
    public function get(JobName $jobName, $server, $procId);

    /**
     * @param JobName $jobName
     * @param string $server
     * @param string $procId
     * @param string $prefix
     * @return mixed
     */
    public function remove(JobName $jobName, $server, $procId);


    /**
     * @param JobName $jobName
     * @param $server
     * @return void
     */
    public function removeForce(JobName $jobName, $server);


}