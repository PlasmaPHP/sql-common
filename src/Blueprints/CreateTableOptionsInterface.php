<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Blueprints;

/**
 * Describes how `CREATE TABLE` options implementation has to act.
 */
interface CreateTableOptionsInterface {
    /**
     * Creates a new `CREATE TABLE` options.
     * @return self
     */
    static function create();
    
    /**
     * Turns the options into a SQL query.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \RuntimeException
     * @throws \Plasma\Exception
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string;
}
