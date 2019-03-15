<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Grammar;

/**
 * PostgreSQL Grammar.
 *
 * When using PostgreSQL and having no conflict targets explicitely defined,
 * this grammar will automatically use `{table_name}_pkey` as conflict target.
 * If you do not have a primary or unique index with that name, or you want
 * to use a different one, you have to explicitely set the conflict target(s).
 */
class PostgreSQL implements \Plasma\SQL\GrammarInterface {
    /**
     * The character used to wrap tables and columns.
     * @var string
     * @source
     */
    const ESCAPE_CHARACTER = '"';
    
    /**
     * Wraps the table name into quotes.
     * @param string  $table
     * @return string
     */
    function quoteTable(string $table): string {
        if(\preg_match('/[^A-Za-z0-9_]/', $table) === 0) {
            return static::ESCAPE_CHARACTER.$table.static::ESCAPE_CHARACTER;
        }
        
        return $table;
    }
    
    /**
     * Wraps the column name into quotes.
     * @param string  $table
     * @return string
     */
    function quoteColumn(string $column): string {
        if(\preg_match('/[^A-Za-z0-9_]/', $column) === 0) {
            return static::ESCAPE_CHARACTER.$column.static::ESCAPE_CHARACTER;
        }
        
        return $column;
    }
        
    /**
     * Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.
     * @param \Plasma\SQL\QueryBuilder                                                       $query
     * @param \Plasma\SQL\OnConflict                                                         $conflict
     * @param \Plasma\SQL\QueryExpressions\Column[]|\Plasma\SQL\QueryExpressions\Fragment[]  $columns
     * @param \Plasma\SQL\QueryExpressions\Parameter[]                                       $parameters
     * @return \Plasma\SQL\ConflictResolution
     */
    function onConflictToSQL(
        \Plasma\SQL\QueryBuilder $query,
        \Plasma\SQL\OnConflict $conflict,
        array $columns,
        array $parameters
    ): \Plasma\SQL\ConflictResolution {
        if($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_ERROR) {
            return (new \Plasma\SQL\ConflictResolution('INSERT INTO', ''));
        }
        
        $sql = 'ON CONFLICT';
        
        $targets = $conflict->getConflictTargets();
        if(empty($targets)) {
            /** @var \Plasma\SQL\QueryExpressions\Table  $table */
            $table = $query->_getProperty('table');
            
            if(!($table instanceof \Plasma\SQL\QueryExpressions\Table)) {
                throw new \Plasma\Exception('No table defined');
            }
            
            $sql .= ' '.$table->getTable().'_pkey';
        } else {
            if($targets[0] instanceof \Plasma\SQL\QueryExpressions\Constraint) {
                $sql .= ' ON CONSTRAINT '.$targets[0]->getIdentifier();
            } else {
                $sql .= ' (';
                
                foreach($targets as $target) {
                    $sql .= $target->getIdentifier().', ';
                }
                
                $sql = \substr($sql, 0, -2).')';
            }
        }
        
        if($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_DO_NOTHING) {
            $sql .= ' DO NOTHING';
        } elseif($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_REPLACE_ALL) {
            $sql .= ' DO UPDATE SET';
            
            foreach($columns as $column) {
                $sql .= ' '.$this->quoteColumn($column).' = excluded.'.$column.',';
            }
            
            $sql = \substr($sql, 0, -1);
        } elseif($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS) {
            $sql .= ' DO UPDATE SET';
            
            $fields = $conflict->getReplaceColumns();
            if(empty($fields)) {
                throw new \Plasma\Exception('On Conflict resolution REPLACE_COLUMNS has no columns');
            }
            
            foreach($fields as $field) {
                $sql .= ' '.$this->quoteColumn($field).' = excluded.'.$field.',';
            }
            
            $sql = \substr($sql, 0, -1);
        } else {
            throw new \Plasma\Exception('Unknown conflict resolution type');
        }
        
        return (new \Plasma\SQL\ConflictResolution('INSERT INTO', $sql));
    }
}
