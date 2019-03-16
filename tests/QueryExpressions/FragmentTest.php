<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class FragmentTest extends \PHPUnit\Framework\TestCase {
    function testAllowEscape2() {
        $frag = new \Plasma\SQL\QueryExpressions\Fragment('abc');
        $this->assertFalse($frag->allowEscape());
    }
    
    function testGetSQL() {
        $frag = new \Plasma\SQL\QueryExpressions\Fragment('abc');
        $this->assertSame('abc', $frag->getSQL());
    }
    
    function testToString() {
        $frag = new \Plasma\SQL\QueryExpressions\Fragment('abc');
        $this->assertSame('abc', ((string) $frag));
    }
}
