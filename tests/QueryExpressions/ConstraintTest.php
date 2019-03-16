<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class ConstraintTest extends \PHPUnit\Framework\TestCase {
    function testGetName() {
        $con = new \Plasma\SQL\QueryExpressions\Constraint('abc');
        $this->assertSame('abc', $con->getName());
    }
    
    function testGetIdentifier() {
        $con = new \Plasma\SQL\QueryExpressions\Constraint('abcd');
        $this->assertSame('abcd', $con->getIdentifier());
    }
}
