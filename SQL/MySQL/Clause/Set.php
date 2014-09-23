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
use Attw\Db\Sql\MySQL\Operator\AndOperator;

class Set extends AbstractClause
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    protected function constructSql()
    {
        if (is_array($this->data)) {
            $this->sql = sprintf('SET %s', new AndOperator($this->data));
        } else {
            $this->sql = sprintf('SET %s', $this->data);
        }
    }
}