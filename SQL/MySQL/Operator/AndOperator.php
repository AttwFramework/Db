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
use \LogicException;

class AndOperator extends AbstractOperator
{
    const OPERATOR = 'AND';

    private $a = array();

    public function __construct(array $a)
    {
        $this->a = $a;
    }

    protected function constructSql()
    {
        $this->sql = implode(' AND ', $this->a);
    }
}