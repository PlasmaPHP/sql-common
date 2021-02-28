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

use Plasma\Exception;
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Constraint;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

class QueryBuilderInsertTest extends TestCase {
    function testInsert() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertParameter() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new Parameter('hello', true))
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertFragment() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new Fragment('LOWER("abc")'))
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, LOWER("abc"))', $query->getQuery());
        self::assertSame(array(500), $query->getParameters());
    }
    
    function testInsertOnConflictError() {
        $onConflict = new OnConflict(OnConflict::RESOLUTION_ERROR);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictDoNothing() {
        $onConflict = new OnConflict(OnConflict::RESOLUTION_DO_NOTHING);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT DO NOTHING', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictReplaceAll() {
        $onConflict = new OnConflict(OnConflict::RESOLUTION_REPLACE_ALL);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        self::assertSame(
            'INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT DO UPDATE SET "abc" = excluded.abc, "efg" = excluded.efg',
            $query->getQuery()
        );
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictReplaceColumns() {
        $onConflict = (new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS))
            ->addReplaceColumn('abc');
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT DO UPDATE SET "abc" = excluded.abc', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictWithTargets() {
        $onConflict = (new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS))
            ->addReplaceColumn('abc')
            ->addConflictTarget('efg');
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        self::assertSame('INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT "efg" DO UPDATE SET "abc" = excluded.abc', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertOnConflictWithTargetsConstraint() {
        $constraint = new Constraint('efg');
        $onConflict = (new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS))
            ->addReplaceColumn('abc')
            ->addConflictTarget($constraint);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ), array(
                'onConflict' => $onConflict
            ));
        
        self::assertSame(
            'INSERT INTO "tests" ("abc", "efg") VALUES ($1, $2) ON CONFLICT ON CONSTRAINT "efg" DO UPDATE SET "abc" = excluded.abc',
            $query->getQuery()
        );
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertWithSubquery() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insertWithSubquery(
                QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 5)
                    ->select()
            );
        
        self::assertSame('INSERT INTO "tests" VALUES ((SELECT * FROM "abc" WHERE "a" = $1))', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testInsertSelectSubquery() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array('abc' => 5))
            ->subquery(
                QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->select()
            );
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
    
    function testInsertReturning() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array('abc' => 5))
            ->returning();
        
        self::assertSame('INSERT INTO "tests" ("abc") VALUES ($1) RETURNING *', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
}
