<?php
namespace Tavii\SQSJobQueue\Job;

/**
 * Class Job
 * @package SQSJobQueue\Job
 */
abstract class Job implements JobInterface
{
    /**
     * @var array
     */
    protected $args;

    /**
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        $this->args = $args;
    }

    /**
     * @return bool
     */
    public function execute()
    {
        return $this->run();
    }

    /**
     * @return boolean
     */
    abstract protected function run();

    /**
     * @return string
     */
    public function getJobName()
    {
        return new JobName($this->getName(), $this->getPrefix());
    }

    /**
     * get job name
     * @return string
     */
    public function getClassName()
    {
        return get_class($this);
    }

    /**
     * get args
     * @return array
     */
    public function getArgs()
    {
        return $this->args;
    }
}