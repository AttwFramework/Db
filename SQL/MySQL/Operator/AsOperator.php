<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql\MySQL\Operator;

use Attw\Db\Sql\MySQL\Operator\AbstractOperatorForTwoValues;

class AsOpearator extends AbstractOperatorForTwoValues
{
    const OPERATOR = 'AS';
    protected $operator = 'AS';
}