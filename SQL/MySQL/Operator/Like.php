<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Operator;

use Attw\DB\SQL\MySQL\Operator\AbstractOperatorForTwoValues;

class Like extends AbstractOperatorForTwoValues
{
    const OPERATOR = 'LIKE';
    protected $operator = 'LIKE';
}