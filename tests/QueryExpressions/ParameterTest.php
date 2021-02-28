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
use Plasma\SQL\QueryExpressions\Parameter;

class ParameterTest extends TestCase {
    function testNoValue() {
        $parameter = new Parameter();
        self::assertFalse($parameter->hasValue());
        self::assertNull($parameter->getValue());
    }
    
    function testValue() {
        $parameter = new Parameter(false, true);
        self::assertTrue($parameter->hasValue());
        self::assertFalse($parameter->getValue());
    }
    
    function testSetValue() {
        $parameter = new Parameter();
        $parameter->setValue(500);
        
        self::assertTrue($parameter->hasValue());
        self::assertSame(500, $parameter->getValue());
    }
}
