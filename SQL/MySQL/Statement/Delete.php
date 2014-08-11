<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Statement;

use Attw\DB\SQL\AbstractStatement;
use Attw\DB\SQL\MySQL\Clause\Where;
use Attw\DB\SQL\MySQL\Operator\Equal;
use Attw\DB\SQL\MySQL\Operator\AndOperator;
use Attw\DB\SQL\MySQL\Clause\From;

/**
 * MySQL SQL Delete statement
*/
class Delete extends AbstractStatement
{
    /**
     * Table to delete some registries
     *
     * @var \Attw\DB\SQL\MySQL\Clause\From
    */
    private $table;

    /**
     * Where clause to indicate some registry
     *
     * @var \Attw\DB\SQL\MySQL\Clause\Where
    */
    private $where;

    /**
     * Contruct a delete statement
     *
     * @param string $table
     * @param array|string $where can be array or a string
     * @param string $operator Operator to separate $where (Default: AND)
     * @throws \InvliadArgumentException case $table is not a string
     * @throws \InvliadArgumentException case $operator is diferent of AND and OR
    */
    public function __construct($table, $where)
    {
        if (!is_string($table)) {
            throw new \InvalidArgumentException(sprintf('%s::%s: the table must be a string', get_class($this), __METHOD__));
        }

        $this->table = new From($table);

        if (is_array($where)) {
            $equals = array();
            foreach ($where as $column => $value) {
                $equals[] = new Equal($column, $value);
            }

            $this->where = new Where(new AndOperator($equals));
        } else {
            $this->where = new Where($where);
        }
    }

    /**
     * Construct the delete SQL
    */
    protected function constructSql()
    {
        $this->sql = sprintf('DELETE %s %s',
                $this->table,
                $this->where);
    }
}