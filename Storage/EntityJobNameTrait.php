<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/14
 */

namespace Tavii\SQSJobQueue\Storage;


use Tavii\SQSJobQueue\Job\JobName;

trait EntityJobNameTrait
{
    public function getJobName()
    {
        return new JobName($this->getQueue(), $this->getPrefix());
    }
}