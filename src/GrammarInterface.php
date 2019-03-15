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
     * @param string  $table
     * @return string
     */
    function quoteColumn(string $column): string;
    
    /**
     * Converts an ON CONFLICT resolution to the equivalent DBMS-specific SQL string.
     * @param \Plasma\SQL\QueryBuilder                               $query
     * @param \Plasma\SQL\OnConflict                                 $conflict
     * @param QueryExpressions\Column[]|QueryExpressions\Fragment[]  $columns
     * @param QueryExpressions\Parameter[]                           $parameters
     * @return \Plasma\SQL\ConflictResolution
     */
    function onConflictToSQL(
        \Plasma\SQL\QueryBuilder $query,
        \Plasma\SQL\OnConflict $conflict,
        array $columns,
        array $parameters
    ): \Plasma\SQL\ConflictResolution;
}
