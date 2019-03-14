<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class FragmentedWhere implements WhereInterface {
    /**
     * @var string|null
     */
    protected $constraint;
    
    /**
     * @var string
     */
    protected $fragment;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Parameter[]
     */
    protected $params;
    
    /**
     * Constructor.
     * @param string|null                               $constraint
     * @param string                                    $fragment
     * @param \Plasma\SQL\QueryExpressions\Parameter[]  $params
     */
    function __construct(?string $constraint, string $fragment, array $params) {
        $this->constraint = $constraint;
        $this->fragment = $fragment;
        $this->params = $params;
    }
    
    /**
     * Get the SQL string for this.
     * @return string
     * @throws \LogicException
     */
    function getSQL(): string {
        return ($this->constraint ? $this->constraint.' ' : '').$this->fragment;
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        return $this->params;
    }
}
