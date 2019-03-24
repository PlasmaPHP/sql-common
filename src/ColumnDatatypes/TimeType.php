<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\ColumnDatatypes;

class TimeType implements ColumnTypeInterface {
    /**
     * Get the SQL string.
     * @param \Plasma\SQL\GrammarInterface|null       $grammar
     * @param \Plasma\SQL\Blueprints\ColumnBlueprint  $column
     * @return string
     */
    static function getSQL(?\Plasma\SQL\GrammarInterface $grammar, \Plasma\SQL\Blueprints\ColumnBlueprint $column): string {
        return 'TIME'.($column->getLength() >= 0 ? '('.$column->getLength().')' : '');
    }
}
