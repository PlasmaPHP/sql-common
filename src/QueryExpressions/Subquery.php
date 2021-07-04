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
 * Represents a subquery. Interoperable with all Plasma SQL query builder.
 */
class Subquery {
    /**
     * @var SQLQueryBuilderInterface
     */
    protected $query;
    
    /**
     * @var string|null
     */
    protected $alias;
    
    /**
     * Constructor.
     * @param SQLQueryBuilderInterface  $subquery
     * @param string|null               $alias
     */
    function __construct(SQLQueryBuilderInterface $subquery, ?string $alias) {
        $this->query = $subquery;
        $this->alias = $alias;
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
        
        return '('.$query->getQuery().')'.($this->alias ? ' AS '.$this->alias : '');
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
     */
    function __toString(): string {
        /** @noinspection PhpUnhandledExceptionInspection */
        return $this->getSQL(null);
    }
}
