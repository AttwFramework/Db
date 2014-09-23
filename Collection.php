<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db;

use Attw\Db\Connection\ConnectorInterface;
use \RuntimeException;
use \ArrayObject;

/**
 * A collection with database connections to the application
 *
 * @example
 *  To add a connection:
 *  Attw\Db\Collection::getInstance()->add('Conn1', new PDOConnector($connector_configs));
*/
class Collection
{
    /**
     * Instance for singleton
     *
     * @var \Attw\Db\Collection
    */
    private static $instance;

    private $count;

    /**
     * Collection with user database connections
     *
     * @var \ArrayObject
    */
    private $connections;

    private function __construct()
    {
        if (!($this->connections instanceof ArrayObject)) {
            $this->connections = new ArrayObject();
        }
    }

    private function __clone() {}

    public static function getInstance()
    {
        if (!(self::$instance instanceof Collection)) {
            self::$instance = new Collection();
        }

        return self::$instance;
    }

    /**
     * Attach a connector to colletion
     *
     * @param string $key identification to connection
     * @param \Attw\Db\Connection\ConnectorInterface $connector
     * @throws \RuntimeException case param $key already exists
    */
    public function add($key, ConnectorInterface $connector)
    {
        
        $this->connections->offsetSet($key, $connector);
    }

    /**
     * Remove a connection from colletion
     *
     * @param string $key identification to connection
     * @throws \RuntimeException case param $key do not exists
    */
    public function remove($key)
    {
        $this->throwAnExceptionIfConnectionDoesNotExists($key);
        $this->connections->offsetUnset($key);
    }

    /**
     * Get a connection from collection by identification key
     *
     * @param string $key identification to connection
     * @return \Attw\Db\Connection\ConnectorInterface
     * @throws \RuntimeException case param $key do not exists
    */
    public function get($key)
    {
        if (!$this->connections->offsetExists($key)) {
            throw new RuntimeException('This connection doesn\'t exists: ' . print_r($key, true));
        }

        return $this->connections->offsetGet($key);
    }

    /**
     * Verify a identification key exists in the collection
     *
     * @param string $key identification to connection
     * @return boolean 'true' case exists and 'false' case does not
    */
    public function exists($key)
    {
        return $this->connections->offsetExists($key);
    }

    private function throwAnExceptionIfConnectionDoesNotExists($key)
    {
        if (!$this->exists($key)) {
            throw new RuntimeException('This connection doesn\'t exists: ' . print_r($key, true));
        }
    }
}