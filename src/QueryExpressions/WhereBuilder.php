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
 * Represents a WhereBuilder inside a WHERE clause.
 */
class WhereBuilder implements WhereInterface {
    /**
     * @var string|null
     */
    protected $constraint;
    
    /**
     * @var \Plasma\SQL\WhereBuilder
     */
    protected $builder;
    
    /**
     * Constructor.
     * @param string|null               $constraint
     * @param \Plasma\SQL\WhereBuilder  $builder
     */
    function __construct(?string $constraint, \Plasma\SQL\WhereBuilder $builder) {
        $this->constraint = $constraint;
        $this->builder = $builder;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        return ($this->constraint ? $this->constraint.' ' : '').'('.$this->builder->getSQL($grammar).')';
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        return $this->builder->getParameters();
    }
}
