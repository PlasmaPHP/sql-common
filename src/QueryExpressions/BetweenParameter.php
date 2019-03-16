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
 * Represents a parameter used for BETWEEN.
 */
class BetweenParameter extends Parameter {
    /**
     * @var \Plasma\SQL\QueryExpressions\Fragment[]|\Plasma\SQL\QueryExpressions\Parameter[]
     */
    protected $value;
    
    /**
     * Constructor.
     * @param Fragment|Parameter  $first
      *@param Fragment|Parameter  $second
     */
    function __construct($first, $second) {
        $this->value = array($first, $second);
    }
    
    /**
     * Whether this parameter has a value. If not, the QueryBuilder is expected to throw an Exception.
     * @return bool
     */
    function hasValue(): bool {
        return true;
    }
    
    /**
     * Get the value.
     * @return \Plasma\SQL\QueryExpressions\Fragment[]|\Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getValue(): array {
        return $this->value;
    }
    
    /**
     * Set the value.
     * @param mixed  $value
     * @return void
     * @throws \LogicException
     */
    function setValue($value): void {
        throw new \LogicException('BetweenParameter can not be mutated - mutate the parameters instead');
    }
}
