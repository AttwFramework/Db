<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Clause;

use Attw\DB\SQL\AbstractClause;

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
                $columnsArr[] = new \Attw\DB\SQL\MySQL\Operator\Equal($column1, $column2);
            }

            $this->sql = sprintf('ON (%s)', new \Attw\DB\SQL\MySQL\Operator\AndO($columnsArr));
        } else {
            $this->sql = sprintf('ON (%s)', $this->columns);
        }
    }
}