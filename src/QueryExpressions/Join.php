<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

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
     * Adds an On.
     * @param \Plasma\SQL\QueryExpressions\On  $on
     * @return void
     */
    function addOn(\Plasma\SQL\QueryExpressions\On $on): void {
        $this->ons[] = $on;
    }
    
    /**
     * Get the SQL string for this.
     * @return string
     * @throws \LogicException
     */
    function getSQL(): string {
        $ons = (
            !empty($this->ons) ?
            ' ON '.\implode(' AND ', $this->ons) :
            ''
        );
        
        return $type.' '.$table.$ons;
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL();
    }
}
