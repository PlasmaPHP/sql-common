<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

/**
 * Provides an implementation for a locked SQL querybuilder.
 * This is mainly useful to just provide the SQL query and the parameters fixed
 * so that multiple times calling `getQuery` or `getParameters` won't
 * be computed each time.
 */
class LockedQueryBuilder implements \Plasma\SQLQueryBuilderInterface {
    /**
     * @var string
     */
    protected $query;
    
    /**
     * @var array
     */
    protected $params;
    
    /**
     * Constructor.
     * @param string  $query
     * @param array   $params
     */
    function __construct(string $query, array $params) {
        $this->query = $query;
        $this->params = $params;
    }
    
    /**
     * A locked querybuilder can't be created with this interface method.
     * @return self
     * @throws \LogicException
     */
    static function create(): self {
        throw new \LogicException('Use the constructor instead');
    }
    
    /**
     * Returns the query.
     * @return string
     */
    function getQuery() {
        return $this->query;
    }
    
    /**
     * Returns the associated parameters for the query.
     * @return array
     */
    function getParameters(): array {
        return $this->params;
    }
}
