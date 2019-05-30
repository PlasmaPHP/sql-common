<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\PostgreSQL;

class QueryBuilderInsertTest extends TestCase {
    function testInsert() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertParameter() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Parameter('hello', true))
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertFragment() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Fragment('LOWER("abc")'))
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, LOWER("abc"))', $query->getQuery());
        $this->assertSame(array(500), $query->getParameters());
    }
    
    function testInsertOnConflictError() {
        $onConflict = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_ERROR);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictDoNothing() {
        $onConflict = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_DO_NOTHING);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT DO NOTHING', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictReplaceAll() {
        $onConflict = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_ALL);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT DO UPDATE SET "abc" = excluded.abc, "efg" = excluded.efg', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictReplaceColumns() {
        $onConflict = (new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS))
            ->addReplaceColumn('abc');
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT DO UPDATE SET "abc" = excluded.abc', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictWithTargets() {
        $onConflict = (new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS))
            ->addReplaceColumn('abc')
            ->addConflictTarget('efg');
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT "efg" DO UPDATE SET "abc" = excluded.abc', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictWithTargetsConstraint() {
        $constraint = new \Plasma\SQL\QueryExpressions\Constraint('efg');
        $onConflict = (new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS))
            ->addReplaceColumn('abc')
            ->addConflictTarget($constraint);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT ON CONSTRAINT "efg" DO UPDATE SET "abc" = excluded.abc', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertWithSubquery() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insertWithSubquery(
                \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 5)
                    ->select()
            );
        
        $this->assertSame('INSERT INTO "tests" VALUES ((SELECT * FROM "abc" WHERE "a" = $1))', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testInsertSelectSubquery() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array('abc' => 5))
            ->subquery(\Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
                ->from('abc')
                ->select()
            );
        
        $this->expectException(\Plasma\Exception::class);
        $query->getQuery();
    }
    
    function testInsertReturning() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array('abc' => 5))
            ->returning();
        
        $this->assertSame('INSERT INTO "tests" ("abc") VALUES ($1) RETURNING *', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
}
