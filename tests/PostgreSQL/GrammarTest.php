<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests\PostgreSQL;

use Plasma\Exception;
use Plasma\SQL\ConflictResolution;
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Parameter;

class GrammarTest extends TestCase {
    function testQuoteTable() {
        self::assertSame('"abc"', $this->grammar->quoteTable('abc'));
    }
    
    function testQuoteTableUnquoted() {
        self::assertSame('a.abc', $this->grammar->quoteTable('a.abc'));
    }
    
    function testQuoteColumn() {
        self::assertSame('"abc"', $this->grammar->quoteColumn('abc'));
    }
    
    function testQuoteColumnUnquoted() {
        self::assertSame('abc()', $this->grammar->quoteColumn('abc()'));
    }
    
    function testOnConflictWithMultiCols() {
        $conflict = (new OnConflict(OnConflict::RESOLUTION_REPLACE_ALL))
            ->addConflictTarget('abc')
            ->addConflictTarget('efg');
        
        $cols = array(
            'a' => (new Column('a', null, false))
        );
        $params = array(
            (new Parameter(1, true))
        );
        
        $expected = new ConflictResolution('INSERT INTO', 'ON CONFLICT (abc, efg) DO UPDATE SET "a" = excluded.a');
        $actual = $this->grammar->onConflictToSQL(QueryBuilder::create()->into('a')->insert(array('b' => 1)), $conflict, $cols, $params);
        
        self::assertEquals($expected, $actual);
    }
    
    function testOnConflictReplaceColumnsWithNoColumns() {
        $conflict = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(Exception::class);
        $this->grammar->onConflictToSQL(QueryBuilder::create(), $conflict, array(), array());
    }
    
    function testSupportsRowLocking() {
        self::assertTrue($this->grammar->supportsRowLocking());
    }
    
    function testGetSQLForRowLockingUnknown() {
        $this->expectException(\InvalidArgumentException::class);
        $this->grammar->getSQLForRowLocking(0);
    }
    
    function testSupportsReturning() {
        self::assertTrue($this->grammar->supportsReturning());
    }
    
    function testGetPlaceholderCallable() {
        $cb = $this->grammar->getPlaceholderCallable();
        self::assertIsCallable($cb);
        
        self::assertSame('$1', $cb());
        self::assertSame('$2', $cb());
        self::assertSame('$3', $cb());
    }
}
