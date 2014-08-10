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
use Attw\DB\SQL\MySQL\Clause\JoinType;

class Join extends AbstractClause
{
    private $type;
    private $table;
    private $on;

    public function __construct(JoinType $type, $table, On $on)
    {
        $this->type = $type;
        $this->table = $table;
        $this->on = $on;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('% JOIN %s %s', $this->type, $this->table, $this->on);
    }
}