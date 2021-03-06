<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql\MySQL\Statement;

use Attw\Db\Sql\MySQL\Statement\AbstractStatementWithWhere;
use Attw\Db\Sql\MySQL\Operator\AndOperator;
use Attw\Db\Sql\MySQL\Operator\AsOperator;
use Attw\Db\Sql\MySQL\Operator\OrOperator;
use Attw\Db\Sql\MySQL\Operator\Like;
use Attw\Db\Sql\MySQL\Operator\Equal;
use Attw\Db\Sql\MySQL\Clause\From;
use Attw\Db\Sql\MySQL\Clause\GroupBy;
use Attw\Db\Sql\MySQL\Clause\Limit;
use Attw\Db\Sql\MySQL\Clause\Offset;
use Attw\Db\Sql\MySQL\Clause\OrderBy;
use Attw\Db\Sql\MySQL\Clause\Where;
use Attw\Db\Sql\MySQL\Clause\On;

class Select extends AbstractStatementWithWhere
{
    private $columns;
    private $from;
    private $join;
    private $groupBy = array();
    private $orderBy = array();
    private $offset;
    private $limit;

    /**
     * @param string|array $columns
     * @param string       $table
    */
    public function __construct($columns = '*', $table)
    {
        if (is_array($columns)) {
            $this->columns = implode(', ', $columns);
        } else {
            $this->columns = $columns;
        }

        if (!is_string($table)) {
            throw new \InvalidArgumentException(get_class($this) . '::from(): the table must be a string');
        }

        $this->from = new From($table);
    }

    public function join($table, $on, $type = 'INNER')
    {
        if (!is_string($table)) {
            throw new \InvalidArgumentException(get_class($this) . '::join(): the table must be a string');
        }

        $this->join = sprintf('%s JOIN %s %s', $type, $table, new On($on));

        return $this;
    }

    public function where($where)
    {
        $this->constructWhere($where);

        return $this;
    }

    public function offset($offset)
    {
        $offset = (int) $offset;

        $this->offset = new Offset($offset);

        return $this;
    }

    public function limit($offset, $limit)
    {
        $limit = (int) $limit;
        $offset = (int) $offset;

        $this->limit = new Limit($offset, $limit);

        return $this;
    }

    public function groupBy($column)
    {
        if (!is_string($column)) {
            throw new \InvalidArgumentException('Invalid argument to ' . get_class($this) . '::groupBy(). The column arguments must be a string');
        }

        if (count($this->groupBy) > 0) {
            $this->groupBy[] = $column;
        } else {
            $this->groupBy[] = new GroupBy($column);
        }

        return $this;
    }

    public function orderBy($column, $type)
    {
        if (!is_string($column)) {
            throw new \InvalidArgumentException('Invalid argument to ' . get_class($this) . '::orderBy(). The column arguments must be a string');
        }

        $sprintf = (count($this->orderBy) > 0) ? '%s %s' : 'ORDER BY %s %s' ;

        $this->orderBy[] = sprintf($sprintf, $column, ' ' . strtoupper($type));

        return $this;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('SELECT %s %s %s %s %s %s %s %s',
                $this->columns,
                $this->from,
                $this->join,
                $this->where,
                implode(', ', $this->groupBy),
                implode(', ', $this->orderBy),
                $this->offset,
                $this->limit);
    }
}