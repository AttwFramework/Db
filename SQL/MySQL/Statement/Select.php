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
use Attw\DB\SQL\MySQL\Operator\AndOperator;
use Attw\DB\SQL\MySQL\Operator\AsOperator;
use Attw\DB\SQL\MySQL\Operator\OrOperator;
use Attw\DB\SQL\MySQL\Operator\Like;
use Attw\DB\SQL\MySQL\Operator\Equal;
use Attw\DB\SQL\MySQL\Clause\From;
use Attw\DB\SQL\MySQL\Clause\GroupBy;
use Attw\DB\SQL\MySQL\Clause\Limit;
use Attw\DB\SQL\MySQL\Clause\Offset;
use Attw\DB\SQL\MySQL\Clause\OrderBy;
use Attw\DB\SQL\MySQL\Clause\Where;
use Attw\DB\SQL\MySQL\Clause\On;

class Select extends AbstractStatement
{
    private $columns;
    private $from;
    private $join;
    private $where;
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
        if (is_array($where)) {
            $equals = array();
            foreach ($where as $column => $value) {
                $equals[] = new Equal($column, $value);
            }

            $this->where = new Where(new AndOperator($equals));
        } else {
            $this->where = new Where($where);
        }

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