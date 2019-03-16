<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests;

class QueryBuilderDeleteTest extends \PHPUnit\Framework\TestCase {
    function testDelete() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->from('tests')
            ->delete();
        
        $this->assertSame('DELETE FROM tests', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testDeleteWhere() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->from('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->delete();
        
        $this->assertSame('DELETE FROM tests WHERE abc IS NULL OR efg = ?', $query->getQuery());
        $this->assertSame(array(250), $query->getParameters());
    }
    
    function testReturningUnsupported() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->delete()
            ->returning();
        
        $this->expectException(\Plasma\Exception::class);
        $query->getQuery();
    }
}
