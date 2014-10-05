<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\Db\Sql\MySQL\Clause;

use Attw\Db\Sql\AbstractClause;
use \InvalidArgumentException;

class Limit extends AbstractClause
{
    private $offset;
    private $limit;

    public function __construct($offset, $limit)
    {
        if (!is_int($offset) || !is_int($limit)) {
            throw new InvalidArgumentException('Offset and limit must be integer');
        }

        $this->offset = $offset;
        $this->limit = $limit;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('LIMIT %s, %s', $this->offset, $this->limit);
    }
}