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

class Values extends AbstractClause
{
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    protected function constructSql()
    {
        $values = implode(', ', $this->values);
        $this->sql = sprintf('VALUES (%s)', $values);
    }
}