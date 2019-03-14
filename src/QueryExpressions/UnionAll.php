<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class UnionAll implements UnionInterface {
    /**
     * @var \Plasma\SQLQuerybuilderInterface
     */
    protected $query;
    
    /**
     * Constructor.
     * @param \Plasma\SQLQuerybuilderInterface  $query
     */
    function __construct(\Plasma\SQLQuerybuilderInterface $query) {
        $this->query = $query;
    }
    
    /**
     * Get the SQL string for this.
     * @return string
     * @throws \LogicException
     */
    function getSQL(): string {
        return $this->query->getQuery();
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        return $this->query->getParameters();
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL();
    }
}
