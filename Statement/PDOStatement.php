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
     * @param \PDO   $pdo
     * @param string $sql
    */
    public function __construct(PDO $pdo, $sql)
    {
        $this->pdo = $pdo;
        $this->stmt = $this->pdo->prepare($sql);
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

            return (count($parameters) > 1) ? $this->stmt->execute($parameters) : $this->stmt->execute();
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Fetches the next row from a result set
     *
     * @param integer|null $type
     * @return mixed
    */
    public function fetch($type = null, $class = null, array $classConstructor = array())
    {
        try {
            return is_null($class) ? $this->stmt->fetch($type) : $this->stmt->fetch($type, $class, $classConstructor);
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        } 
    }

    /**
     * Returns an array containing all of the result set rows
     *
     * @param integer|null $type
     * @return mixed
    */
    public function fetchAll($type = null, $class = null, array $classConstructor = array())
    {
        try {
            return is_null($class) ? $this->stmt->fetchAll($type) : $this->stmt->fetchAll($type, $class, $classConstructor);
        } catch (PDOException $e) {
            StatementException::pdoStmtError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Set the default fetch mode for this statement
     *
     * @param integer    $type
     * @param string|null $param2
     * @param array       $param3
    */
    public function setFetchMode($type, $param2 = null, array $param3 = array())
    {
        try {
            if (is_null($param2)) {
                return $this->stmt->setFetchMode($type);
            } elseif (count($param3) == 0) {
                return $this->stmt->setFetchMode($type, $param2);
            }
            return $this->stmt->setFetchMode($type, $param2, $param3);
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