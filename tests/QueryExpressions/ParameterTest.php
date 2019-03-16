<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class ParameterTest extends \PHPUnit\Framework\TestCase {
    function testNoValue() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter();
        $this->assertFalse($parameter->hasValue());
        $this->assertNull($parameter->getValue());
    }
    
    function testValue() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(false, true);
        $this->assertTrue($parameter->hasValue());
        $this->assertFalse($parameter->getValue());
    }
    
    function testSetValue() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter();
        $parameter->setValue(500);
        
        $this->assertTrue($parameter->hasValue());
        $this->assertSame(500, $parameter->getValue());
    }
}
