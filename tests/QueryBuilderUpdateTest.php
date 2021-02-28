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
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

class QueryBuilderUpdateTest extends TestCase {
    function testUpdate() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('UPDATE tests SET abc = ?, efg = ?', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testUpdateParameter() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => (new Parameter('hello', true))
            ));
        
        self::assertSame('UPDATE tests SET abc = ?, efg = ?', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testUpdateFragment() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => (new Fragment('LOWER(abc)'))
            ));
        
        self::assertSame('UPDATE tests SET abc = ?, efg = LOWER(abc)', $query->getQuery());
        self::assertSame(array(500), $query->getParameters());
    }
    
    function testUpdateWhere() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('UPDATE tests SET abc = ?, efg = ? WHERE abc IS NULL OR efg = ?', $query->getQuery());
        self::assertSame(array(500, 'hello', 250), $query->getParameters());
    }
    
    function testReturningUnsupported() {
        $query = QueryBuilder::create()
            ->into('tests')
            ->update(array('abc' => 5))
            ->returning();
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
    
    function testPrefix() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->setPrefix('abc')
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('UPDATE abc.tests SET abc = ?, efg = ?', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
}
