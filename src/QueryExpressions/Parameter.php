<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class Parameter {
    /**
     * @var mixed
     */
    protected $value;
    
    /**
     * @var bool
     */
    protected $withValue;
    
    /**
     * Constructor.
     * @param mixed  $value
      *@param bool   $withValue
     */
    function __construct($value = null, bool $withValue = false) {
        $this->value = $value;
        $this->withValue = $withValue;
    }
    
    /**
     * Whether this parameter has a value. If not, the QueryBuilder is expected to throw an Exception.
     * @return bool
     */
    function hasValue(): bool {
        return $this->withValue;
    }
    
    /**
     * Get the value.
     * @return mixed
     */
    function getValue() {
        return $this->value;
    }
    
    /**
     * Set the value.
     * @param mixed  $value
     * @return void
     */
    function setValue($value): void {
        $this->value = $value;
        $this->withValue = true;
    }
}
