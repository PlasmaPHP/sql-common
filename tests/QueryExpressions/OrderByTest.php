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
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\OrderBy;

class OrderByTest extends TestCase {
    function testGetColumn() {
        $col = new Column('abc', null, true);
        $order = new OrderBy($col, false);
        self::assertSame($col, $order->getColumn());
    }
    
    function testIsDescending() {
        $col = new Column('abc', null, true);
        $order = new OrderBy($col, false);
        self::assertFalse($order->isDescending());
    }
    
    function testIsDescending2() {
        $col = new Column('abc', null, true);
        $order = new OrderBy($col, true);
        self::assertTrue($order->isDescending());
    }
    
    function testGetSQL() {
        $col = new Column('abc', null, true);
        $order = new OrderBy($col, false);
        self::assertSame('abc ASC', $order->getSQL(null));
    }
    
    function testGetSQL2() {
        $col = new Column('abc', null, true);
        $order = new OrderBy($col, true);
        self::assertSame('abc DESC', $order->getSQL(null));
    }
}
