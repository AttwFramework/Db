<?php
namespace Attw\Db\Sql\MySQL\Operator;

use Attw\Db\Sql\AbstractOperator;

abstract class AbstractOperatorForTwoValues extends AbstractOperator
{
    protected $a;
    protected $b;
    protected $operator;

    public function __construct($a, $b)
    {
        $this->a = $a;
        $this->b = $b;
    }

    protected function constructSql()
    {
        $this->sql = sprintf('%s %s %s', $this->a, $this->operator, $this->b);
    }
}