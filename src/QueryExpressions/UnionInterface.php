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
 * Internal interface.
 * @internal
 */
interface UnionInterface {
    /**
     * Get the SQL string for this.
     * @param GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?GrammarInterface $grammar): string;
    
    /**
     * Get the parameters.
     * @return Parameter[]
     */
    function getParameters(): array;
}
