<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\Exception;

use \Exception;

class StatementException extends Exception
{
	public static function unknownFetchType($type)
	{
		throw new StatementException('Unknown fetch type: ' . $type);
	}

	public static function unknownParamType($type)
	{
		throw new StatementException('Unknown param type: ' . $type);
	}

	public static function mysqliError($error, $code)
	{
		throw new StatementException('MySQLi error: ' . $error);
	}

	public static function mysqliStmtError($error, $code)
	{
		throw new StatementException('MySQLi statement error: ' . $error);
	}

	public static function pdoStmtError($error, $code)
	{
		throw new StatementException('PDO error: ' . $error);
	}
}