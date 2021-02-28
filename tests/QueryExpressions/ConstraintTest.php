<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests\QueryExpressions;

use PHPUnit\Framework\TestCase;
use Plasma\SQL\QueryExpressions\Constraint;

class ConstraintTest extends TestCase {
    function testGetName() {
        $con = new Constraint('abc');
        self::assertSame('abc', $con->getName());
    }
    
    function testGetIdentifier() {
        $con = new Constraint('abcd');
        self::assertSame('abcd', $con->getIdentifier());
    }
}
