<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\Storage;

use Attw\DB\Storage\StorageInterface;
use Attw\DB\Connection\ConnectorInterface;
use Attw\DB\SQL\SQLGenerator;
use Attw\DB\Storage\Statement\Select as StorageSelect;

/**
 * Storage class for relational databases
*/
class Storage implements StorageInterface
{
    /**
     * Connector instance
     *
     * @var \Attw\DB\Connection\ConnectorInterface
    */
    private $connector;

    /**
     * SQL generator
     *
     * @var \Attw\DB\SQL\SQLGenerator
    */
    private $sqlGenerator;

    /**
     * @param \Attw\DB\Connection\ConnectorInterface $connector
     * @param \Attw\DB\SQL\SQLGenerator          $sql
    */
    public function __construct(ConnectorInterface $connector, SQLGenerator $sql)
    {
        $this->connector = $connector;
        $this->sqlGenerator = $sql;
    }

    /**
     * Insert something in database
     *
     * @param string $container
     * @param array  $data
    */
    public function create($container, array $data)
    {
        $dataVars = array();

        foreach ($data as $column => $value) {
            $dataVars[ $column ] = '?';
        }

        $sql = $this->sqlGenerator->insert($container, $dataVars, false);
        $stmt = $this->connector->getStatement($sql);
        $stmt = $this->bindAllParams($stmt, array_values($data));

        return $stmt;
    }

    /**
     * Delete some registry from database
     *
     * @param string $container
     * @param array  $where
    */
    public function remove($container, array $where)
    {
        $whereVars = array();

        foreach ($where as $column => $value) {
            $whereVars[] = sprintf('`%s` = ?', $column);
        }

        $sql = $this->sqlGenerator->delete($container, implode(' AND ', $whereVars));
        $stmt = $this->connector->getStatement($sql);
        $stmt = $this->bindAllParams($stmt, array_values($where));

        return $stmt;
    }

    /**
     * Update some registry in database
     *
     * @param string $container
     * @param array  $data
     * @param array  $where
    */
    public function update($container, array $data, array $where)
    {
        $dataVars = array();
        $whereVars = array();

        foreach ($data as $column => $value) {
            $dataVars[ $column ] = sprintf(':%s_data', $column);
        }

        foreach ($where as $column => $value) {
            $whereVars[ $column ] = sprintf(':%s_where', $column);
        }

        $sql = $this->sqlGenerator->update($container, $dataVars, $whereVars);

        $stmt = $this->connector->getStatement($sql);

        $stmt = $this->bindAllParams($stmt, $data, '_data');
        $stmt = $this->bindAllParams($stmt, $where, '_where');

        return $stmt;
    }

    /**
     * Find some registry in database
     *
     * @param string       $container
     * @param array|string $data
    */
    public function read($container, $data = '*')
    {
        $query = new StorageSelect($this->connector, $this->sqlGenerator);

        return $query->create($container, $data);
    }

    /**
     * @param \Attw\DB\Statement\StatementInterface $stmt
     * @param array                                 $params
     * @param string|null                           $prefix
     * @return \Attw\DB\Statement\StatementInterface
    */
    private function bindAllParams($stmt, array $params, $prefix = null)
    {
        foreach ($params as $key => $value) {
            (is_null($prefix)) ? $stmt->bindParam($key + 1, $value) : $stmt->bindParam($key . $prefix, $value);
        }

        return $stmt;
    }
}