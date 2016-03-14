<?php
namespace Tavii\SQSJobQueue\Job;


class JobTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function ジョブを生成することができる()
    {
        $args = array('1' => 'a', 'b' => '3');
        $job = new TestJob($args);
        $this->assertEquals("Tavii\\SQSJobQueue\\Job\\TestJob",$job->getClassName());
        $this->assertEquals("test_job", $job->getName());
        $this->assertInternalType('array', $job->getArgs());
        $this->assertEquals($args, $job->getArgs());
    }
}

class TestJob extends Job
{

    public function getName()
    {
        return 'test_job';
    }

    public function getPrefix()
    {
        return 'job_test';
    }


    public function run()
    {
        return true;
    }
}