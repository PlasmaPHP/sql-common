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
 * Represents a JOIN clausel.
 */
class Join {
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Table
     */
    protected $table;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\On[]
     */
    protected $ons = array();
    
    /**
     * Constructor.
     * @param string                              $type
     * @param \Plasma\SQL\QueryExpressions\Table  $table
     */
    function __construct(string $type, \Plasma\SQL\QueryExpressions\Table $table) {
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
     * @return \Plasma\SQL\QueryExpressions\Table
     */
    function getTable(): \Plasma\SQL\QueryExpressions\Table {
        return $this->table;
    }
    
    /**
     * Get the added ON clausels.
     * @return \Plasma\SQL\QueryExpressions\On[]
     */
    function getOns(): array {
        return $this->ons;
    }
    
    /**
     * Adds an ON clausel.
     * @param \Plasma\SQL\QueryExpressions\On  $on
     * @return $this
     */
    function addOn(\Plasma\SQL\QueryExpressions\On $on): self {
        $this->ons[] = $on;
        return $this;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
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
