<?php
namespace Attw\DB\SQL\MySQL\Operator;

use Attw\DB\SQL\AbstractOperator;

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