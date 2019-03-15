<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class Where implements WhereInterface {
    /**
     * @var string|null
     */
    protected $constraint;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Column|\Plasma\SQL\QueryExpressions\Fragment
     */
    protected $column;
    
    /**
     * @var string|null
     */
    protected $operator;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Parameter|null
     */
    protected $value;
    
    /**
     * Constructor.
     * @param string|null      $constraint
     * @param Column|Fragment  $column
     * @param string|null      $operator
     * @param Parameter|null   $value
     */
    function __construct(?string $constraint, $column, string $operator, ?\Plasma\SQL\QueryExpressions\Parameter $value) {
        $this->constraint = $constraint;
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        if($this->operator === null || $this->value === null) {
            $placeholder = '';
        } elseif($this->operator === 'IN' || $this->operator === 'NOT IN') {
            /** @var \Plasma\SQL\QueryExpressions\Parameter  $this->value */
            $value = $this->value->getValue();
            
            if(!\is_array($value)) {
                throw new \LogicException('Parameter value must be an array for IN and NOT IN clausels');
            }
            
            $placeholder = ' ('.\implode(', ', \array_fill(0, \count($value), '?')).')';
        } elseif($this->operator === 'BETWEEN') {
            $placeholder = ' ? AND ?';
        } else {
            $placeholder = ' ?';
        }
        
        return ($this->constraint ? $this->constraint.' ' : '').$this->column->getSQL($grammar).($this->operator ? ' '.$this->operator : '').$placeholder;
    }
    
    /**
     * Get the parameter.
     * @return \Plasma\SQL\QueryExpressions\Parameter|null
     */
    function getParameter(): ?\Plasma\SQL\QueryExpressions\Parameter {
        return $this->value;
    }
    
    /**
     * Get the parameter wrapped in an array.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        if($this->value === null) {
            return array();
        }
        
        return array($this->value);
    }
}
