<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests;

class QueryBuilderInsertTest extends \PHPUnit\Framework\TestCase {
    function testInsert() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        $this->assertSame('INSERT INTO tests (abc, efg) VALUES (?, ?)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertParameter() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Parameter('hello', true))
            ));
        
        $this->assertSame('INSERT INTO tests (abc, efg) VALUES (?, ?)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertFragment() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Fragment('LOWER(abc)'))
            ));
        
        $this->assertSame('INSERT INTO tests (abc, efg) VALUES (?, LOWER(abc))', $query->getQuery());
        $this->assertSame(array(500), $query->getParameters());
    }
    
    function testInsertOnConflictUnsupported() {
        $onConflict = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_ERROR);
        
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->expectException(\Plasma\Exception::class);
        $query->getQuery();
    }
    
    function testInsertOnConflictInvalidArg() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests');
        
        $this->expectException(\InvalidArgumentException::class);
        $query->insert(array(
            'abc' => 500,
            'efg' => 'hello'
        ), array(
            'onConflict' => true
        ));
    }
    
    function testInsertWithSubquery() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insertWithSubquery(
                \Plasma\SQL\QueryBuilder::create()
                    ->from('abc')
                    ->where('a', '=', 5)
                    ->select()
            );
        
        $this->assertSame('INSERT INTO tests VALUES ((SELECT * FROM abc WHERE a = ?))', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testInsertSelectSubquery() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insert(array('abc' => 5))
            ->subquery(\Plasma\SQL\QueryBuilder::create()
                ->from('abc')
                ->select()
            );
        
        $this->expectException(\Plasma\Exception::class);
        $query->getQuery();
    }
    
    function testReturningUnsupported() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->into('tests')
            ->insert(array('abc' => 5))
            ->returning();
        
        $this->expectException(\Plasma\Exception::class);
        $query->getQuery();
    }
    
    function testPrefix() {
        $query = \Plasma\SQL\QueryBuilder::create()
            ->from('tests')
            ->setPrefix('abc')
            ->insert(array('abc' => 5));
        
            $this->assertSame('INSERT INTO abc.tests (abc) VALUES (?)', $query->getQuery());
            $this->assertSame(array(5), $query->getParameters());
    }
}
