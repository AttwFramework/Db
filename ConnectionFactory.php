<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db;

use Attw\Db\Exception\ConnectionException;

class ConnectionFactory
{
    private $drivers = array(
        'pdo' => '\Attw\Db\Connection\PDOConnector',
        'mysqli' => '\Attw\Db\Connection\MySQLiConnector'
    );

    public function createConnection($driver, array $args = array())
    {
        if (!in_array($driver, array_keys($this->drivers))) {
            throw new ConnectionException::driverNotFound($driver);
        }

        $connection = new ReflectionClass($this->drivers[$driver]);
        return $connection->newInstanceArgs($args);
    }
}