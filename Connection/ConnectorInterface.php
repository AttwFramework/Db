<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Connection;

interface ConnectorInterface
{
    /**
     * Returns the instance of connection
     *
     * @return object
    */
    public function getConnection();

    /**
     * Returns the driver of database
     *
     * @return string
    */
    public function getDriver();

    /**
     * Executes an SQL statement
     *
     * @param string $sql
     * @return mixed
    */
    public function query($sql);

    /**
     * Prepares a statement for execution and returns a statement object
     *
     * @param string $sql
     * @return mixed
    */
    public function prepare($sql);

    /**
     * Execute an SQL statement and return the number of affected rows
     *
     * @param string   $sql
     * @return integer
    */
    public function exec($sql);

    /**
     * Returns the ID of the last inserted row or sequence value
     *
     * @param string|null $name
     * @return string
    */
    public function lastInsertId($name = null);

    /**
     * Returns statement class
     *
     * @param string  $sql
     * @return object
    */
    public function getStatement($sql);
}