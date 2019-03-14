<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class Column {
    /**
     * @var string|Fragment
     */
    protected $column;
    
    /**
     * @var string|null
     */
    protected $alias;
    
    /**
     * @var string[]
     */
    protected $columnsNoEscape = array(
        'TABLE_SCHEMA',
        'TABLE_NAME',
        'COLUMN_NAME'
    );
    
    /**
     * Constructor.
     * @param string|Fragment  $column
     * @param string|null      $alias
     * @param bool             $allowEscape
     * @param string           $escapeCharacter
     */
    function __construct($column, ?string $alias, bool $allowEscape, string $escapeCharacter) {
        $this->alias = $alias;
        
        if(
            $allowEscape &&
            \is_string($column) &&
            \strpos($column, '.') === false && // Schema / database access
            \strpos($column, '(') === false && // SQL function call
            \strpos($column, '->') === false && // JSON1 extension
            !\in_array($column, $this->columnsNoEscape, true)
        ) {
            $this->column = $escapeCharacter.$column.$escapeCharacter;
        } else {
            $this->column = $column;
        }
    }
    
    /**
     * Get the column.
     * @return string
     */
    function getColumn(): string {
        return $this->column;
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
        return $this->column.($this->as ? ' AS '.$this->as : '');
    }
    
    /**
     * Turns the expression into a SQL string.
     * @return string
     */
    function __toString(): string {
        return $this->getSQL();
    }
}
