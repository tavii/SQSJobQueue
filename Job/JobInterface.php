<?php
namespace Tavii\SQSJobQueue\Job;

/**
 * Interface JobInterface
 * @package SQSJobQueueBundle\Job
 */
interface JobInterface
{
    /**
     * @return booelan
     */
    public function run();

    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return array
     */
    public function getArgs();
}