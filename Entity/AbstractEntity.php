<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Entity;

use Attw\Db\Exception\EntityException;

/**
 * Interface to an entity
*/
abstract class AbstractEntity
{
    protected $_configs;

    /**
     * Database fields
     *
     * @var array
    */
    protected $fields = array();

    /**
     * Constructor to entity
     * List the table columns to variable $this->fields
    */
    public function __construct($id = null)
    {
        $this->fields = get_object_vars($this);
        unset($this->fields['fields'], $this->fields['_configs']);
        $this->validateConfigs();

        if ($id !== null) {
            $this->{$this->getPrimaryKey()} = $id;
            $this->fields[$this->getPrimaryKey()] = $id;
        }
    }

    /**
     * Setter
     *
     * @param string $column column to set
     * @param string $value value to set
     * @throws \RuntimeException case param $column is a invalid column
     * @example
     * $entity->valid_column = $value;
    */
    public function __set($column, $value)
    {
        $this->verifyIfHasField($column);

        $this->{$column} = $value;
        $this->fields[$column] = $value;
    }

    /**
     * Getter
     *
     * @param string $column column to get value
     * @throws \RuntimeException case param $columns is an invalid column
     * @return string value of column
    */
    public function __get($column)
    {
        $this->verifyIfHasField($column);

        return $this->fields[$column];
    }

    /**
     * Returns table of entity
     *
     * @return string
    */
    public function getTable()
    {
        return $this->_configs['table'];
    }

    /**
     * Returns all columns with values of the entity
     *
     * @return array
    */
    public function getColumns()
    {
        return $this->fields;
    }

    /**
     * Verifies if a column is a valid field of the table
     *
     * @param string $field
     * @return boolean
    */
    public function hasColumn($field)
    {
        return in_array($field, array_keys($this->fields));
    }

    /**
     * Returns all columns that are entities
     *
     * @return array
    */
    public function getEntityColumns()
    {
        return isset($this->_configs['entities']) ? $this->_configs['entities'] : array();
    }

    /**
     * Returns datetime columns
     *
     * @return array
    */
    public function getDatetimeColumns()
    {
        return isset($this->_configs['datetime']) ? $this->_configs['datetime'] : array();
    }

    /**
     * Returns the primary key of the table
     *
     * @return array
    */
    public function getPrimaryKey()
    {
        return $this->_configs['primary_key'];
    }

    private function verifyIfHasField($column)
    {
        if (!$this->hasColumn($column)) {
            throw new EntityException(sprintf('%s isn not a column of the table %s',
                                $column,
                                $this->_configs['table']));
        }
    }

    private function validateConfigs()
    {
        //Primary key
        if (!isset($this->_configs['primary_key'])) {
            EntityException::primaryKeyNotDefined($this);
        } elseif (!is_string($this->_configs['primary_key'])) {
            EntityException::primaryKeyIsNotString($this);
        } elseif (!$this->hasColumn($this->_configs['primary_key'])) {
            EntityException::primaryKeyIsNotAColumn($this);
        }

        //Table
        if (!isset($this->_configs['table'])) {
            EntityException::tableNotDefined($this);
        } elseif (!is_string($this->_configs['table'])) {
            EntityException::tableIsNotString($this);
        }

        //Entities
        if (isset($this->_configs['entities']) && !is_array($this->_configs['entities'])) {
            EntityException::entitiesAreNotOnAnArray($this);
        }
    }
}