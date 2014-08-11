<?php
namespace Attw\DB\SQL\MySQL\Operator;

use Attw\DB\SQL\MySQL\Operator\AbstractOperatorForTwoValues;

abstract class AbstractOperatorThatAcceptArray extends AbstractOperatorForTwoValues
{
    protected $a;
    protected $b;
    protected $operator;

    public function __construct($a, $b = null)
    {
        $this->a = $a;
        $this->b = $b;
    }

    protected function constructSql()
    {
        $a = $this->a;
        $b = $this->b;

        if (is_array($a)) {
            $this->sql = implode(' ' . $this->operator . ' ', $a);
        } else {
            if (!is_null($b)) {
                parent::constructSql();
            } else {
                throw new LogicException('If the first argument is\'t an array, second argument must not be null');
            }
        }
    }
}