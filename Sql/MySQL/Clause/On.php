<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql\MySQL\Clause;

use Attw\Db\Sql\AbstractClause;

class On extends AbstractClause
{
    private $columns;

    public function __construct($columns)
    {
        $this->columns = $columns;
    }

    protected function constructSql()
    {
        if (is_array($this->columns)) {
            $columnsArr = array();
            foreach ($this->columns as $column1 => $column2) {
                $columnsArr[] = new \Attw\Db\Sql\MySQL\Operator\Equal($column1, $column2);
            }

            $this->sql = sprintf('ON (%s)', new \Attw\Db\Sql\MySQL\Operator\AndO($columnsArr));
        } else {
            $this->sql = sprintf('ON (%s)', $this->columns);
        }
    }
}