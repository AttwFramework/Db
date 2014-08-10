<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\Storage;

/**
 * Interface for database storage
*/
interface StorageInterface
{
    /**
     * Find some registry in database
     *
     * @param string       $container
     * @param string|array $data
    */
    public function read($container, $data = '*');

    /**
     * Insert something in database
     *
     * @param string $container
     * @param array  $data
    */
    public function create($container, array $data);

    /**
     * Delete some registry from database
     *
     * @param string $container
     * @param array  $where
    */
    public function remove($container, array $where);

    /**
     * Update some registry in database
     *
     * @param string $container
     * @param array  $data
     * @param array  $where
    */
    public function update($container, array $data, array $where);
}