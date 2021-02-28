<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Grammar;

use Plasma\Exception;
use Plasma\SQL\ConflictResolution;
use Plasma\SQL\GrammarInterface;
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Constraint;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

/**
 * PostgreSQL Grammar.
 */
class PostgreSQL implements GrammarInterface {
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
        /** @noinspection NotOptimalRegularExpressionsInspection */
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
        /** @noinspection NotOptimalRegularExpressionsInspection */
        if(\preg_match('/[^A-Za-z0-9_]/', $column) === 0) {
            return static::ESCAPE_CHARACTER.$column.static::ESCAPE_CHARACTER;
        }
        
        return $column;
    }
    
    /**
     * Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.
     * @param QueryBuilder         $query
     * @param OnConflict           $conflict
     * @param Column[]|Fragment[]  $columns
     * @param Parameter[]          $parameters
     * @return ConflictResolution|null
     * @throws Exception
     */
    function onConflictToSQL(
        QueryBuilder $query,
        OnConflict $conflict,
        array $columns,
        array $parameters
    ): ?ConflictResolution {
        if($conflict->getType() === OnConflict::RESOLUTION_ERROR) {
            return null;
        }
        
        $sql = 'ON CONFLICT';
        
        $targets = $conflict->getConflictTargets();
        if(!empty($targets)) {
            if($targets[0] instanceof Constraint) {
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
        
        if($conflict->getType() === OnConflict::RESOLUTION_DO_NOTHING) {
            $sql .= ' DO NOTHING';
        } elseif($conflict->getType() === OnConflict::RESOLUTION_REPLACE_ALL) {
            $sql .= ' DO UPDATE SET';
            
            foreach($columns as $column) {
                $sql .= ' '.$this->quoteColumn($column).' = excluded.'.$column.',';
            }
            
            $sql = \substr($sql, 0, -1);
        } elseif($conflict->getType() === OnConflict::RESOLUTION_REPLACE_COLUMNS) {
            $sql .= ' DO UPDATE SET';
            
            $fields = $conflict->getReplaceColumns();
            if(empty($fields)) {
                throw new Exception('On Conflict resolution REPLACE_COLUMNS has no columns');
            }
            
            foreach($fields as $field) {
                $sql .= ' '.$this->quoteColumn($field).' = excluded.'.$field.',';
            }
            
            $sql = \substr($sql, 0, -1);
        } else {
            throw new Exception('Unknown conflict resolution type'); // @codeCoverageIgnore
        }
        
        return (new ConflictResolution('INSERT INTO', $sql));
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
            case QueryBuilder::ROW_LOCKING_FOR_UPDATE:
                return 'FOR UPDATE';
            case QueryBuilder::ROW_LOCKING_FOR_NO_KEY_UPDATE:
                return 'FOR NO KEY UPDATE';
            case QueryBuilder::ROW_LOCKING_FOR_SHARE:
                return 'FOR SHARE';
            case QueryBuilder::ROW_LOCKING_FOR_KEY_SHARE:
                return 'FOR KEY SHARE';
            default:
                throw new \InvalidArgumentException('Unknown SELECT row-level locking mode');
        }
    }
    
    /**
     * Whether the grammar supports RETURNING.
     * @return bool
     */
    function supportsReturning(): bool {
        return true;
    }
    
    /**
     * Returns the placeholder callable used to replace `?` with the
     * correct placeholder of the grammar.
     * @return callable|null  Returns null, if the grammar uses `?`.
     * @see \Plasma\Utility::parseParameters()
     */
    function getPlaceholderCallable(): ?callable {
        return (static function () {
            static $i;
            
            if(!$i) {
                $i = 0;
            }
            
            return '$'.(++$i);
        });
    }
}
