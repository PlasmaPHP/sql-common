<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\ColumnDatatypes;

class TimeWithTimeZoneType implements ColumnTypeInterface {
    /**
     * Get the SQL string.
     * @param \Plasma\SQL\GrammarInterface|null       $grammar
     * @param \Plasma\SQL\Blueprints\ColumnBlueprint  $column
     * @return string
     * @throws \LogicException
     */
    static function getSQL(?\Plasma\SQL\GrammarInterface $grammar, \Plasma\SQL\Blueprints\ColumnBlueprint $column): string {
        if(!($grammar instanceof \Plasma\SQL\Grammar\PostgreSQL)) {
            throw new \LogicException('TIME WITH TIME ZONE does only exist in PostgreSQL');
        }
        
        return 'TIME'.($column->getLength() >= 0 ? '('.$column->getLength().')' : '').' WITH TIME ZONE';
    }
}
