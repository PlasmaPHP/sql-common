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
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\WhereBuilder;

class QueryBuilderSelectTest extends TestCase {
    function testCreateAndGrammar() {
        $query = QueryBuilder::create();
        $query2 = $query->withGrammar($this->grammar);
        
        self::assertNotSame($query, $query2);
    }
    
    function testTable() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->select('*');
        
        self::assertSame('SELECT * FROM "tests"', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testTableColumns() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->select(array('uid', 'created_at'));
        
        self::assertSame('SELECT "uid", "created_at" FROM "tests"', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testJoin() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->join('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" JOIN "test" AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testInnerJoin() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->innerJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" INNER JOIN "test" AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOuterJoin() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->outerJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" OUTER JOIN "test" AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testLeftJoin() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->leftJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" LEFT JOIN "test" AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRightJoin() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->rightJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" RIGHT JOIN "test" AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testJoinOn() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->join('test', 'a')
            ->on('tests.uid', 'a.abc')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" JOIN "test" AS a ON tests.uid = a.abc', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOnMissingJoin() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->on('tests.uid', 'a.abc');
    }
    
    function testJoinOn2() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->join('test', 'a')
            ->on('tests.uid', 'a.abc')
            ->on('tests.ab', 'a.abc')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" JOIN "test" AS a ON tests.uid = a.abc AND tests.ab = a.abc', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testWhere() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" = $1', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testWhereAnd() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->where('created_at', '<', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" = $1 AND "created_at" < $2', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testWhereOr() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->orWhere('created_at', '>', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" = $1 OR "created_at" > $2', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testWhereExt() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->whereExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '>', 2018)
                    ->or('created_at', '<', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" = $1 AND ("created_at" > $2 OR "created_at" < $3)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testWhereExtEmpty() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->whereExt(static function (WhereBuilder $where) {});
    }
    
    function testOrWhereExt() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->orWhereExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '<', 2018)
                    ->and('created_at', '>', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" = $1 OR ("created_at" < $2 AND "created_at" > $3)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testOrWhereExtEmpty() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->orWhereExt(static function (WhereBuilder $where) {});
    }
    
    function testWhereFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->whereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE EXISTS("uid" = $1)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testWhereFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->whereFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testWhereFragment2() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '<=', 5)
            ->whereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" <= $1 AND EXISTS("uid" = $2)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testOrWhereFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE EXISTS("uid" = $1)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testOrWhereFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testOrWhereFragment2() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '<=', 5)
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" WHERE "uid" <= $1 OR EXISTS("uid" = $2)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testHaving() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" = $1', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testHavingAnd() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->having('created_at', '<', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" = $1 AND "created_at" < $2', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testHavingOr() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->orHaving('created_at', '>', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" = $1 OR "created_at" > $2', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testHavingExt() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->Having('uid', '=', 5)
            ->HavingExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '>', 2018)
                    ->or('created_at', '<', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" = $1 AND ("created_at" > $2 OR "created_at" < $3)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testHavingExtEmpty() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->havingExt(static function (WhereBuilder $where) {});
    }
    
    function testOrHavingExt() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->orHavingExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '<', 2018)
                    ->and('created_at', '>', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" = $1 OR ("created_at" < $2 AND "created_at" > $3)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testOrHavingExtEmpty() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->orHavingExt(static function (WhereBuilder $where) {});
    }
    
    function testHavingFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->havingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING EXISTS("uid" = $1)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testHavingFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->havingFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testHavingFragment2() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '<=', 5)
            ->havingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" <= $1 AND EXISTS("uid" = $2)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testOrHavingFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING EXISTS("uid" = $1)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testOrHavingFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testOrHavingFragment2() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '<=', 5)
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" HAVING "uid" <= $1 OR EXISTS("uid" = $2)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testLimit() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->limit(125)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" LIMIT 125', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOffset() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->offset(12)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" OFFSET 12', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testLimitOffset() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->limit(256)
            ->offset(125)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" LIMIT 256 OFFSET 125', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOrderBy() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orderBy('a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" ORDER BY "a" ASC', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOrderByDesc() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orderBy('a', true)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" ORDER BY "a" DESC', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOrderBy2() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orderBy('a')
            ->orderBy('b', true)
            ->orderBy('c')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" ORDER BY "a" ASC, "b" DESC, "c" ASC', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testGroupBy() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->groupBy('a')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" GROUP BY "a"', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testGroupBy2() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->groupBy('a')
            ->groupBy('b')
            ->groupBy('c')
            ->select();
        
        self::assertSame('SELECT * FROM "tests" GROUP BY "a", "b", "c"', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testSubquery() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->subquery(
                QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->select('*')
            )
            ->select();
        
        self::assertSame('SELECT (SELECT * FROM "abc" WHERE "a" = $1), * FROM "tests"', $query->getQuery());
        self::assertSame(array('c'), $query->getParameters());
    }
    
    function testUnion() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->union(
                QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->orderBy('abc')
                    ->groupBy('a')
                    ->select('*')
            )
            ->orderBy('ac')
            ->groupBy('ab')
            ->select();
        
        self::assertSame(
            '(SELECT * FROM "tests" GROUP BY "ab") UNION (SELECT * FROM "abc" WHERE "a" = $1 GROUP BY "a" ORDER BY "abc" ASC) ORDER BY "ac" ASC',
            $query->getQuery()
        );
        self::assertSame(array('c'), $query->getParameters());
    }
    
    function testUnionAll() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->unionAll(
                QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->orderBy('abc')
                    ->groupBy('a')
                    ->select('*')
            )
            ->orderBy('ac')
            ->groupBy('ab')
            ->select();
        
        self::assertSame(
            '(SELECT * FROM "tests" GROUP BY "ab") UNION ALL (SELECT * FROM "abc" WHERE "a" = $1 GROUP BY "a" ORDER BY "abc" ASC) ORDER BY "ac" ASC',
            $query->getQuery()
        );
        self::assertSame(array('c'), $query->getParameters());
    }
    
    function testDistinct() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->distinct()
            ->select();
        
        self::assertSame('SELECT DISTINCT * FROM "tests"', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForUpdate() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(QueryBuilder::ROW_LOCKING_FOR_UPDATE)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" FOR UPDATE', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForNoKeyUpdate() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(QueryBuilder::ROW_LOCKING_FOR_NO_KEY_UPDATE)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" FOR NO KEY UPDATE', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForShare() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(QueryBuilder::ROW_LOCKING_FOR_SHARE)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" FOR SHARE', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForKeyShare() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(QueryBuilder::ROW_LOCKING_FOR_KEY_SHARE)
            ->select();
        
        self::assertSame('SELECT * FROM "tests" FOR KEY SHARE', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockUnknown() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\InvalidArgumentException::class);
        $query->setSelectRowLocking(\PHP_INT_MAX);
    }
}
