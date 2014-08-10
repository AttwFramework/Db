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
   use Attw\DB\SQL\MySQL\Operator\AndOperator;

class Where extends AbstractClause
{
    private $operations;

    public function __construct($operations)
    {
        $this->operations = $operations;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('WHERE %s', (!is_array($this->operations)) ? $this->operations :
                                        new AndOperator($this->operations));
    }
}