<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\Statement;

use Attw\DB\Statement\StatementInterface;
use \PDO;
use \Exception;
use \PDOException;
use Attw\DB\Exception\StatementException;

class PDOStatement implements StatementInterface
{
    /**
     * PDO instance
     *
     * @var \PDO
    */
    private $pdo;

    /**
     * Prepared statement
     *
     * @var \Attw\DB\Connection\ConnectorInterface::prepare()
    */
    private $stmt;

    /**
     * Params to bindValue
     *
     * @var array
    */
    private $paramsToBindValue = array();

    /**
     * Params to bindParam
     *
     * @var array
    */
    private $paramsToBindParam = array();

    /**
     * @var boolean
    */
    private $execute;

    private $executeParams;

    /**
     * @param \PDO   $pdo
     * @param string $sql
    */
    public function __construct(PDO $pdo, $sql)
    {
        $this->pdo = $pdo;
        $this->stmt = $this->pdo->prepare(trim($sql));
    }

    /**
     * Binds a parameter to the specified variable name
     *
     * @param string       $var
     * @param mixed    $value
     * @param integer|null $type
     * @param integer|null $length
    */
    public function bindParam($var, $value, $type = null, $length = null)
    {
        $this->paramsToBindParam[] = array(
            'var' => $var,
            'value' => $value,
            'type' => $type,
            'length' => $length
       );

        return $this;
    }

    /**
     * Binds a value to a parameter
     *
     * @param string       $var
     * @param mixed    $value
     * @param integer|null $type
    */
    public function bindValue($var, $value, $type = null)
    {
        $this->paramsToBindValue[] = array('var' => $var, 'value' => $value, 'type' => $type);

        return $this;
    }

    /**
     * Execute the prepared statement
     *
     * @param array    $parameters
     * @return boolean
    */
    public function execute(array $parameters = array())
    {
        try {
            foreach ($this->paramsToBindValue as $param) {
                $this->stmt->bindValue($param['var'], $param['value'], $param['type']);
            }

            foreach ($this->paramsToBindParam as $param) {
                $this->stmt->bindParam($param['var'], $param['value'], $param['type'], $param['length']);
            }

            count($this->executeParams) > 0 ? $this->stmt->execute($this->executeParams) : $this->stmt->execute();
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Fetches the next row from a result set
     *
     * @param integer|null $type
     * @param string       $class
     * @param array        $classConstructor
     * @return mixed
    */
    public function fetch($type = null, $class = null, array $classConstructor = array())
    {
        return $this->fetchMethods('fetch', $type, $class, $classConstructor);
    }

    /**
     * Returns an array containing all of the result set rows
     *
     * @param integer|null $type
     * @param string       $class
     * @param array        $classConstructor
     * @return mixed
    */
    public function fetchAll($type = null, $class = null, array $classConstructor = array())
    {
        return $this->fetchMethods('fetchAll', $type, $class, $classConstructor);
    }

    private function fetchMethods($method, $type = null, $class = null, array $classConstructor = array())
    {
        try {
            return is_null($class) ? $this->stmt->{$method}($type) : $this->stmt->{$method}($type, $class, $classConstructor);
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Set the default fetch mode for this statement
     *
     * @param integer     $type
     * @param string|null $secondParam
     * @param array       $thirdParam
    */
    public function setFetchMode($type, $secondParam = null, array $thirdParam = array())
    {
        try {
            return $this->stmt->setFetchMode($type, $secondParam, $thirdParam);
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns the number of rows affected by the last SQL statement
     *
     * @return integer
    */
    public function rowCount()
    {
        try {
            return $this->stmt->rowCount();
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Closes the cursor, enabling the statement to be executed again.
     *
     * @return boolean
    */
    public function closeCursor()
    {
        try {
            return $this->stmt->closeCursor();
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }
}