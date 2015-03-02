<?php
namespace Tavii\SQSJobQueue\Storage;

use Doctrine\DBAL\Connection;

class DoctrineStorage implements StorageInterface
{
    /**
     * @var Connection
     */
    private $doctrine;

    public function __construct(Connection $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function all()
    {
        $sql = "SELECT * FROM sqs_workers";
        return $this->doctrine->fetchAll($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function set($queue, $server, $procId, $status = self::SERVER_STATUS_RUN)
    {
        $sql = "INSERT INTO sqs_workers (server, queue, proc_id, status, created_at, updated_at) VALUES (:server, :queue, :proc_id, :status, NOW(), NOW())";
        $stmt = $this->doctrine->prepare($sql);
        $stmt->bindValue("queue", $queue);
        $stmt->bindValue("server", $server);
        $stmt->bindValue("proc_id", $procId);
        $stmt->bindValue("status", $status);
        $stmt->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function get($queue, $server = null, $procId = null)
    {
        // TODO: Implement remove() method.
        $sql = "SELECT * FROM sqs_workers WHERE queue = :queue AND status = :status";

        $params = array();
        $params['queue'] = $queue;
        $params['status'] = self::SERVER_STATUS_RUN;

        if ($server) {
            $sql .= " AND server = :server";
            $params['server'] = $server;
        }
        if ($procId) {
            $sql .= " AND proc_id = :proc_id";
            $params['proc_id'] = $procId;
        }


        return $this->doctrine->fetchAll($sql, $params);
    }

    /**
     * {@inheritdoc}
     */
    public function remove($queue, $server = null, $procId = null)
    {
        // TODO: Implement remove() method.
        $sql = "DELETE FROM sqs_workers WHERE queue = :queue";
        if (!empty($server)) {
            $sql .= " AND server = :server";
        }
        if (!empty($procId)) {
            $sql .= " AND proc_id = :proc_id";
        }
        $stmt = $this->doctrine->prepare($sql);
        $stmt->bindValue('queue', $queue);
        if (!empty($server)) {
            $stmt->bindValue('server', $server);
        }

        if (!empty($procId)) {
            $stmt->bindValue('proc_id', $procId);
        }

        $stmt->execute();
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $config = array())
    {
        $sql = "CREATE TABLE `sqs_workers` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `server` varchar(255) NOT NULL,
  `proc_id` int(11) NOT NULL,
  `status` varchar(45) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `queue` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8";
        $stmt = $this->doctrine->prepare($sql);
        $stmt->execute();
    }

}