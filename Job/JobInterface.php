<?php
namespace Tavii\SQSJobQueue\Job;
use Tavii\SQSJobQueue\Queue\QueueName;

/**
 * Interface JobInterface
 * @package SQSJobQueueBundle\Job
 */
interface JobInterface
{
    /**
     * Jobを実行する
     *
     * @return booelan
     */
    public function execute();


    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getPrefix();

    /**
     * @return QueueName
     */
    public function getJobName();

    /**
     * @return string
     */
    public function getClassName();

    /**
     * @return array
     */
    public function getArgs();
}