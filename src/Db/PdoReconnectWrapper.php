<?php
namespace FreeElephants\Db;

/**
 * TODO extract to standalone package for re-usage.
 * @author samizdam <samizdam@inbox.ru>
 */
class PdoReconnectWrapper
{

    /**
     * @var string
     */
    private $dsn;
    /**
     * @var string
     */
    private $username;
    /**
     * @var string
     */
    private $passwd;
    /**
     * @var null
     */
    private $options;
    /**
     * @var \PDO
     */
    private $pdo;

    public function __construct(string $dsn, string $username = null, string $passwd = null, $options = null)
    {
        $this->dsn = $dsn;
        $this->username = $username;
        $this->passwd = $passwd;
        $this->options = $options ?: [\PDO::ATTR_PERSISTENT => true];
    }

    public function getConnection(): \PDO
    {
        // TODO check that connection is alive
        if (empty($this->pdo)) {
            $this->connect();
        }

        return $this->pdo;
    }

    private function connect()
    {
        $this->pdo = new PDONestedTransaction($this->dsn, $this->username, $this->passwd, $this->options);
    }
}