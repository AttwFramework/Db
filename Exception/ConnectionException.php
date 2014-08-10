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

class ConnectionException extends Exception
{
	public static function mysqliError($error, $code)
	{
		throw new ConnectionException('MySQLi error: ' . $error, $code);
	}

	public static function pdoError($error, $code)
	{
		throw new ConnectionException('PDO error: ' . $error, $code);
	}
}