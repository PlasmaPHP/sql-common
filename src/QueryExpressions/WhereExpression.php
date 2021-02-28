<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

use Plasma\SQL\GrammarInterface;
use Plasma\SQL\WhereBuilder;

/**
 * Represents a WhereExpression inside a WHERE clause.
 */
class WhereExpression implements WhereInterface {
    /**
     * @var string|null
     */
    protected $constraint;
    
    /**
     * @var WhereBuilder
     */
    protected $builder;
    
    /**
     * Constructor.
     * @param string|null   $constraint
     * @param WhereBuilder  $builder
     */
    function __construct(?string $constraint, WhereBuilder $builder) {
        $this->constraint = $constraint;
        $this->builder = $builder;
    }
    
    /**
     * Get the SQL string for this.
     * @param GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?GrammarInterface $grammar): string {
        return ($this->constraint ? $this->constraint.' ' : '').'('.$this->builder->getSQL($grammar).')';
    }
    
    /**
     * Get the parameters.
     * @return Parameter[]
     */
    function getParameters(): array {
        return $this->builder->getParameters();
    }
}
