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
use Plasma\SQL\QueryExpressions\Table;

class TableTest extends TestCase {
    function testGetTable() {
        $table = new Table('abc', null, false);
        self::assertSame('abc', $table->getTable());
    }
    
    function testGetAlias() {
        $table = new Table('abc', null, false);
        self::assertNull($table->getAlias());
    }
    
    function testGetAlias2() {
        $table = new Table('abc', 'a', false);
        self::assertSame('a', $table->getAlias());
    }
    
    function testAllowEscape() {
        $table = new Table('abc', 'a', true);
        self::assertTrue($table->allowEscape());
    }
    
    function testAllowEscape2() {
        $table = new Table('abc', 'a', false);
        self::assertFalse($table->allowEscape());
    }
    
    function testGetSQL() {
        $table = new Table('abc', null, false);
        self::assertSame('abc', $table->getSQL(null));
    }
    
    function testGetSQLWithAlias() {
        $table = new Table('abc', 'a', false);
        self::assertSame('abc AS a', $table->getSQL(null));
    }
    
    function testToString() {
        $table = new Table('abc', null, false);
        self::assertSame('abc', ($table->getSQL(null)));
    }
}
