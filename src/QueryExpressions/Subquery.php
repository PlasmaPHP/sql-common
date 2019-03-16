<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class Subquery {
    /**
     * @var \Plasma\SQLQueryBuilderInterface
     */
    protected $query;
    
    /**
     * @var string|null
     */
    protected $alias;
    
    /**
     * Constructor.
     * @param \Plasma\SQLQueryBuilderInterface  $subquery
     * @param string|null                       $alias
     */
    function __construct(\Plasma\SQLQueryBuilderInterface $subquery, ?string $alias) {
        $this->query = $subquery;
        $this->alias = $alias;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        $query = $this->query;
        if($query instanceof \Plasma\SQL\QueryBuilder && $grammar !== null) {
            $query = $query->withGrammar($grammar);
        }
        
        return '('.$query->getQuery().')'.($this->alias ? ' AS '.$this->alias : '');
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
