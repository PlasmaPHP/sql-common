<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

class GroupBy {
    /**
     * @var \Plasma\SQL\QueryExpressions\Column
     */
    protected $column;
    
    /**
     * Constructor.
     * @param \Plasma\SQL\QueryExpressions\Column  $column
     */
    function __construct(\Plasma\SQL\QueryExpressions\Column $column) {
        $this->column = $column;
    }
    
    /**
     * Get the column.
     * @return \Plasma\SQL\QueryExpressions\Column
     */
    function getColumn(): \Plasma\SQL\QueryExpressions\Column {
        return $this->column;
    }
}
