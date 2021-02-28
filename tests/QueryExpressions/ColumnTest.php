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

class ColumnTest extends TestCase {
    function testGetColumn() {
        $col = new Column('abc', null, true);
        self::assertSame('abc', $col->getColumn());
    }
    
    function testGetAlias() {
        $col = new Column('abc', null, true);
        self::assertNull($col->getAlias());
    }
    
    function testGetAlias2() {
        $col = new Column('abc', 'a', true);
        self::assertSame('a', $col->getAlias());
    }
    
    function testAllowEscape() {
        $col = new Column('abc', null, true);
        self::assertTrue($col->allowEscape());
    }
    
    function testAllowEscape2() {
        $col = new Column('abc', null, false);
        self::assertFalse($col->allowEscape());
    }
    
    function testGetSQL() {
        $col = new Column('abc', null, true);
        self::assertSame('abc', $col->getSQL(null));
    }
    
    function testGetSQLWithAlias() {
        $col = new Column('abc', 'a', true);
        self::assertSame('abc AS a', $col->getSQL(null));
    }
    
    function testGetIdentifier() {
        $col = new Column('abc', null, true);
        self::assertSame('abc', $col->getIdentifier());
    }
}
