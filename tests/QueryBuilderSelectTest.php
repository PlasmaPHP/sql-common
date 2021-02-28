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
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;
use Plasma\SQL\WhereBuilder;

class QueryBuilderSelectTest extends TestCase {
    function testTable() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->select('*');
        
        self::assertSame('SELECT * FROM tests', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testTableColumns() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->select(array('uid', 'created_at'));
        
        self::assertSame('SELECT uid, created_at FROM tests', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testJoin() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->join('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM tests JOIN test AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testInnerJoin() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->innerJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM tests INNER JOIN test AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOuterJoin() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->outerJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM tests OUTER JOIN test AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testLeftJoin() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->leftJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM tests LEFT JOIN test AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRightJoin() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->rightJoin('test', 'a')
            ->select();
        
        self::assertSame('SELECT * FROM tests RIGHT JOIN test AS a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testJoinOn() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->join('test', 'a')
            ->on('tests.uid', 'a.abc')
            ->select();
        
        self::assertSame('SELECT * FROM tests JOIN test AS a ON tests.uid = a.abc', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOnMissingJoin() {
        $query = QueryBuilder::create()
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->on('tests.uid', 'a.abc');
    }
    
    function testJoinOn2() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->join('test', 'a')
            ->on('tests.uid', 'a.abc')
            ->on('tests.ab', 'a.abc')
            ->select();
        
        self::assertSame('SELECT * FROM tests JOIN test AS a ON tests.uid = a.abc AND tests.ab = a.abc', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testWhere() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '=', 5)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid = ?', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testWhereAnd() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '=', 5)
            ->where('created_at', '<', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid = ? AND created_at < ?', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testWhereOr() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '=', 5)
            ->orWhere('created_at', '>', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid = ? OR created_at > ?', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testWhereExt() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '=', 5)
            ->whereExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '>', 2018)
                    ->or('created_at', '<', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid = ? AND (created_at > ? OR created_at < ?)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testWhereExtEmpty() {
        $query = QueryBuilder::create()
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->whereExt(static function (WhereBuilder $where) {});
    }
    
    function testOrWhereExt() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '=', 5)
            ->orWhereExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '<', 2018)
                    ->and('created_at', '>', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid = ? OR (created_at < ? AND created_at > ?)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testOrWhereExtEmpty() {
        $query = QueryBuilder::create()
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->orWhereExt(static function (WhereBuilder $where) {});
    }
    
    function testWhereFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->whereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testWhereFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::create()
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
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '<=', 5)
            ->whereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid <= ? AND EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testOrWhereFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testOrWhereFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::create()
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
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('uid', '<=', 5)
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE uid <= ? OR EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testHaving() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->having('uid', '=', 5)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid = ?', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testHavingAnd() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->having('uid', '=', 5)
            ->having('created_at', '<', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid = ? AND created_at < ?', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testHavingOr() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->having('uid', '=', 5)
            ->orHaving('created_at', '>', 2019)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid = ? OR created_at > ?', $query->getQuery());
        self::assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testHavingExt() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->Having('uid', '=', 5)
            ->HavingExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '>', 2018)
                    ->or('created_at', '<', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid = ? AND (created_at > ? OR created_at < ?)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testHavingExtEmpty() {
        $query = QueryBuilder::create()
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->havingExt(static function (WhereBuilder $where) {});
    }
    
    function testOrHavingExt() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->having('uid', '=', 5)
            ->orHavingExt(function (WhereBuilder $where) {
                $where
                    ->and('created_at', '<', 2018)
                    ->and('created_at', '>', 0);
            })
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid = ? OR (created_at < ? AND created_at > ?)', $query->getQuery());
        self::assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testOrHavingExtEmpty() {
        $query = QueryBuilder::create()
            ->from('tests');
        
        $this->expectException(Exception::class);
        $query->orHavingExt(static function (WhereBuilder $where) {});
    }
    
    function testHavingFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->havingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testHavingFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::create()
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
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->having('uid', '<=', 5)
            ->havingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid <= ? AND EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testOrHavingFragment() {
        $fragment = new Fragment('EXISTS($$)');
        $builder = (new WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5), $query->getParameters());
    }
    
    function testOrHavingFragmentMissingDoubleDollar() {
        $fragment = new Fragment('EXISTS()');
        $builder = (new WhereBuilder());
        
        $query = QueryBuilder::create()
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
        
        $query = QueryBuilder::create()
            ->from('tests')
            ->having('uid', '<=', 5)
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        self::assertSame('SELECT * FROM tests HAVING uid <= ? OR EXISTS(uid = ?)', $query->getQuery());
        self::assertSame(array(5, 5), $query->getParameters());
    }
    
    function testLimit() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->limit(125)
            ->select();
        
        self::assertSame('SELECT * FROM tests LIMIT 125', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOffset() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->offset(12)
            ->select();
        
        self::assertSame('SELECT * FROM tests OFFSET 12', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testLimitOffset() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->limit(256)
            ->offset(125)
            ->select();
        
        self::assertSame('SELECT * FROM tests LIMIT 256 OFFSET 125', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOrderBy() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->orderBy('a')
            ->select();
        
        self::assertSame('SELECT * FROM tests ORDER BY a ASC', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOrderByDesc() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->orderBy('a', true)
            ->select();
        
        self::assertSame('SELECT * FROM tests ORDER BY a DESC', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testOrderBy2() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->orderBy('a')
            ->orderBy('b', true)
            ->orderBy('c')
            ->select();
        
        self::assertSame('SELECT * FROM tests ORDER BY a ASC, b DESC, c ASC', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testGroupBy() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->groupBy('a')
            ->select();
        
        self::assertSame('SELECT * FROM tests GROUP BY a', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testGroupBy2() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->groupBy('a')
            ->groupBy('b')
            ->groupBy('c')
            ->select();
        
        self::assertSame('SELECT * FROM tests GROUP BY a, b, c', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testSubquery() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->subquery(
                QueryBuilder::create()
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->select('*')
            )
            ->select();
        
        self::assertSame('SELECT (SELECT * FROM abc WHERE a = ?), * FROM tests', $query->getQuery());
        self::assertSame(array('c'), $query->getParameters());
    }
    
    function testUnion() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->union(
                QueryBuilder::create()
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
            '(SELECT * FROM tests GROUP BY ab) UNION (SELECT * FROM abc WHERE a = ? GROUP BY a ORDER BY abc ASC) ORDER BY ac ASC',
            $query->getQuery()
        );
        self::assertSame(array('c'), $query->getParameters());
    }
    
    function testUnionAll() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->unionAll(
                QueryBuilder::create()
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
            '(SELECT * FROM tests GROUP BY ab) UNION ALL (SELECT * FROM abc WHERE a = ? GROUP BY a ORDER BY abc ASC) ORDER BY ac ASC',
            $query->getQuery()
        );
        self::assertSame(array('c'), $query->getParameters());
    }
    
    function testPrefix() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->setPrefix('abc')
            ->select();
        
        self::assertSame('SELECT * FROM abc.tests', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testDistinct() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->distinct()
            ->select();
        
        self::assertSame('SELECT DISTINCT * FROM tests', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockUnsupported() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->setSelectRowLocking(QueryBuilder::ROW_LOCKING_FOR_KEY_SHARE)
            ->select();
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
    
    function testBetweenQuery() {
        $query = QueryBuilder::create()
            ->from('tests')
            ->where('a', 'BETWEEN', QueryBuilder::between(0, 1))
            ->select();
        
        self::assertSame('SELECT * FROM tests WHERE a BETWEEN ? AND ?', $query->getQuery());
        self::assertSame(array(0, 1), $query->getParameters());
    }
    
    function testComplexSelectQuery() {
        $query = QueryBuilder::create()
            ->from('users', 'u')
            ->leftJoin('comments', 'c')
            ->on('c.uid', 'u.uid')
            ->outerJoin('abc')
            ->on('abc.a', 'u.created_at')
            ->on('abc.g', 'u.updated_at')
            ->where('u.uid', '=', ($p1 = new Parameter()))
            ->orWhereExt(function (WhereBuilder $where) {
                $where
                    ->and('abc', '=', 59)
                    ->andBuilder(
                        (new WhereBuilder())
                            ->and('a', 'IS NULL')
                            ->or('a', 'IN', array(0, 1))
                    )
                    ->orBuilder(
                        (new WhereBuilder())
                            ->and('ac', 'IS NOT NULL')
                            ->or('b', '<>', 1)
                            ->andBuilder(
                                (new WhereBuilder())
                                    ->and('js', '=', 'BAD')
                            )
                    );
            })
            ->orderBy('uid')
            ->orderBy('created_at', true)
            ->groupBy('created_at')
            ->groupBy('abcd')
            ->limit(5)
            ->offset(0)
            ->select(array(
                'uid',
                'username',
                QueryBuilder::fragment(
                    '? AS ?',
                    QueryBuilder::fragment('json_field->abc'),
                    (new Column('json_data', null, false))
                )
            ));
        
        $p1->setValue(500);
        $expected = 'SELECT uid, username, json_field->abc AS json_data FROM users AS u LEFT JOIN comments AS c ON c.uid = u.uid '.
            'OUTER JOIN abc ON abc.a = u.created_at AND abc.g = u.updated_at '.
            'WHERE u.uid = ? OR (abc = ? AND (a IS NULL OR a IN (?, ?)) '.
            'OR (ac IS NOT NULL OR b <> ? AND (js = ?))) '.
            'GROUP BY created_at, abcd ORDER BY uid ASC, created_at DESC '.
            'LIMIT 5 OFFSET 0';
        
        self::assertSame($expected, $query->getQuery());
        self::assertSame(array(500, 59, 0, 1, 1, 'BAD'), $query->getParameters());
    }
}
