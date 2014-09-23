<?php
namespace Attw\Db\Sql\MySQL\Operator;

use Attw\Db\Sql\AbstractOperator;

abstract class AbstractOperatorQuotes extends AbstractOperator
{
    protected $a;
    protected $operator;

    public function __construct($a)
    {
        $this->a = $a;
    }

    protected function constructSql()
    {
        $this->sql = $this->operator . $this->a . $this->operator;
    }
}