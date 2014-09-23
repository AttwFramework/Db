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

class StatementFetch
{
    const FETCH_LAZY = PDO::FETCH_LAZY;
    const FETCH_ASSOC = PDO::FETCH_ASSOC;
    const FETCH_NAMED = PDO::FETCH_NAMED;
    const FETCH_NUM = PDO::FETCH_NUM;
    const FETCH_BOTH = PDO::FETCH_BOTH;
    const FETCH_OBJ = PDO::FETCH_OBJ;
    const FETCH_BOUND = PDO::FETCH_BOUND;
    const FETCH_CLASS = PDO::FETCH_CLASS;
    const FETCH_INTO = PDO::FETCH_INTO;
}