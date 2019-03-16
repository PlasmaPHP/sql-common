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
 * Represents a table with optional alias and escaping.
 */
class Table {
    /**
     * @var string
     */
    protected $table;
    
    /**
     * @var string|null
     */
    protected $alias;
    
    /**
     * @var bool
     */
    protected $allowEscape;
    
    /**
     * Constructor.
     * @param string       $table
     * @param string|null  $alias
     * @param bool         $allowEscape
     */
    function __construct(string $table, ?string $alias, bool $allowEscape) {
        $this->table = $table;
        $this->alias = $alias;
        $this->allowEscape = $allowEscape;
    }
    
    /**
     * Get the table.
     * @return string
     */
    function getTable(): string {
        return $this->table;
    }
    
    /**
     * Get the alias.
     * @return string|null
     */
    function getAlias(): ?string {
        return $this->alias;
    }
    
    /**
     * Whether the table allows escaping.
     * @return bool
     */
    function allowEscape(): bool {
        return $this->allowEscape;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        if($grammar !== null && $this->allowEscape) {
            $table = $grammar->quoteTable($this->table);
        } else {
            $table = $this->table;
        }
        
        return $table.($this->alias ? ' AS '.$this->alias : '');
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL(null);
    }
}
