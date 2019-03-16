<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class ColumnTest extends \PHPUnit\Framework\TestCase {
    function testGetColumn() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $this->assertSame('abc', $col->getColumn());
    }
    
    function testGetAlias() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $this->assertNull($col->getAlias());
    }
    
    function testGetAlias2() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', 'a', true);
        $this->assertSame('a', $col->getAlias());
    }
    
    function testAllowEscape() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $this->assertTrue($col->allowEscape());
    }
    
    function testAllowEscape2() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, false);
        $this->assertFalse($col->allowEscape());
    }
    
    function testGetSQL() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $this->assertSame('abc', $col->getSQL(null));
    }
    
    function testGetSQLWithAlias() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', 'a', true);
        $this->assertSame('abc AS a', $col->getSQL(null));
    }
    
    function testGetIdentifier() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $this->assertSame('abc', $col->getIdentifier());
    }
}
