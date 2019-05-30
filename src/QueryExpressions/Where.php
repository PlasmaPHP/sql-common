<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

/**
 * Represents a WHERE clause.
 */
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
    function __construct(?string $constraint, $column, ?string $operator, ?\Plasma\SQL\QueryExpressions\Parameter $value) {
        $this->constraint = $constraint;
        $this->column = $column;
        $this->operator = $operator;
        $this->value = $value;
    }
    
    /**
     * Get the SQL string for this.
     * Placeholders use `?`.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        if($this->operator === null || $this->value === null) {
            $placeholder = '';
        } elseif($this->operator === 'IN' || $this->operator === 'NOT IN') {
            $value = $this->value->getValue();
            
            if(!\is_array($value)) {
                throw new \LogicException('Parameter value must be an array for IN and NOT IN clauses');
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
     * Get the raw parameter.
     * @return \Plasma\SQL\QueryExpressions\Parameter|null
     */
    function getParameter(): ?\Plasma\SQL\QueryExpressions\Parameter {
        return $this->value;
    }
    
    /**
     * Get the parameter wrapped in an array.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     * @throws \LogicException
     */
    function getParameters(): array {
        if($this->value === null) {
            return array();
        } elseif($this->operator === 'IN' || $this->operator === 'NOT IN') {
            $value = $this->value->getValue();
            
            if(!\is_array($value)) {
                throw new \LogicException('Parameter value must be an array for IN and NOT IN clauses');
            }
            
            $params = array();
            foreach($value as $val) {
                $params[] = new \Plasma\SQL\QueryExpressions\Parameter($val, true);
            }
            
            return $params;
        } elseif($this->operator === 'BETWEEN') {
            [ $first, $second ] = $this->value->getValue();
            
            return array($first, $second);
        }
        
        return array($this->value);
    }
}
