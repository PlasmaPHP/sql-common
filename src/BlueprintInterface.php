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
 * Describes how to interact with standard blueprints.
 */
interface BlueprintInterface {
    /**
     * Creates a new blueprint.
     * @return self
     */
    static function create();
    
    /**
     * Turns the blueprint into a SQL query.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \RuntimeException
     * @throws \Plasma\Exception
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string;
}
