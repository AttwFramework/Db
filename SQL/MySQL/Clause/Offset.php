<?php
/**
 * AttwFramework
 *
 * @author Gabriel Jacinto <gamjj74@hotmail.com>
 * @license MIT License
 * @link http://attwframework.github.io
*/

namespace Attw\DB\SQL\MySQL\Clause;

use Attw\DB\SQL\AbstractClause;
use \InvalidArgumentException;

class Offset extends AbstractClause
{
    private $offset;

    public function __construct($offset)
    {
        if (!is_int($offset)) {
            throw new InvalidArgumentException('Offset must be integer');
        }

        $this->offset = $offset;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('OFFSET %s', $this->offset);
    }
}