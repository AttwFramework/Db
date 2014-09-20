<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Statement;

use Attw\DB\SQL\MySQL\Clause\Where;

abstract class AbstractStatementWithWhere extends AbstractStatement
{
    protected $where;

    /**
     * Construct the where clause
     *
     * @param string|array $where
    */
    protected function constructWhere($where)
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
    }
}