<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Statement;

use Attw\DB\SQL\AbstractStatement;
use Attw\DB\SQL\MySQL\Clause\Values;
use Attw\DB\SQL\MySQL\Operator\SimpleQuote;
use \InvalidArgumentException;

/**
 * MySQL SQL Insert statement
*/
class Insert extends AbstractStatement
{
    /**
     * Table to do the insert
     *
     * @var string
    */
    private $table;

    /**
     * Columns to insert
     *
     * @var string
    */
    private $columns;

    /**
     * Values to insert
     *
     * @var \Attw\DB\SQL\MySQL\Clause\Values
    */
    private $values;

    /**
     * Constructor for insert statement
     *
     * @param string $table
     * @param array $data data to inset
     * @param boolean $valuesWithQuote if is to prepared statements, false
     * @throws \InvalidArgumentException case param $table is not a string
    */
    public function __construct($table, array $data, $valuesWithQutoes = true)
    {
        if (!is_string($table)) {
            throw new InvalidArgumentException(get_class($this) . '::__construct(): the table must be a string');
        }

        $values = array_values($data);

        $this->table = $table;

        $this->columns = '(`' . implode('`, `', array_keys($data)) . '`)';

        if ($valuesWithQutoes) {
            $valuesToSql = array();
            foreach ($values as $value) {
                $valuesToSql[] = new SimpleQuote($value);
            }

            $this->values = new Values($valuesToSql);
        } else {
            $this->values = new Values($values);
        }
    }

    /**
     * Construct the insert SQL
    */
    protected function constructSql()
    {
        $this->sql = sprintf('INSERT INTO %s %s %s',
                $this->table,
                $this->columns,
                $this->values);
    }
}