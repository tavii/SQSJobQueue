<?php
namespace Tavii\SQSJobQueue\Storage;


use Tavii\SQSJobQueue\Job\JobName;

interface EntityInterface
{
    /**
     * @return string
     */
    public function getQueue();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return JobName
     */
    public function getJobName();

    /**
     * @return string
     */
    public function getServer();

    /**
     * @return int
     */
    public function getProcId();

}