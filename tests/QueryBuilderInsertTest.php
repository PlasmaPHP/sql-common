<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests;

use PHPUnit\Framework\TestCase;
use Plasma\Exception;
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

class QueryBuilderInsertTest extends TestCase {
    function testInsert() {
        $query = QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('INSERT INTO tests (abc, efg) VALUES (?, ?)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertParameter() {
        $query = QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new Parameter('hello', true))
            ));
        
        self::assertSame('INSERT INTO tests (abc, efg) VALUES (?, ?)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertFragment() {
        $query = QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new Fragment('LOWER(abc)'))
            ));
        
        self::assertSame('INSERT INTO tests (abc, efg) VALUES (?, LOWER(abc))', $query->getQuery());
        self::assertSame(array(500), $query->getParameters());
    }
    
    function testInsertOnConflictUnsupported() {
        $onConflict = new OnConflict(OnConflict::RESOLUTION_ERROR);
        
        $query = QueryBuilder::create()
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
    
    function testInsertOnConflictInvalidArg() {
        $query = QueryBuilder::create()
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
        $query = QueryBuilder::create()
            ->into('tests')
            ->insertWithSubquery(
                QueryBuilder::create()
                    ->from('abc')
                    ->where('a', '=', 5)
                    ->select()
            );
        
        self::assertSame('INSERT INTO tests VALUES ((SELECT * FROM abc WHERE a = ?))', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testInsertSelectSubquery() {
        $query = QueryBuilder::create()
            ->into('tests')
            ->insert(array('abc' => 5))
            ->subquery(
                QueryBuilder::create()
                    ->from('abc')
                    ->select()
            );
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
    
    function testReturningUnsupported() {
        $query = QueryBuilder::create()
            ->into('tests')
            ->insert(array('abc' => 5))
            ->returning();
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
    
    function testPrefix() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->setPrefix('abc')
            ->insert(array('abc' => 5));
        
        self::assertSame('INSERT INTO abc.tests (abc) VALUES (?)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
}
