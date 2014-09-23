<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Entity;

use Attw\Db\Entity\AbstractEntity;

/**
 * Interface for storage with entites
*/
interface EntityStorageInterface
{
    /**
     * Find for some registry in database
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function find(AbstractEntity $entity);

    /**
     * Find all registries in database
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function findAll(AbstractEntity $entity);

    /**
     * Persist some registry on database
     * If registry exists, update
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function persist(AbstractEntity $entity);

    /**
     * Delete some registry
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function remove(AbstractEntity $entity);
}