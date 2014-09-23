<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Statement;

interface StatementInterface
{
    /**
     * Binds a parameter to the specified variable name
     *
     * @param string       $var
     * @param mixed       $value
     * @param integer|null $type
     * @param integer|null $length
    */
    public function bindParam($var, $value, $type = null, $length = null);

    /**
     * Binds a value to a parameter
     *
     * @param string       $var
     * @param mixed    $value
     * @param integer|null $type
    */
    public function bindValue($var, $value, $type = null);

    /**
     * Execute the prepared statement
     *
     * @param array    $parameters
     * @return boolean
    */
    public function execute(array $parameters = array());

    /**
     * Fetches the next row from a result set
     *
     * @param integer|null $type
     * @param string|null  $class
     * @param array        $classConstructor
     * @return mixed
    */
    public function fetch($type = null, $class = null, array $classConstructor = array());

    /**
     * Returns an array containing all of the result set rows
     *
     * @param integer|null $type
     * @param string|null  $class
     * @param array        $classConstructor
     * @return mixed
    */
    public function fetchAll($type = null, $class = null, array $classConstructor = array());

    /**
     * Set the default fetch mode for this statement
     *
     * @param integer    $type
     * @param string|null $param2
     * @param array       $param3
    */
    public function setFetchMode($type, $param2 = null, array $param3 = array());

    /**
     * Returns the number of rows affected by the last SQL statement
     *
     * @return integer
    */
    public function rowCount();

    /**
     * Closes the cursor, enabling the statement to be executed again.
     *
     * @return boolean
    */
    public function closeCursor();
}