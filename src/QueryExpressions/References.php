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
 * Represents a reference for column definitions.
 */
class References {
    /**
     * Produce an error indicating that the deletion or update
     * would create a foreign key constraint violation.
     * If the constraint is deferred, this error will be produced
     * at constraint check time if there still exist any referencing rows.
     * This is the default action.
     * @var string
     * @source
     */
    const ACTION_NONE = 'NO ACTION';
    
    /**
     * Produce an error indicating that the deletion or update
     * would create a foreign key constraint violation.
     * This is the same as NO ACTION except
     * that the check is not deferrable.
     * @var string
     * @source
     */
    const ACTION_RESTRICT = 'RESTRICT';
    
    /**
     * Delete any rows referencing the deleted row,
     * or update the value of the referencing column
     * to the new value of the referenced column, respectively.
     * @var string
     * @source
     */
    const ACTION_CASCADE = 'CASCADE';
    
    /**
     * Set the referencing column(s) to null.
     * @var string
     * @source
     */
    const ACTION_SET_NULL = 'SET NULL';
    
    /**
     * Set the referencing column(s) to their default values.
     * @var string
     * @source
     */
    const ACTION_SET_DEFAULT = 'SET DEFAULT';
    
    /**
     * @var Fragment|Table
     */
    protected $reftable;
    
    /**
     * @var Column|Fragment|null
     */
    protected $refcol;
    
    /**
     * @var bool
     */
    protected $matchFull;
    
    /**
     * @var bool
     */
    protected $matchPartial;
    
    /**
     * @var bool
     */
    protected $matchSimple;
    
    /**
     * @var string|null
     */
    protected $onDelete;
    
    /**
     * @var string|null
     */
    protected $onUpdate;
    
    /**
     * Constructor.
     * @param Fragment|Table        $table
     * @param Column|Fragment|null  $column
     * @param bool                  $matchFull
     * @param bool                  $matchPartial
     * @param bool                  $matchSimple
     * @param string|null           $onDelete
     * @param string|null           $onUpdate
     */
    function __construct($table, $column, bool $matchFull = false, bool $matchPartial = false, bool $matchSimple = false, ?string $onDelete = null, ?string $onUpdate = null) {
        $this->reftable = $table;
        $this->refcol = $column;
        $this->matchFull = $matchFull;
        $this->matchPartial = $matchPartial;
        $this->matchSimple = $matchSimple;
        $this->onDelete = $onDelete;
        $this->onUpdate = $onUpdate;
    }
    
    /**
     * Get the referenced table.
     * @return Fragment|Table
     */
    function getTable() {
        return $this->reftable;
    }
    
    /**
     * Get the referenced column. If null, the primary key is used.
     * @return Column|Fragment|null
     */
    function getColumn() {
        return $this->refcol;
    }
    
    /**
     * `MATCH FULL` will not allow one column of a multicolumn foreign key
     * to be null unless all foreign key columns are null.
     *
     * For users of MySQL: Use of an explicit MATCH clause will not
     * have the specified effect, and also causes ON DELETE and ON UPDATE
     * clauses to be ignored.
     * @return bool
     */
    function getMatchFull(): bool {
        return $this->matchFull;
    }
    
    /**
     * Not implemented by PostgreSQL yet.
     *
     * For users of MySQL: Use of an explicit MATCH clause will not
     * have the specified effect, and also causes ON DELETE and ON UPDATE
     * clauses to be ignored.
     * @return bool
     */
    function getMatchPartial(): bool {
        return $this->matchPartial;
    }
    
    /**
     * `MATCH SIMPLE` allows some foreign key columns to be null
     * while other parts of the foreign key are not null.
     *
     * For users of MySQL: Use of an explicit MATCH clause will not
     * have the specified effect, and also causes ON DELETE and ON UPDATE
     * clauses to be ignored.
     * @return bool
     */
    function getMatchSimple(): bool {
        return $this->matchSimple;
    }
    
    /**
     * Get the `ON DELETE` action.
     * @return string|null
     */
    function getOnDelete(): ?string {
        return $this->onDelete;
    }
    
    /**
     * Get the `ON UPDATE` action.
     * @return string|null
     */
    function getOnUpdate(): ?string {
        return $this->onUpdate;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        if($grammar !== null && $this->reftable->allowEscape()) {
            $table = $grammar->quoteTable($this->reftable);
        } else {
            $table = $this->reftable;
        }
        
        if($this->refcol !== null) {
            if($grammar !== null && $this->refcol->allowEscape()) {
                $column = $grammar->quoteColumn($this->refcol);
            } else {
                $column = $this->refcol;
            }
            
            $column = ' ('.$column.')';
        } else {
            $column = '';
        }
        
        $sql = $table.$column;
        
        if($this->matchFull) {
            $sql .= ' MATCH FULL';
        } elseif($this->matchPartial) {
            $sql .= ' MATCH PARTIAL';
        } elseif($this->matchSimple) {
            $sql .= ' MATCH SIMPLE';
        }
        
        if($this->onDelete !== null) {
            $sql .= ' ON DELETE '.$this->onDelete;
        }
        
        if($this->onUpdate !== null) {
            $sql .= ' ON UPDATE '.$this->onUpdate;
        }
        
        return $sql;
    }
}
