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
     * @param string  $column
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
     * @return \Plasma\SQL\ConflictResolution|null
     */
    function onConflictToSQL(
        \Plasma\SQL\QueryBuilder $query,
        \Plasma\SQL\OnConflict $conflict,
        array $columns,
        array $parameters
    ): ?\Plasma\SQL\ConflictResolution {
        if($conflict->getType() === \Plasma\SQL\OnConflict::RESOLUTION_ERROR) {
            return null;
        }
        
        $sql = 'ON CONFLICT';
        
        $targets = $conflict->getConflictTargets();
        if(!empty($targets)) {
            if($targets[0] instanceof \Plasma\SQL\QueryExpressions\Constraint) {
                $sql .= ' ON CONSTRAINT '.$this->quoteColumn($targets[0]->getIdentifier());
            } elseif(\count($targets) === 1) {
                $sql .= ' '.$this->quoteColumn($targets[0]->getIdentifier());
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
            throw new \Plasma\Exception('Unknown conflict resolution type'); // @codeCoverageIgnore
        }
        
        return (new \Plasma\SQL\ConflictResolution('INSERT INTO', $sql));
    }
    
    /**
     * Whether the grammar supports row-level locking.
     * @return bool
     */
    function supportsRowLocking(): bool {
        return true;
    }
    
    /**
     * Get the SQL command for the given row-level locking mode.
     * @param int  $lock
     * @return string
     * @throws \InvalidArgumentException
     */
    function getSQLForRowLocking(int $lock): string {
        switch($lock) {
            case \Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_UPDATE:
                return 'FOR UPDATE';
            break;
            case \Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_NO_KEY_UPDATE:
                return 'FOR NO KEY UPDATE';
            break;
            case \Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_SHARE:
                return 'FOR SHARE';
            break;
            case \Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_KEY_SHARE:
                return 'FOR KEY SHARE';
            break;
            default:
                throw new \Plasma\Exception('Unknown SELECT row-level locking mode');
            break;
        }
    }
    
    /**
     * Whether the grammar supports RETURNING.
     * @return bool
     */
    function supportsReturning(): bool {
        return true;
    }
}
