<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\PostgreSQL;

class QueryBuilderUpdateTest extends TestCase {
    function testUpdate() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        $this->assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testUpdateParameter() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Parameter('hello', true))
            ));
        
        $this->assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testUpdateFragment() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Fragment('LOWER("abc")'))
            ));
        
        $this->assertSame('UPDATE "tests" SET "abc" = $1, "efg" = LOWER("abc")', $query->getQuery());
        $this->assertSame(array(500), $query->getParameters());
    }
    
    function testUpdateWhere() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        $this->assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2 WHERE "abc" IS NULL OR "efg" = $3', $query->getQuery());
        $this->assertSame(array(500, 'hello', 250), $query->getParameters());
    }
    
    function testUpdateReturning() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ))
            ->returning();
        
        $this->assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2 WHERE "abc" IS NULL OR "efg" = $3 RETURNING *', $query->getQuery());
        $this->assertSame(array(500, 'hello', 250), $query->getParameters());
    }
}
