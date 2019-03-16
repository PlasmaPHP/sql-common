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
 * Represents a constraint. Currently only used with PostgreSQL.
 */
class Constraint implements \Plasma\SQL\ConflictTargetInterface {
    /**
     * @var string
     */
    protected $name;
    
    /**
     * Constructor.
     * @param string  $name
     */
    function __construct(string $name) {
        $this->name = $name;
    }
    
    /**
     *  Get the constraint name.
     * @return string
     */
    function getName(): string {
        return $this->name;
    }
    
    /**
     * Get the conflict identifier.
     * @return string
     */
    function getIdentifier(): string {
        return $this->name;
    }
}
