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
use Plasma\SQL\QueryExpressions\BetweenParameter;
use Plasma\SQL\QueryExpressions\Parameter;

class BetweenParameterTest extends TestCase {
    function testHasValue() {
        $para1 = new Parameter();
        $para2 = new Parameter();
        
        $between = new BetweenParameter($para1, $para2);
        self::assertTrue($between->hasValue());
    }
    
    function testGetValue() {
        $para1 = new Parameter();
        $para2 = new Parameter();
        
        $between = new BetweenParameter($para1, $para2);
        self::assertSame(array($para1, $para2), $between->getValue());
    }
    
    function testSetValue() {
        $para1 = new Parameter();
        $para2 = new Parameter();
        
        $between = new BetweenParameter($para1, $para2);
        
        $this->expectException(\LogicException::class);
        $between->setValue(null);
    }
}
