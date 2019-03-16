<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class OrderBy {
    /**
     * @var \Plasma\SQL\QueryExpressions\Column
     */
    protected $column;
    
    /**
     * @var bool
     */
    protected $desc;
    
    /**
     * Constructor.
     * @param \Plasma\SQL\QueryExpressions\Column  $column
     * @param bool                                 $desc
     */
    function __construct(\Plasma\SQL\QueryExpressions\Column $column, bool $desc) {
        $this->column = $column;
        $this->desc = $desc;
    }
    
    /**
     * Get the column.
     * @return \Plasma\SQL\QueryExpressions\Column
     */
    function getColumn(): \Plasma\SQL\QueryExpressions\Column {
        return $this->column;
    }
    
    /**
     * Whether the sorting is descending.
     * @return bool
     */
    function isDescending(): bool {
        return $this->desc;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        if($grammar !== null && $this->column->allowEscape()) {
            $column = $grammar->quoteColumn($this->column);
        } else {
            $column = $this->column;
        }
        
        return $column.' '.($this->desc ? 'DESC' : 'ASC');
    }
}
