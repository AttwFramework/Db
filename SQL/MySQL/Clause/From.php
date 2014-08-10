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

class From extends AbstractClause
{
    private $table;

    public function __construct($table)
    {
        $this->table = $table;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('FROM %s', $this->table);
    }
}