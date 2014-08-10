<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Operator;

use Attw\DB\SQL\AbstractOperator;

class SimpleQuotes extends AbstractOperator
{
    private $a;

    public function __construct($a)
    {
        $this->a = $a;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('\'%s\'', $this->a);
    }
}