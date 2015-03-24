<?php
namespace Tavii\SQSJobQueue\Storage;


interface EntityInterface
{
    /**
     * @return string
     */
    public function getQueue();

    /**
     * @return string
     */
    public function getServer();

    /**
     * @return int
     */
    public function getProcId();

}