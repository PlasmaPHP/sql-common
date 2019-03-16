<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\MySQL;

class GrammarTest extends TestCase {
    function testQuoteTable() {
        $this->assertSame('`abc`', $this->grammar->quoteTable('abc'));
    }
    
    function testQuoteTableUnquoted() {
        $this->assertSame('a.abc', $this->grammar->quoteTable('a.abc'));
    }
    
    function testQuoteColumn() {
        $this->assertSame('`abc`', $this->grammar->quoteColumn('abc'));
    }
    
    function testQuoteColumnUnquoted() {
        $this->assertSame('abc()', $this->grammar->quoteColumn('abc()'));
    }
    
    function testOnConflictWithTargets() {
        $conflict = (new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_ERROR))
            ->addConflictTarget('abc');
        
        $this->expectException(\Plasma\Exception::class);
        $this->grammar->onConflictToSQL(\Plasma\SQL\QueryBuilder::create(), $conflict, array(), array());
    }
    
    function testOnConflictReplaceColumnsWithNoColumns() {
        $conflict = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\Plasma\Exception::class);
        $this->grammar->onConflictToSQL(\Plasma\SQL\QueryBuilder::create(), $conflict, array(), array());
    }
    
    function testSupportsRowLocking() {
        $this->assertTrue($this->grammar->supportsRowLocking());
    }
    
    function testGetSQLForRowLockingUnknown() {
        $this->expectException(\Plasma\Exception::class);
        $this->grammar->getSQLForRowLocking(0);
    }
    
    function testSupportsReturning() {
        $this->assertFalse($this->grammar->supportsReturning());
    }
}
