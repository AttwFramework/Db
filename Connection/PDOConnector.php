<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\Connection;

use Attw\DB\Connection\ConnectorInterface;
use Attw\DB\Statement\PDOStatement;
use \PDO;
use \PDOException;

class PDOConnector implements ConnectorInterface
{
    /**
     * PDO instance
     *
     * @var \PDO
    */
    private $pdo;

    /**
     * Creates a PDO instance representing a connection to a database
     *
     * @param string      $dsn
     * @param string|null $user
     * @param string|null $pass
     * @param array       $options
    */
    public function __construct($dsn, $user = null, $pass = null, array $options = array())
    {
        $this->pdo = new PDO($dsn, $user, $pass, $options);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Returns the instance of connection
     *
     * @return object
    */
    public function getConnection()
    {
        return $this->pdo;
    }

    /**
     * Returns the driver of database
     *
     * @return string
    */
    public function getDriver()
    {
        try {
            return $this->pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        } catch (PDOException $e) {
            ConnectionException::pdoError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Executes an SQL statement
     *
     * @param string $sql
     * @return mixed
    */
    public function query($sql)
    {
        try {
            return $this->pdo->query($sql);
        } catch (PDOException $e) {
            ConnectionException::pdoError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Prepares a statement for execution and returns a statement object
     *
     * @param string $sql
     * @return mixed
    */
    public function prepare($sql)
    {
        try {
            return $this->pdo->prepare($sql);
        } catch (PDOException $e) {
            ConnectionException::pdoError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     *
     * @param string   $sql
     * @return integer
    */
    public function exec($sql)
    {
        try {
            return $this->exec($sql);
        } catch (PDOException $e) {
            ConnectionException::pdoError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string|null $name
     * @return string
    */
    public function lastInsertId($name = null)
    {
        try {
            return $this->pdo->lastInsertId($name);
        } catch (PDOException $e) {
            ConnectionException::pdoError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns statement class
     *
     * @return object
    */
    public function getStatement($sql)
    {
        return new PDOStatement($this->getConnection(), $sql);
    }
}