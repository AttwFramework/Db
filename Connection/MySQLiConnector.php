<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Connection;

use Attw\Db\Connection\ConnectorInterface;
use Attw\Db\Statement\MySQLiStatement;
use Attw\Db\Exception\ConnectionException;
use \mysqli;

class MySQLiConnector implements ConnectorInterface
{
    /**
     * MySQLi instance
     *
     * @var \mysqli
    */
    private $mysqli;

    /**
     * @param string|null  $host
     * @param string|null  $user
     * @param string|null  $pass
     * @param string|null  $db
     * @param integer|null $port
     * @param string|null  $socket
    */
    public function __construct($host = null, $user = null, $pass = null, $db = null, $port = null, $socket = null)
    {
        $this->mysqli = new mysqli($host, $user, $pass, $db, $port, $socket);

        if ($this->mysqli->connect_error) {
            ConnectionException::mysqliError($this->mysqli->connect_error, $this->mysqli->connect_errno);
        }
    }

    /**
     * Returns the instance of connection
     *
     * @return \mysqli
    */
    public function getConnection()
    {
        return $this->mysqli;
    }

    /**
     * Returns the driver of database
     *
     * @return string
    */
    public function getDriver()
    {
        return 'mysql';
    }

    /**
     * Executes an SQL statement
     *
     * @param string $sql
     * @return mixed
    */
    public function query($sql)
    {
        $this->verifyException();
        return $this->mysqli->query($sql);
    }

    /**
     * Prepares a statement for execution and returns a statement object
     *
     * @param string $sql
     * @return mixed
    */
    public function prepare($sql)
    {
        $this->verifyException();
        return $this->mysqli->prepare($sql);
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     *
     * @param string   $sql
     * @return integer
    */
    public function exec($sql)
    {
        $this->verifyException();
        return $this->query($sql);
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string|null $name
     * @return string
    */
    public function lastInsertId($name = null)
    {
        $this->verifyException();
        return $this->mysqli->insert_id;
    }

    /**
     * Returns statement class
     *
     * @param string  $sql
     * @return \Attw\Db\Statement\MySQLiStatement
    */
    public function getStatement($sql)
    {
        $this->verifyException();
        return new MySQLiStatement($this->mysqli, $sql);
    }

    private function verifyException()
    {
        if (!is_null($this->mysqli->error)) {
            ConnectionException::mysqliError($this->mysqli->error, $this->mysqli->errno);
        }
    }
}