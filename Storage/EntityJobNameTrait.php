<?php
/**
 * Created by PhpStorm.
 * User: polidog
 * Date: 2016/03/14
 */

namespace Tavii\SQSJobQueue\Storage;


use Tavii\SQSJobQueue\Queue\QueueName;

trait EntityJobNameTrait
{
    public function getQueueName()
    {
        return new QueueName($this->getQueue(), $this->getPrefix());
    }
}