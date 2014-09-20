<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Statement;

use Attw\DB\SQL\MySQL\Statement\AbstractStatementWithWhere;
use Attw\DB\SQL\MySQL\Clause\Set;
use Attw\DB\SQL\MySQL\Clause\Where;
use Attw\DB\SQL\MySQL\Operator\Equal;
use Attw\DB\SQL\MySQL\Operator\AndOperator;
use \InvalidArgumentException;

/**
 * MySQL SQL Update statement
*/
class Update extends AbstractStatementWithWhere
{
    /**
     * Table to update some registries
     *
     * @param string
    */
    private $table;
    private $set;

    /**
     * Constructor to update statement
     *
     * @param string $table
     * @param array $data data to update
     * @param string | array $where
    */
    public function __construct($table, array $data, $where)
    {
        if (!is_string($table)) {
        throw new InvalidArgumentException(get_class($this) . '::__construct(): the table must be a string');
        }

        $this->table = $table;
        $this->constructWhere($where);
        $this->constructSet($data);
    }

    /**
     * Construct the set clause
     *
     * @param array $data data to update
    */
    private function constructSet(array $data)
    {
        $equals = array();
        foreach ($data as $column => $value) {
        $equals[] = new Equal($column, $value);
        }

        $this->set = new Set(implode(', ', $equals));
    }

    /**
     * Construct the update SQL
    */
    public function constructSql()
    {
        $this->sql = sprintf('UPDATE %s %s %s',
                $this->table,
                $this->set,
                $this->where);
    }
}