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
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

/**
 * MySQL Grammar.
 */
class MySQL implements GrammarInterface {
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
     * MySQL does not support conflict targets.
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
        if(!empty($conflict->getConflictTargets())) {
            throw new Exception('MySQL does not support conflict targets');
        }
        
        if($conflict->getType() === OnConflict::RESOLUTION_ERROR) {
            return null;
        }
        
        if($conflict->getType() === OnConflict::RESOLUTION_DO_NOTHING) {
            return (new ConflictResolution('INSERT IGNORE INTO', ''));
        }
        
        $sql = 'ON DUPLICATE KEY UPDATE';
        
        if($conflict->getType() === OnConflict::RESOLUTION_REPLACE_ALL) {
            foreach($columns as $column) {
                $col = $this->quoteColumn($column);
                $sql .= ' '.$col.' = VALUES('.$col.'),';
            }
            
            $sql = \substr($sql, 0, -1);
        } elseif($conflict->getType() === OnConflict::RESOLUTION_REPLACE_COLUMNS) {
            $fields = $conflict->getReplaceColumns();
            if(empty($fields)) {
                throw new Exception('On Conflict resolution REPLACE_COLUMNS has no columns');
            }
            
            foreach($fields as $field) {
                $col = $this->quoteColumn($field);
                $sql .= ' '.$col.' = VALUES('.$col.'),';
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
            case QueryBuilder::ROW_LOCKING_FOR_NO_KEY_UPDATE:
                return 'FOR UPDATE';
            case QueryBuilder::ROW_LOCKING_FOR_SHARE:
            case QueryBuilder::ROW_LOCKING_FOR_KEY_SHARE:
                return 'FOR SHARE';
            default:
                throw new \InvalidArgumentException('Unknown SELECT row-level locking mode');
        }
    }
    
    /**
     * Whether the grammar supports RETURNING.
     * @return bool
     */
    function supportsReturning(): bool {
        return false;
    }
    
    /**
     * Returns the placeholder callable used to replace `?` with the
     * correct placeholder of the grammar.
     * @return callable|null  Returns null, if the grammar uses `?`.
     */
    function getPlaceholderCallable(): ?callable {
        return null;
    }
}
