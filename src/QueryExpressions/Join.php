<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

use Plasma\SQL\GrammarInterface;

/**
 * Represents a JOIN clause.
 */
class Join {
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var Table
     */
    protected $table;
    
    /**
     * @var On[]
     */
    protected $ons = array();
    
    /**
     * Constructor.
     * @param string  $type
     * @param Table   $table
     */
    function __construct(string $type, Table $table) {
        $this->type = $type;
        $this->table = $table;
    }
    
    /**
     * Get the type.
     * @return string
     */
    function getType(): string {
        return $this->type;
    }
    
    /**
     * Get the table.
     * @return Table
     */
    function getTable(): Table {
        return $this->table;
    }
    
    /**
     * Get the added ON clauses.
     * @return On[]
     */
    function getOns(): array {
        return $this->ons;
    }
    
    /**
     * Adds an ON clause.
     * @param On  $on
     * @return $this;
     */
    function addOn(On $on): self {
        $this->ons[] = $on;
        return $this;
    }
    
    /**
     * Get the SQL string for this.
     * @param GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?GrammarInterface $grammar): string {
        if($grammar !== null) {
            $table = $grammar->quoteTable($this->table->getTable());
            
            $alias = $this->table->getAlias();
            if(!empty($alias)) {
                $table .= ' AS '.$alias;
            }
        } else {
            $table = $this->table;
        }
        
        $ons = (
            !empty($this->ons) ?
            ' ON '.\implode(' AND ', $this->ons) :
            ''
        );
        
        return ($this->type ? $this->type.' ' : '').'JOIN '.$table.$ons;
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL(null);
    }
}
