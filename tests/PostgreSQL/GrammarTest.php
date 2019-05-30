<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\PostgreSQL;

class GrammarTest extends TestCase {
    function testQuoteTable() {
        $this->assertSame('"abc"', $this->grammar->quoteTable('abc'));
    }
    
    function testQuoteTableUnquoted() {
        $this->assertSame('a.abc', $this->grammar->quoteTable('a.abc'));
    }
    
    function testQuoteColumn() {
        $this->assertSame('"abc"', $this->grammar->quoteColumn('abc'));
    }
    
    function testQuoteColumnUnquoted() {
        $this->assertSame('abc()', $this->grammar->quoteColumn('abc()'));
    }
    
    function testOnConflictWithMultiCols() {
        $conflict = (new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_ALL))
            ->addConflictTarget('abc')
            ->addConflictTarget('efg');
        
        $cols = array(
            'a' => (new \Plasma\SQL\QueryExpressions\Column('a', null, false))
        );
        $params = array(
            (new \Plasma\SQL\QueryExpressions\Parameter(1, true))
        );
        
        $expected = new \Plasma\SQL\ConflictResolution('INSERT INTO', 'ON CONFLICT (abc, efg) DO UPDATE SET "a" = excluded.a');
        $actual = $this->grammar->onConflictToSQL(\Plasma\SQL\QueryBuilder::create()->into('a')->insert(array('b' => 1)), $conflict, $cols, $params);
        
        $this->assertEquals($expected, $actual);
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
        $this->assertTrue($this->grammar->supportsReturning());
    }
    
    function testGetPlaceholderCallable() {
        $cb = $this->grammar->getPlaceholderCallable();
        $this->assertInternalType('callable', $cb);
        
        $this->assertSame('$1', $cb());
        $this->assertSame('$2', $cb());
        $this->assertSame('$3', $cb());
    }
}
