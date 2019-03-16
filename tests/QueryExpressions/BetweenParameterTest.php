<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class BetweenParameterTest extends \PHPUnit\Framework\TestCase {
    function testHasValue() {
        $para1 = new \Plasma\SQL\QueryExpressions\Parameter();
        $para2 = new \Plasma\SQL\QueryExpressions\Parameter();
        
        $between = new \Plasma\SQL\QueryExpressions\BetweenParameter($para1, $para2);
        $this->assertTrue($between->hasValue());
    }
    
    function testGetValue() {
        $para1 = new \Plasma\SQL\QueryExpressions\Parameter();
        $para2 = new \Plasma\SQL\QueryExpressions\Parameter();
        
        $between = new \Plasma\SQL\QueryExpressions\BetweenParameter($para1, $para2);
        $this->assertSame(array($para1, $para2), $between->getValue());
    }
    
    function testSetValue() {
        $para1 = new \Plasma\SQL\QueryExpressions\Parameter();
        $para2 = new \Plasma\SQL\QueryExpressions\Parameter();
        
        $between = new \Plasma\SQL\QueryExpressions\BetweenParameter($para1, $para2);
        
        $this->expectException(\LogicException::class);
        $between->setValue(null);
    }
}
