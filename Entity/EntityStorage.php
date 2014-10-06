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
use Attw\Db\Entity\EntityStorageInterface;
use Attw\Db\Storage\StorageInterface;
use Attw\Db\Statement\StatementFetch;
use Attw\Db\Exception\StorageException;
use \DateTime;
use \ReflectionClass;

/**
 * Interface for storage with entites
*/
class EntityStorage implements EntityStorageInterface
{
    /**
     * Storage instance
     *
     * @var \Attw\Db\Storage\StorageInterface
    */
    private $storage;

    /**
     * @param \Attw\Db\Storage\StorageInterface $storage
    */
    public function __construct(StorageInterface $storage)
    {
        $this->storage = $storage;
    }

    /**
     * Find for some registry in database
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function find(AbstractEntity $entity)
    {
        $where = $this->constructWhere($entity);
        $stmt = $this->storage->read($entity->getTable())->where($where);
        $stmt->setFetchMode(StatementFetch::FETCH_CLASS, get_class($entity), array());
        $stmt->execute();
        $result = $stmt->fetch();

        foreach ($entity->getEntityColumns() as $column => $columnEntity) {
            $reflection = new ReflectionClass($columnEntity);
            $instance = $reflection->newInstanceArgs(array($result->{$column}));
            $result->{$column} = $this->find($instance);
        }

        foreach ($entity->getDatetimeColumns() as $column) {
            $instance = new DateTime($result->{$column});
            $result->{$column} = $instance;
        }

        return $result;
    }

    /**
     * Find all registries in database
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
     * @return array
    */
    public function findAll(AbstractEntity $entity)
    {
        $stmt = $this->storage->read($entity->getTable());
        $stmt->setFetchMode(StatementFetch::FETCH_CLASS, get_class($entity), array());
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $key => $value) {
            foreach ($entity->getEntityColumns() as $column => $columnEntity) {
                $reflection = new ReflectionClass($columnEntity);
                $instance = $reflection->newInstanceArgs(array($value->{$column}));
                $value->{$column} = $this->find($instance);
                $result[$key] = $value;
            }

            foreach ($entity->getDatetimeColumns() as $column) {
                $instance = new DateTime($value->{$column});
                $value->{$column} = $instance;
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * Persist some registry on database
     * If registry exists, update
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function persist(AbstractEntity $entity)
    {
        $primaryKey = $entity->{$entity->getPrimaryKey()};
        $columns = $entity->getColumns();

        if ($primaryKey !== null) {
            $where = $this->constructWhere($entity);
            $stmt = $this->storage->read($entity->getTable())->where($where);
            $stmt->execute();
            $total = $stmt->rowCount();

            if ($total == 0) {
                throw new StorageException('If primary key is not null, it must exists');
            }

            foreach ($columns as $column => $value) {
                if (is_null($value) || $column === $entity->getPrimaryKey()) {
                    unset($columns[$column]);
                }
            }

            return $this->storage->update($entity->getTable(), $columns, $where)->execute();
        }

        foreach ($columns as $column => $value) {
            if ($column === $entity->getPrimaryKey()) {
                unset($columns[$column]);
            }
        }

        return $this->storage->create($entity->getTable(), $columns)->execute();
    }

    /**
     * Delete some registry
     *
     * @param \Attw\Db\Entity\AbstractEntity $entity
    */
    public function remove(AbstractEntity $entity)
    {
        $where = $this->constructWhere($entity);
        $stmt = $this->storage->remove($entity->getTable(), $where);

        return $stmt->execute();
    }

    private function constructWhere(AbstractEntity $entity)
    {
        $columns = $entity->getColumns();
        $primaryKey = $entity->getPrimaryKey();
        return array($primaryKey => $columns[$primaryKey]);
    }
}