<?php
namespace Tavii\SQSJobQueue\Job;

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
    public function getClassName();

    /**
     * @return array
     */
    public function getArgs();
}