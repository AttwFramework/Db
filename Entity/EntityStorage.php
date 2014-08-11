<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\Entity;

use Attw\DB\Entity\AbstractEntity;
use Attw\DB\Entity\EntityStorageInterface;
use Attw\DB\Storage\StorageInterface;
use Attw\DB\Statement\StatementFetch;
use Attw\DB\Exception\StorageException;

/**
 * Interface for storage with entites
*/
class EntityStorage implements EntityStorageInterface
{
    /**
     * Storage instance
     *
     * @var \Attw\DB\Storage\StorageInterface
    */
    private $storage;

    /**
     * @param \Attw\DB\Storage\StorageInterface $storage
    */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Find for some registry in database
     *
     * @param \Attw\DB\Entity\AbstractEntity $entity
    */
    public function find(AbstractEntity $entity)
    {
        $primaryKey = $entity->getPrimaryKey();
        $this->hasPrimaryKey($primaryKey);

        $stmt = $this->storage->read($entity->getTable())->where($primaryKey);
        $stmt->setFetchMode(StatementFetch::FETCH_CLASS, get_class($entity), array());
        $stmt->execute();

        return $stmt->fetch();
    }

    /**
     * Find all registries in database
     *
     * @param \Attw\DB\Entity\AbstractEntity $entity
     * @return array
    */
    public function findAll(AbstractEntity $entity)
    {
        $stmt = $this->storage->read($entity->getTable());
        $stmt->setFetchMode(StatementFetch::FETCH_CLASS, get_class($entity), array());
        $stmt->execute();

        return $stmt->fetchAll();
    }

    /**
     * Persist some registry on database
     * If registry exists, update
     *
     * @param \Attw\DB\Entity\AbstractEntity $entity
    */
    public function persist(AbstractEntity $entity)
    {
        $primaryKey = $entity->getPrimaryKey();
        $columns = $entity->getColumns();
        $primaryKeyColumn = null;

        if (count($primaryKey) > 0) {
            foreach ($primaryKey as $key => $value) {
                if (!is_null($value) || $value != '' || !$value != ' ') {
                    $primaryKeyColumn = $key;

                    $stmt = $this->storage->read($entity->getTable())->where($primaryKey);
                    $stmt->execute();
                    $total = $stmt->rowCount();

                    if ($total == 0) {
                        throw new StorageException('If primary key is not null, it must exists');
                    }

                    foreach ($columns as $column => $value) {
                        if (is_null($value) || $column === $primaryKeyColumn) {
                        unset($columns[ $column ]);
                        }
                    }

                    return $this->storage->update($entity->getTable(), $columns, $primaryKey)
                                 ->execute();
                }
            }
        }

        foreach ($columns as $column => $value) {
            if ($column === $primaryKeyColumn) {
                unset($columns[ $column ]);
            }
        }

        return $this->storage->create($entity->getTable(), $columns)
                 ->execute();
    }

    /**
     * Delete some registry
     *
     * @param \Attw\DB\Entity\AbstractEntity $entity
    */
    public function remove(AbstractEntity $entity)
    {
        $primaryKey = $entity->getPrimaryKey();
        $this->hasPrimaryKey($primaryKey);

        $stmt = $this->storage->remove($entity->getTable(), $primaryKey);

        return $stmt->execute();
    }

    private function hasPrimaryKey(array $primaryKey)
    {
        foreach ($primaryKey as $key => $value) {
            if (is_null($value) || $value == '' || $value == ' ') {
                throw new \RuntimeException(sprintf('The primary key "%s" must not be null', $key));
            }
        }
    }
}