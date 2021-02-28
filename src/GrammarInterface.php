<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

/**
 * Grammar describe a SQL flavour, most notably MySQL vs. PostgreSQL.
 */
interface GrammarInterface {
    /**
     * Wraps the table name into quotes.
     * @param string  $table
     * @return string
     */
    function quoteTable(string $table): string;
    
    /**
     * Wraps the column name into quotes.
     * @param string  $column
     * @return string
     */
    function quoteColumn(string $column): string;
    
    /**
     * Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.
     * @param QueryBuilder                                           $query
     * @param OnConflict                                             $conflict
     * @param QueryExpressions\Column[]|QueryExpressions\Fragment[]  $columns
     * @param QueryExpressions\Parameter[]                           $parameters
     * @return ConflictResolution|null
     */
    function onConflictToSQL(
        QueryBuilder $query,
        OnConflict $conflict,
        array $columns,
        array $parameters
    ): ?ConflictResolution;
    
    /**
     * Whether the grammar supports row-level locking.
     * @return bool
     */
    function supportsRowLocking(): bool;
    
    /**
     * Get the SQL command for the given row-level locking mode.
     * @param int  $lock
     * @return string
     * @throws \InvalidArgumentException
     */
    function getSQLForRowLocking(int $lock): string;
    
    /**
     * Whether the grammar supports RETURNING.
     * @return bool
     */
    function supportsReturning(): bool;
    
    /**
     * Returns the placeholder callable used to replace `?` with the
     * correct placeholder of the grammar.
     * @return callable|null  Returns null, if the grammar uses `?`.
     * @see \Plasma\Utility::parseParameters()
     */
    function getPlaceholderCallable(): ?callable;
}
