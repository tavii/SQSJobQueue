<?php
namespace Tavii\SQSJobQueue\Message;

interface MessageInterface
{
    public function getJob();

    public function getMessage();

}