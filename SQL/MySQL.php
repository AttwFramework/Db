<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql;

use Attw\Db\Sql\SQLGenerator;
use Attw\Db\Sql\MySQL\Statement\Select;
use Attw\Db\Sql\MySQL\Statement\Insert;
use Attw\Db\Sql\MySQL\Statement\Update;
use Attw\Db\Sql\MySQL\Statement\Delete;

/**
 * Adapter to MySQL Statements
 *
 * E.g.:
 * $mysql = new MySQL;
 * $select = $mysql->select('table');
 * $select->where(array('id' => 17));
*/
class MySQL implements SQLGenerator
{
    /**
     * Create a select statement
     *
     * @param string                   $container
     * @param string|array             $columns
     * @return \Attw\Db\Sql\MySQL\Statement\Select
    */
    public function select($container, $columns = '*')
    {
        return new Select($columns, $container);
    }

    /**
     * Create a insert statement
     *
     * @param string                   $container
     * @param array                    $data
     * @param boolean                  $columnsWithQutoes
     * @return \Attw\Db\Sql\MySQL\Statement\Insert
    */
    public function insert($container, array $data, $columnsWithQutoes = true)
    {
        return new Insert($container, $data, $columnsWithQutoes);
    }

    /**
     * Create a update statement
     *
     * @param string                   $container
     * @param array                    $data
     * @param array|string             $where
     * @return \Attw\Db\Sql\MySQL\Statement\Update
    */
    public function update($container, array $data, $where)
    {
        return new Update($container, $data, $where);
    }

    /**
     * Create a delete statement
     *
     * @param string                   $container
     * @param array|string             $where
     * @return \Attw\Db\Sql\MySQL\Statement\Delete
    */
    public function delete($container, $where)
    {
        return new Delete($container, $where);
    }
}