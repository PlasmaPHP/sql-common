<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class OrderByTest extends \PHPUnit\Framework\TestCase {
    function testGetColumn() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $order = new \Plasma\SQL\QueryExpressions\OrderBy($col, false);
        $this->assertSame($col, $order->getColumn());
    }
    
    function testIsDescending() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $order = new \Plasma\SQL\QueryExpressions\OrderBy($col, false);
        $this->assertFalse($order->isDescending());
    }
    
    function testIsDescending2() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $order = new \Plasma\SQL\QueryExpressions\OrderBy($col, true);
        $this->assertTrue($order->isDescending());
    }
    
    function testGetSQL() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $order = new \Plasma\SQL\QueryExpressions\OrderBy($col, false);
        $this->assertSame('abc ASC', $order->getSQL(null));
    }
    
    function testGetSQL2() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $order = new \Plasma\SQL\QueryExpressions\OrderBy($col, true);
        $this->assertSame('abc DESC', $order->getSQL(null));
    }
}
