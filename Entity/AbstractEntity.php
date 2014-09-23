<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Entity;

use \RuntimeException;
use \ReflectionProperty;

/**
 * Interface to an entity
*/
abstract class AbstractEntity
{
    protected $_table;

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
    public function __construct()
    {
        $this->fields = get_object_vars($this);
        unset($this->fields['fields']);
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

        $this->{$column} = print_r($value, true);
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

        $columns = get_object_vars($this);

        return $columns[ $column ];
    }

    /**
     * Get table of entity
     *
     * @return string
    */
    public function getTable()
    {
        return $this->_table;
    }

    /**
     * Get all columns with values of the entity
     *
     * @return array
    */
    public function getColumns()
    {
        $columns = get_object_vars($this);
        unset($columns['_table'], $columns['fields']);

        return $columns;
    }

    /**
     * Verify if a column is a valid field of the table
     *
     * @param string $field
     * @return boolean
    */
    public function hasField($field)
    {
        return in_array($field, array_keys($this->fields));
    }

    /**
     * Get the primary key of the table
     *
     * @throws \RuntimeException case exists two primary keys
     * @throws \RuntimeException case is not defined a primary key
     * @return array
    */
    public function getPrimaryKey()
    {
        $primaries = array();

        foreach ($this->getColumns() as $field => $value) {
            $property = new ReflectionProperty(get_class($this), $field);

            if(
                '%2F%2A%2A%0D%0A%09%09+%2A+%40key+PRIMARY+KEY%0D%0A++++%09%2A%2F' === urlencode($property->getDocComment())
                || '%2F%2A%2A%0D%0A%09%09+%2A+%40key+PRIMARY+KEY%0D%0A%09%09%2A%2F' === urlencode($property->getDocComment())
           ){
                $primaries[ $field ] = $value;
            }
        }

        if (count($primaries) > 1) {
            throw new RuntimeException('Define a only primary key on entity: ' . get_class($this));
        } elseif (count($primaries) == 0) {
            throw new RuntimeException('Define a primary key on entity with the comment "/** * @key PRIMARY KEY */" in entity: ' . get_class($this));
        }

        return $primaries;
    }

    private function verifyIfHasField($column)
    {
        if (!$this->hasField($column)) {
            throw new RuntimeException(sprintf('%s isn not a column of the table %s',
                                $column,
                                $this->_table));
        }
    }
}