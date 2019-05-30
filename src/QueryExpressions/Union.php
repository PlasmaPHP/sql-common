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

/**
 * Represents an UNION clause.
 */
class Union implements UnionInterface {
    /**
     * @var \Plasma\SQLQueryBuilderInterface
     */
    protected $query;
    
    /**
     * Constructor.
     * @param \Plasma\SQLQueryBuilderInterface  $query
     */
    function __construct(\Plasma\SQLQueryBuilderInterface $query) {
        $this->query = $query;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \Plasma\Exception
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        $query = $this->query;
        if($query instanceof \Plasma\SQL\QueryBuilder && $grammar !== null) {
            $query = $query->withGrammar($grammar);
        }
        
        return $query->getQuery();
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
        return $this->getSQL(null);
    }
}
