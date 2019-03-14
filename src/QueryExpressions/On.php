<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class On {
    /**
     * @var string
     */
    protected $leftside;
    
    /**
     * @var string
     */
    protected $rightside;
    
    /**
     * Constructor.
     * @param string  $leftside
     * @param string  $rightside
     */
    function __construct(string $leftside, string $rightside) {
        $this->leftside = $leftside;
        $this->rightside = $rightside;
    }
    
    /**
     * Get the SQL string for this.
     * @return string
     * @throws \LogicException
     */
    function getSQL(): string {
        return $this->leftside.' = '.$this->rightside;
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL();
    }
}
