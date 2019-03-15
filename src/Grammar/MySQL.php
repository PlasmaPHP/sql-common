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
 * MySQL Grammar.
 */
class MySQL implements \Plasma\SQL\GrammarInterface {
    /**
     * The character used to wrap tables and columns.
     * @var string
     * @source
     */
    const ESCAPE_CHARACTER = '`';
    
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
     * MySQL does not support conflict targets.
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
        if(!empty($conflict->getConflictTargets())) {
            throw new \Plasma\Exception('MySQL does not support conflict targets');
        }
        
        if($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_ERROR) {
            return (new \Plasma\SQL\ConflictResolution('INSERT INTO', ''));
        }
        
        if($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_DO_NOTHING) {
            return (new \Plasma\SQL\ConflictResolution('INSERT IGNORE INTO', ''));
        }
        
        $sql = 'ON CONFLICT DUPLICATE KEY UPDATE';
        
        if($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_REPLACE_ALL) {
            foreach($columns as $column) {
                $col = $this->quoteColumn($column);
                $sql .= ' '.$col.' = VALUES('.$col.'),';
            }
            
            $sql = \substr($sql, 0, -1);
        } elseif($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS) {
            $fields = $conflict->getReplaceColumns();
            if(empty($fields)) {
                throw new \Plasma\Exception('On Conflict resolution REPLACE_COLUMNS has no columns');
            }
            
            foreach($fields as $field) {
                $col = $this->quoteColumn($field);
                $sql .= ' '.$col.' = VALUES('.$col.'),';
            }
            
            $sql = \substr($sql, 0, -1);
        } else {
            throw new \Plasma\Exception('Unknown conflict resolution type');
        }
        
        return (new \Plasma\SQL\ConflictResolution('INSERT INTO', $sql));
    }
}