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

class OrOperator extends AbstractOperator
{
    const OPERATOR = 'OR';

    private $a;
    private $b;

    public function __construct($a, $b = null)
    {
        $this->a = $a;
        $this->b = $b;
    }

    protected function constructSql()
    {
        $a = $this->a;
        $b = $this->b;

        if (is_array($a)) {
            $this->sql = implode(' OR ', $a);
        } else {
        if (!is_null($b)) {
            $this->sql = sprintf('%s OR %s', $a, $b);
        } else {
            throw new LogicException('If the first argument is\'t an array, second argument must not be null');
        }
        }
    }
}