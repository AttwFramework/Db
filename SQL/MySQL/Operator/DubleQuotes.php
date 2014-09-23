<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql\MySQL\Operator;

use Attw\Db\Sql\MySQL\Operator\AbstractOperatorQuotes;

class DubleQuotes extends AbstractOperatorQuotes
{
    const OPERATOR = '"';
    protected $operator = '"';
}