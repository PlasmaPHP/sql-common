<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\PostgreSQL;

class TestCase extends \PHPUnit\Framework\TestCase {
    /**
     * @var \Plasma\SQL\GrammarInterface
     */
    protected $grammar;
    
    function setUp() {
        $this->grammar = new \Plasma\SQL\Grammar\PostgreSQL();
    }
}
