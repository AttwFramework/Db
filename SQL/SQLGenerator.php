<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql;

/**
 * Interface to diferents SQL Generators
 * (MySQL, MsSQL, PostgreSQL etc.)
*/
interface SQLGenerator
{
    /**
     * Generate a SQL to select
     *
     * @param string $container also met as table
     * @param string|array $columns columns to fetch
    */
    public function select($container, $columns = '*');

    /**
     * Generate a SQL to insert
     *
     * @param string $container also met as table
     * @param array $data data to insert
    */
    public function insert($container, array $data, $columnsWithQutoes = true);

    /**
     * Generate a SQL to update
     *
     * @param string $container also met as table
     * @param array $data data to set
     * @param string|array $where
    */
    public function update($container, array $data, $where);

    /**
     * Generate a SQL to delete
     *
     * @param string $container also met as table
     * @param string|array $where
    */
    public function delete($container, $where);
}