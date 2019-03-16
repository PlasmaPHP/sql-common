<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class TableTest extends \PHPUnit\Framework\TestCase {
    function testGetTable() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', null, false);
        $this->assertSame('abc', $table->getTable());
    }
    
    function testGetAlias() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', null, false);
        $this->assertNull($table->getAlias());
    }
    
    function testGetAlias2() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', 'a', false);
        $this->assertSame('a', $table->getAlias());
    }
    
    function testAllowEscape() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', 'a', true);
        $this->assertTrue($table->allowEscape());
    }
    
    function testAllowEscape2() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', 'a', false);
        $this->assertFalse($table->allowEscape());
    }
    
    function testGetSQL() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', null, false);
        $this->assertSame('abc', $table->getSQL(null));
    }
    
    function testGetSQLWithAlias() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', 'a', false);
        $this->assertSame('abc AS a', $table->getSQL(null));
    }
    
    function testToString() {
        $table = new \Plasma\SQL\QueryExpressions\Table('abc', null, false);
        $this->assertSame('abc', ((string) $table->getSQL(null)));
    }
}
