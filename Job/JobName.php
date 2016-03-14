<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/14
 */

namespace Tavii\SQSJobQueue\Job;


final class JobName
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $prefix;

    /**
     * JobName constructor.
     * @param string $name
     * @param string $prefix
     * @param string $separator
     */
    public function __construct($name, $prefix = "")
    {
        $this->name = $name;
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getQueueName()
    {
        if (empty($this->prefix)) {
            return $this->name;
        }
        return $this->prefix . "_" . $this->name;
    }
}