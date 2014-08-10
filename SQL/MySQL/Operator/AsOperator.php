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

class AsOpearator extends AbstractOperator
{
    const OPERATOR = 'AS';

    private $a;
    private $b;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('%s AS %s', $this->a, $this->b);
    }
}