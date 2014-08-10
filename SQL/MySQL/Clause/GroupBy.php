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

class GroupBy extends AbstractClause
{
    private $column;

    public function __construct($column)
    {
        $this->column = $column;
    }

    protected function constructSql()
    {
        $column = (is_array($this->column)) ? implode(', ', $this->column) : $this->column;
        $this->sql = sprintf('GROUP BY %s', $column);
    }
}