<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests\MySQL;

use Plasma\Exception;
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryBuilder;

class GrammarTest extends TestCase {
    function testQuoteTable() {
        self::assertSame('`abc`', $this->grammar->quoteTable('abc'));
    }
    
    function testQuoteTableUnquoted() {
        self::assertSame('a.abc', $this->grammar->quoteTable('a.abc'));
    }
    
    function testQuoteColumn() {
        self::assertSame('`abc`', $this->grammar->quoteColumn('abc'));
    }
    
    function testQuoteColumnUnquoted() {
        self::assertSame('abc()', $this->grammar->quoteColumn('abc()'));
    }
    
    function testOnConflictWithTargets() {
        $conflict = (new OnConflict(OnConflict::RESOLUTION_ERROR))
            ->addConflictTarget('abc');
        
        $this->expectException(Exception::class);
        $this->grammar->onConflictToSQL(QueryBuilder::create(), $conflict, array(), array());
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
        self::assertFalse($this->grammar->supportsReturning());
    }
    
    function testGetPlaceholderCallable() {
        self::assertNull($this->grammar->getPlaceholderCallable());
    }
}
