<?php
namespace Tavii\SQSJobQueue\Storage;

use Phake;
use Tavii\SQSJobQueue\Storage\StorageInterface;

class DoctrineStorageTest extends \PHPUnit_Framework_TestCase
{
    private $doctrine;

    public function setUp()
    {
        $this->doctrine = Phake::mock('Doctrine\DBAL\Connection');
    }

    /**
     * @test
     */
    public function 全てのワーカーの情報を取得する()
    {
        $sql = "SELECT * FROM sqs_workers";

        $storage = new DoctrineStorage($this->doctrine);
        $storage->all();

        Phake::verify($this->doctrine)
            ->fetchAll($sql);
    }

    /**
     * @test
     */
    public function ワーカーの情報をセットすることができる()
    {
        $sql = "INSERT INTO sqs_workers (server, queue, proc_id, status, created_at, updated_at) VALUES (:server, :queue, :proc_id, :status, NOW(), NOW())";
        $queue = "test_queue";
        $server = "test.com";
        $procId = 12345;

        $stmt = Phake::mock('Doctrine\DBAL\Driver\Statement');

        Phake::when($this->doctrine)->prepare($sql)
            ->thenReturn($stmt);

        $storage = new DoctrineStorage($this->doctrine);
        $storage->set($queue, $server, $procId);

        Phake::verify($this->doctrine)->prepare($sql);
        Phake::verify($stmt)->bindValue('queue', $queue);
        Phake::verify($stmt)->bindValue('server', $server);
        Phake::verify($stmt)->bindValue('proc_id', $procId);
        Phake::verify($stmt)->bindValue('status', StorageInterface::SERVER_STATUS_RUN);
        Phake::verify($stmt)->execute();
    }

    /**
     * @test
     */
    public function ワーカーの情報を取得することができる()
    {
        $sql = "SELECT * FROM sqs_workers WHERE queue = :queue AND status = :status AND worker = :worker AND proc_id = :proc_id";
        $queue = 'test_queue';
        $server = 'test.com';
        $procId = 12345;


        $storage = new DoctrineStorage($this->doctrine);
        $storage->get($queue, $server, $procId);

        Phake::verify($this->doctrine)->fetchAll($sql,array(
            'queue' => $queue,
            'status' => StorageInterface::SERVER_STATUS_RUN,
            'server' => $server,
            'proc_id' => $procId,
        ));

    }

    /**
     * @test
     */
    public function ワーカーの情報を削除することができる()
    {
        $sql = "DELETE FROM sqs_workers WHERE queue = :queue AND server = :server AND proc_id = :proc_id";
        $queue = 'test_queue';
        $server = 'test.com';
        $procId = 12345;

        $stmt = Phake::mock('Doctrine\DBAL\Driver\Statement');

        Phake::when($this->doctrine)->prepare($sql)
            ->thenReturn($stmt);


        $storage = new DoctrineStorage($this->doctrine);
        $storage->remove($queue, $server, $procId);

        Phake::verify($this->doctrine)->prepare($sql);
        Phake::verify($stmt)->bindValue('queue', $queue);
        Phake::verify($stmt)->bindValue('server', $server);
        Phake::verify($stmt)->bindValue('proc_id', $procId);

        Phake::verify($stmt)->execute();
    }

}