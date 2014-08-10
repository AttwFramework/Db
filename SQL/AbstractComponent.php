<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL;

/**
 * Inteface to SQL Components
 * (Clauses, Statements, Operators)
*/
abstract class AbstractComponent
{
    /**
     * SQL
     *
     * @var string
    */
    protected $sql;

    /**
     * Object to string
     *
     * @return string SQL
    */
    public function __toString()
    {
        $this->constructSql();

        return $this->sql;
    }

    /**
     * Construct the SQL
    */
    abstract protected function constructSql();
}