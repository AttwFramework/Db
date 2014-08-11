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
use Attw\DB\Exception\StatementException;
use Attw\DB\Statement\StatementFetch as Fetch;
use Attw\DB\Statement\ParamTypes as Param;
use \mysqli;
use \PDO;
use \ReflectionClass;

class MySQLiStatement implements StatementInterface
{
    /**
     * MySQLi instance
     *
     * @var \mysqli
    */
    private $mysqli;

    /**
     * Prepared statement
     *
     * @var \mysqli_stmt
    */
    private $stmt;

    /**
     * @var array
    */
    private $bindParam = array();

    /**
     * @var array
    */
    private $types = array();

    /**
     * @var array
    */
    private $acceptedParamTypes = array(
        Param::PARAM_STR => 's',
        Param::PARAM_LOB => 's',
        Param::PARAM_NULL => 's',
        Param::PARAM_INT => 'i',
        Param::PARAM_BOOL => 'i',
    );

    /**
     * @var array
    */
    private $acceptedFetchTypes = array(
        Fetch::FETCH_LAZY,
        Fetch::FETCH_ASSOC,
        Fetch::FETCH_NAMED,
        Fetch::FETCH_NUM,
        Fetch::FETCH_BOTH,
        Fetch::FETCH_OBJ,
        Fetch::FETCH_BOUND,
        Fetch::FETCH_CLASS,
        Fetch::FETCH_INTO
    );

    /**
     * @var array
    */
    private $standartFetchMode;

    /**
     * \mysqli_result instance
     *
     * @var \mysql_result
    */
    private $result;

    /**
     * @param \mysqli $mysqli
     * @param string  $sql
    */
    public function __construct(mysqli $mysqli, $sql)
    {
        $this->mysqli = $mysqli;
        $this->stmt = $this->mysqli->prepare($sql);
    }

    /**
     * Binds a parameter to the specified variable name
     *
     * @param string       $var
     * @param mixed        $value
     * @param integer|null $type
     * @param integer|null $length Not support
    */
    public function bindParam($var, $value, $type = null, $length = null)
    {
        $this->bindParamAndValue($var, $value, $type);

        return $this;
    }

    /**
     * Binds a value to a parameter
     *
     * @param string       $var
     * @param mixed        $value
     * @param integer|null $type
    */
    public function bindValue($var, $value, $type = null)
    {
        $this->bindParamAndValue($var, $value, $type);

        return $this;
    }

    /**
     * @param string       $var
     * @param mixed        $value
     * @param integer|null $type
    */
    private function bindParamAndValue($var, $value, $type = null)
    {
        if (is_null($type)) {
            $type = 's';
        } else {
            if (!array_key_exists($type, $this->acceptedParamTypes)){
                StatementException::unknownParamType($type);
            }

            $type = $this->acceptedParamTypes[$type];
        }

        $this->bindParam[$var] = $value;
        $this->types[] = $type;
    }

    /**
     * Execute the prepared statement
     *
     * @param array    $parameters
     * @return boolean
    */
    public function execute(array $parameters = array())
    {
        if (count($this->bindParam) > 0 || count($parameters) > 0) {
            $this->bindParamOfMySQLi($parameters);
        }

        $this->verifyMySQLiErrorsAndThrowException();

        if (!$this->stmt->execute()) {
            StatementException::mysqliStmtError($this->stmt->error, $this->stmt->errno);
        }

        $this->result = $this->stmt->get_result();
    }

    /**
     * @param array $params
    */
    private function bindParamOfMySQLi(array $params = array())
    {
        $typesStringOfCurrentParams = str_repeat('s', count($params));
        $typesString = implode('', $this->types) . $typesStringOfCurrentParams;

        if (!call_user_func_array(array($this->stmt, 'bind_param'), 
            $this->refValues(array_merge(array($typesString), $this->bindParam, $params)))
        ) {
            StatementException::mysqliError($this->stmt->error, $this->stmt->errno);
        }
    }

