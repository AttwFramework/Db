<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL;

use Attw\DB\SQL\SQLGenerator;
use Attw\DB\SQL\MySQL\Statement\Select;
use Attw\DB\SQL\MySQL\Statement\Insert;
use Attw\DB\SQL\MySQL\Statement\Update;
use Attw\DB\SQL\MySQL\Statement\Delete;

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
     * @return \Attw\DB\SQL\MySQL\Statement\Select
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
     * @return \Attw\DB\SQL\MySQL\Statement\Insert
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
     * @return \Attw\DB\SQL\MySQL\Statement\Update
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
     * @return \Attw\DB\SQL\MySQL\Statement\Delete
    */
    public function delete($container, $where)
    {
        return new Delete($container, $where);
    }
}