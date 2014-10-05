<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Statement;

use \PDO;

class ParamTypes
{
	const PARAM_STR = PDO::PARAM_STR;
    const PARAM_LOB = PDO::PARAM_LOB;
    const PARAM_NULL = PDO::PARAM_NULL;
    const PARAM_INT = PDO::PARAM_INT;
    const PARAM_BOOL = PDO::PARAM_BOOL;
}