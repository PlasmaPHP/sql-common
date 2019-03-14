<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class Fragment {
    /**
     * @var string
     */
    protected $operation;
    
    /**
     * Constructor.
     * @param string  $operation  E.g. `LOWER(`args`)`
     */
    function __construct(string $operation) {
        $this->operation = $operation;
    }
    
    /**
     * Get the SQL string for this.
     * @return string
     */
    function getSQL(): string {
        return $this->operation;
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL();
    }
}
