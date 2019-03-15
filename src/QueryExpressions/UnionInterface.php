<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

/**
 * Internal interface.
 * @internal
 */
interface UnionInterface {
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string;
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array;
}
