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

class OrderBy extends AbstractClause
{
    private $column;
    private $type;

    public function __construct($column, $type)
    {
        $this->column = $column;
        $this->type = $type;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('ORDER BY %s $s', $this->column, $this->type);
    }
}