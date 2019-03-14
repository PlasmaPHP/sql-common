<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class Table {
    /**
     * @var string
     */
    protected $table;
    
    /**
     * @var string|null
     */
    protected $alias;
    
    /**
     * @var string[]
     */
    protected $tablesNoEscape = array(
        'INFORMATION_SCHEMA.TABLES',
        'INFORMATION_SCHEMA.COLUMNS'
    );
    
    /**
     * Constructor.
     * @param string       $table
     * @param string|null  $alias
     * @param bool         $allowEscape
     * @param string       $escapeCharacter
     */
    function __construct(string $table, ?string $alias, bool $allowEscape, string $escapeCharacter) {
        $this->alias = $alias;
        
        if($allowEscape && \strpos($table, '.') === false && !\in_array($table, $this->tablesNoEscape, true)) {
            $this->table = $escapeCharacter.$table.$escapeCharacter;
        } else {
            $this->table = $table;
        }
    }
    
    /**
     * Get the table.
     * @return string
     */
    function getTable(): string {
        return $this->table;
    }
    
    /**
     * Get the alias.
     * @return string|null
     */
    function getAlias(): ?string {
        return $this->alias;
    }
    
    /**
     * Get the SQL string for this.
     * @return string
     */
    function getSQL(): string {
        return $this->table.($this->as ? ' AS '.$this->as : '');
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL();
    }
}
