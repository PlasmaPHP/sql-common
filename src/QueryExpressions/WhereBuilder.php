<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

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
     * @return string
     */
    function getSQL(): string {
        return ($this->constraint ? $this->constraint.' ' : '').'('.$this->builder->getWhere().')';
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        return $this->builder->getParameters();
    }
}
