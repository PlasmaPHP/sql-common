<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpDocMissingThrowsInspection
*/

namespace Plasma\SQL\QueryExpressions;

use Plasma\Exception;
use Plasma\SQL\GrammarInterface;
use Plasma\SQL\QueryBuilder;
use Plasma\SQLQueryBuilderInterface;

/**
 * Represents an UNION ALL clause.
 */
class UnionAll implements UnionInterface {
    /**
     * @var SQLQueryBuilderInterface
     */
    protected $query;
    
    /**
     * Constructor.
     * @param SQLQueryBuilderInterface  $query
     */
    function __construct(SQLQueryBuilderInterface $query) {
        $this->query = $query;
    }
    
    /**
     * Get the SQL string for this.
     * @param GrammarInterface|null  $grammar
     * @return string
     * @throws Exception
     */
    function getSQL(?GrammarInterface $grammar): string {
        $query = $this->query;
        if($query instanceof QueryBuilder && $grammar !== null) {
            $query = $query->withGrammar($grammar);
        }
        
        return $query->getQuery();
    }
    
    /**
     * Get the parameters.
     * @return Parameter[]
     */
    function getParameters(): array {
        return $this->query->getParameters();
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     * @throws Exception
     */
    function __toString(): string {
        return $this->getSQL(null);
    }
}
