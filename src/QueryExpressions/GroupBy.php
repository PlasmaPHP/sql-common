<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

use Plasma\SQL\GrammarInterface;

/**
 * Represents a GROUP BY clause.
 */
class GroupBy {
    /**
     * @var Column
     */
    protected $column;
    
    /**
     * Constructor.
     * @param Column  $column
     */
    function __construct(Column $column) {
        $this->column = $column;
    }
    
    /**
     * Get the column.
     * @return Column
     */
    function getColumn(): Column {
        return $this->column;
    }
    
    /**
     * Get the SQL string for this.
     * @param GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?GrammarInterface $grammar): string {
        if($grammar !== null && $this->column->allowEscape()) {
            $column = $grammar->quoteColumn($this->column);
        } else {
            $column = $this->column;
        }
        
        return $column;
    }
}
