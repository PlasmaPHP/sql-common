<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\PostgreSQL;

class QueryBuilderDeleteTest extends TestCase {
    function testDelete() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->delete();
        
        $this->assertSame('DELETE FROM "tests"', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testDeleteWhere() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->delete();
        
        $this->assertSame('DELETE FROM "tests" WHERE "abc" IS NULL OR "efg" = ?', $query->getQuery());
        $this->assertSame(array(250), $query->getParameters());
    }
    
    function testDeleteReturning() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->delete()
            ->returning();
        
        $this->assertSame('DELETE FROM "tests" WHERE "abc" IS NULL OR "efg" = ? RETURNING *', $query->getQuery());
        $this->assertSame(array(250), $query->getParameters());
    }
}
