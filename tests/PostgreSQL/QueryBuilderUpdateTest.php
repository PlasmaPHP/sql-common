<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests\PostgreSQL;

use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

class QueryBuilderUpdateTest extends TestCase {
    function testUpdate() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testUpdateParameter() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => (new Parameter('hello', true))
            ));
        
        self::assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testUpdateFragment() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->update(array(
                'abc' => 500,
                'efg' => (new Fragment('LOWER("abc")'))
            ));
        
        self::assertSame('UPDATE "tests" SET "abc" = $1, "efg" = LOWER("abc")', $query->getQuery());
        self::assertSame(array(500), $query->getParameters());
    }
    
    function testUpdateWhere() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2 WHERE "abc" IS NULL OR "efg" = $3', $query->getQuery());
        self::assertSame(array(500, 'hello', 250), $query->getParameters());
    }
    
    function testUpdateReturning() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->update(array(
                'abc' => 500,
                'efg' => 'hello'
            ))
            ->returning();
        
        self::assertSame('UPDATE "tests" SET "abc" = $1, "efg" = $2 WHERE "abc" IS NULL OR "efg" = $3 RETURNING *', $query->getQuery());
        self::assertSame(array(500, 'hello', 250), $query->getParameters());
    }
}
