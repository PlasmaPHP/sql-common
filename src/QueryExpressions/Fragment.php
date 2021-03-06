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
 * Represents a raw SQL string.
 *
 * Not all SQL queries can be represented and created with the query builder,
 * as such Fragments provide a way to inject unescaped SQL strings into the query.
 */
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
     * Whether the fragment allows escaping. Always `false`.
     * @return bool
     */
    function allowEscape(): bool {
        return false;
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