    /**
     * Fetches the next row from a result set
     *
     * @param integer|null $type
     * @param string       $class
     * @return mixed
    */
    public function fetch($type = null, $class = null, array $classConstructor = array())
    {
        $this->verifyMySQLiErrorsAndThrowException();

        switch ($type) {
            case null:
                if (is_null($this->standartFetchMode) || count($this->standartFetchMode) == 0 || !isset($this->standartFetchMode['type'])) {
                    return $this->result->fetch_array();
                }

                return (isset($this->standartFetchMode['param2'])) ? $this->fetch(
                    $this->standartFetchMode['type'], 
                    $this->standartFetchMode['param2'], 
                    $this->standartFetchMode['param3']
                ) : $this->fetch($this->standartFetchMode['type']);
            case Fetch::FETCH_BOTH:
                return $this->result->fetch_array();
            case Fetch::FETCH_NAMED:
            case Fetch::FETCH_ASSOC:
                return $this->result->fetch_assoc();
            case Fetch::FETCH_OBJ:
            case Fetch::FETCH_LAZY:
                return $this->result->fetch_object();
            case Fetch::FETCH_NUM:
                    return array_values($this->result->fetch_assoc());
            case Fetch::FETCH_CLASS:
                return $this->createObjectOfClassFromFetch($this->result->fetch_assoc(), $class, $classConstructor);
            default:
                StatementException::unknownFetchType($type);       
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
        $this->verifyMySQLiErrorsAndThrowException();
        $results = array();

        switch ($type) {
            case null:
                if (is_null($this->standartFetchMode) || count($this->standartFetchMode) == 0 || !isset($this->standartFetchMode['type'])) {
                    while ($r = $this->result->fetch_array()) {
                        $results[] = $r;
                    }
                } else {
                    $results = (isset($this->standartFetchMode['param2'])) ? $this->fetchAll(
                        $this->standartFetchMode['type'], 
                        $this->standartFetchMode['param2'], 
                        $this->standartFetchMode['param3']
                    ) : $this->fetchAll($this->standartFetchMode['type']);
                }
                break;
            case Fetch::FETCH_BOTH:
                while ($r = $this->result->fetch_array()) {
                    $results[] = $r;
                }
                break;
            case Fetch::FETCH_OBJ:
                while ($r = $this->result->fetch_object()) {
                    $results[] = $r;
                }
                break;
            case Fetch::FETCH_ASSOC:
            case Fetch::FETCH_NAMED:
                while ($r = $this->result->fetch_assoc()) {
                    $results[] = $r;
               }
                break;
            case Fetch::FETCH_NUM:
                while ($r = $this->result->fetch_assoc()) {
                    $results[] = array_values($r);
                }
                break;
            case Fetch::FETCH_CLASS:
                while ($r = $this->result->fetch_assoc()) {
                    $results[] = $this->createObjectOfClassFromFetch($r, $class, $classConstructor);
                }
                break;
            default:
                StatementException::unknownFetchType($type);
        } 

        return $results;
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
        if (!in_array($type, $this->acceptedFetchTypes)) {
            StatementException::unknownFetchType($type);
        }

        $this->standartFetchMode['type'] = $type;
        
        if (!is_null($param2)) {
            $this->standartFetchMode['param2'] = $param2;
            $this->standartFetchMode['param3'] = $param3;
        }
    }

    /**
     * Returns the number of rows affected by the last SQL statement
     *
     * @return integer
    */
    public function rowCount()
    {

        return $this->result->num_rows;
    }

    /**
     * Closes the cursor, enabling the statement to be executed again.
     *
     * @return boolean
    */
    public function closeCursor()
    {
        return $this->stmt->close();
    }

    /**
     * @param array  $data
     * @param string $class
     * @param array  $classConstructor
     * @return object
    */
    private function createObjectOfClassFromFetch(array $data, $class, array $classConstructor = array())
    {
        $reflection = new ReflectionClass($class);
        $instance = $reflection->newInstanceArgs($classConstructor);

        foreach ($data as $column => $value) {
            $instance->$column = $value;
        }

        return $instance;
    }

    /**
     * @param array $arr
     * @return array
    */
    private function refValues(array $arr)
    {
        $refs = array();

        foreach($arr as $key => $value) {
            $refs[$key] = &$arr[$key];
        }
        
        return $refs;
    }

    /**
     * @throws \StatementException Error with MySQLi
     * @throws \StatementException Error with MySQLi Statement
    */
    private function verifyMySQLiErrorsAndThrowException()
    {
        if (!is_null($this->mysqli->error)) {
            StatementException::mysqliError($this->mysqli->error, $this->mysqli->errno);
        }

        if (!is_null($this->stmt->error)) {
            StatementException::mysqliStmtError($this->stmt->error, $this->stmt->errno);
        }
    }
}