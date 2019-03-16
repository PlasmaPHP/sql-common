<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\MySQL;

class QueryBuilderSelectTest extends TestCase {
    function testCreateAndGrammar() {
        $query = \Plasma\SQL\QueryBuilder::create();
        $query2 = $query->withGrammar($this->grammar);
        
        $this->assertNotSame($query, $query2);
    }
    
    function testTable() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->select('*');
        
        $this->assertSame('SELECT * FROM `tests`', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testTableColumns() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->select(array('uid', 'created_at'));
        
        $this->assertSame('SELECT `uid`, `created_at` FROM `tests`', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testJoin() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->join('test', 'a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` JOIN `test` AS a', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testInnerJoin() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->innerJoin('test', 'a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` INNER JOIN `test` AS a', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testOuterJoin() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->outerJoin('test', 'a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` OUTER JOIN `test` AS a', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testLeftJoin() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->leftJoin('test', 'a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` LEFT JOIN `test` AS a', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testRightJoin() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->rightJoin('test', 'a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` RIGHT JOIN `test` AS a', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testJoinOn() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->join('test', 'a')
            ->on('tests.uid', 'a.abc')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` JOIN `test` AS a ON tests.uid = a.abc', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testOnMissingJoin() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\Plasma\Exception::class);
        $query->on('tests.uid', 'a.abc');
    }
    
    function testJoinOn2() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->join('test', 'a')
            ->on('tests.uid', 'a.abc')
            ->on('tests.ab', 'a.abc')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` JOIN `test` AS a ON tests.uid = a.abc AND tests.ab = a.abc', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testWhere() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` = ?', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testWhereAnd() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->where('created_at', '<', 2019)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` = ? AND `created_at` < ?', $query->getQuery());
        $this->assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testWhereOr() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->orWhere('created_at', '>', 2019)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` = ? OR `created_at` > ?', $query->getQuery());
        $this->assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testWhereExt() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->whereExt(function (\Plasma\SQL\WhereBuilder $where) {
                $where
                    ->and('created_at', '>', 2018)
                    ->or('created_at', '<', 0);
            })
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` = ? AND (`created_at` > ? OR `created_at` < ?)', $query->getQuery());
        $this->assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testWhereExtEmpty() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\Plasma\Exception::class);
        $query->whereExt(function (\Plasma\SQL\WhereBuilder $where) {});
    }
    
    function testOrWhereExt() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '=', 5)
            ->orWhereExt(function (\Plasma\SQL\WhereBuilder $where) {
                $where
                    ->and('created_at', '<', 2018)
                    ->and('created_at', '>', 0);
            })
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` = ? OR (`created_at` < ? AND `created_at` > ?)', $query->getQuery());
        $this->assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testOrWhereExtEmpty() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\Plasma\Exception::class);
        $query->orWhereExt(function (\Plasma\SQL\WhereBuilder $where) {});
    }
    
    function testWhereFragment() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->whereFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testWhereFragmentMissingDoubleDollar() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS()');
        $builder = (new \Plasma\SQL\WhereBuilder());
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->whereFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testWhereFragment2() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '<=', 5)
            ->whereFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` <= ? AND EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5, 5), $query->getParameters());
    }
    
    function testOrWhereFragment() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testOrWhereFragmentMissingDoubleDollar() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS()');
        $builder = (new \Plasma\SQL\WhereBuilder());
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
        ->from('tests')
        ->orWhereFragment($fragment, $builder)
        ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testOrWhereFragment2() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('uid', '<=', 5)
            ->orWhereFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` WHERE `uid` <= ? OR EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5, 5), $query->getParameters());
    }
    
    function testHaving() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` = ?', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testHavingAnd() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->having('created_at', '<', 2019)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` = ? AND `created_at` < ?', $query->getQuery());
        $this->assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testHavingOr() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->orHaving('created_at', '>', 2019)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` = ? OR `created_at` > ?', $query->getQuery());
        $this->assertSame(array(5, 2019), $query->getParameters());
    }
    
    function testHavingExt() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->Having('uid', '=', 5)
            ->HavingExt(function (\Plasma\SQL\WhereBuilder $where) {
                $where
                    ->and('created_at', '>', 2018)
                    ->or('created_at', '<', 0);
            })
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` = ? AND (`created_at` > ? OR `created_at` < ?)', $query->getQuery());
        $this->assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testHavingExtEmpty() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\Plasma\Exception::class);
        $query->havingExt(function (\Plasma\SQL\WhereBuilder $where) {});
    }
    
    function testOrHavingExt() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '=', 5)
            ->orHavingExt(function (\Plasma\SQL\WhereBuilder $where) {
                $where
                    ->and('created_at', '<', 2018)
                    ->and('created_at', '>', 0);
            })
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` = ? OR (`created_at` < ? AND `created_at` > ?)', $query->getQuery());
        $this->assertSame(array(5, 2018, 0), $query->getParameters());
    }
    
    function testOrHavingExtEmpty() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\Plasma\Exception::class);
        $query->orHavingExt(function (\Plasma\SQL\WhereBuilder $where) {});
    }
    
    function testHavingFragment() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->havingFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testHavingFragmentMissingDoubleDollar() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS()');
        $builder = (new \Plasma\SQL\WhereBuilder());
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->havingFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testHavingFragment2() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '<=', 5)
            ->havingFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` <= ? AND EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5, 5), $query->getParameters());
    }
    
    function testOrHavingFragment() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5), $query->getParameters());
    }
    
    function testOrHavingFragmentMissingDoubleDollar() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS()');
        $builder = (new \Plasma\SQL\WhereBuilder());
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        $this->expectException(\LogicException::class);
        $query->getQuery();
    }
    
    function testOrHavingFragment2() {
        $fragment = new \Plasma\SQL\QueryExpressions\Fragment('EXISTS($$)');
        $builder = (new \Plasma\SQL\WhereBuilder())
            ->and('uid', '=', 5);
        
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->having('uid', '<=', 5)
            ->orHavingFragment($fragment, $builder)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` HAVING `uid` <= ? OR EXISTS(`uid` = ?)', $query->getQuery());
        $this->assertSame(array(5, 5), $query->getParameters());
    }
    
    function testLimit() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->limit(125)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` LIMIT 125', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testOffset() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->offset(12)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` OFFSET 12', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testLimitOffset() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->limit(256)
            ->offset(125)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` LIMIT 256 OFFSET 125', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testOrderBy() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orderBy('a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` ORDER BY `a` ASC', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testOrderByDesc() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orderBy('a', true)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` ORDER BY `a` DESC', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testOrderBy2() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->orderBy('a')
            ->orderBy('b', true)
            ->orderBy('c')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` ORDER BY `a` ASC, `b` DESC, `c` ASC', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testGroupBy() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->groupBy('a')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` GROUP BY `a`', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testGroupBy2() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->groupBy('a')
            ->groupBy('b')
            ->groupBy('c')
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` GROUP BY `a`, `b`, `c`', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testSubquery() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->subquery(
                \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->select('*')
            )
            ->select();
        
        $this->assertSame('SELECT (SELECT * FROM `abc` WHERE `a` = ?), * FROM `tests`', $query->getQuery());
        $this->assertSame(array('c'), $query->getParameters());
    }
    
    function testUnion() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->union(
                \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->orderBy('abc')
                    ->groupBy('a')
                    ->select('*')
            )
            ->orderBy('ac')
            ->groupBy('ab')
            ->select();
        
        $this->assertSame('(SELECT * FROM `tests` GROUP BY `ab`) UNION (SELECT * FROM `abc` WHERE `a` = ? GROUP BY `a` ORDER BY `abc` ASC) ORDER BY `ac` ASC', $query->getQuery());
        $this->assertSame(array('c'), $query->getParameters());
    }
    
    function testUnionAll() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->unionAll(
                \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
                    ->from('abc')
                    ->where('a', '=', 'c')
                    ->orderBy('abc')
                    ->groupBy('a')
                    ->select('*')
            )
            ->orderBy('ac')
            ->groupBy('ab')
            ->select();
        
        $this->assertSame('(SELECT * FROM `tests` GROUP BY `ab`) UNION ALL (SELECT * FROM `abc` WHERE `a` = ? GROUP BY `a` ORDER BY `abc` ASC) ORDER BY `ac` ASC', $query->getQuery());
        $this->assertSame(array('c'), $query->getParameters());
    }
    
    function testDistinct() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->distinct()
            ->select();
        
        $this->assertSame('SELECT DISTINCT * FROM `tests`', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForUpdate() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(\Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_UPDATE)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` FOR UPDATE', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForNoKeyUpdate() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(\Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_NO_KEY_UPDATE)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` FOR UPDATE', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForShare() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(\Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_SHARE)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` FOR SHARE', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockForKeyShare() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->setSelectRowLocking(\Plasma\SQL\QueryBuilder::ROW_LOCKING_FOR_KEY_SHARE)
            ->select();
        
        $this->assertSame('SELECT * FROM `tests` FOR SHARE', $query->getQuery());
        $this->assertSame(array(), $query->getParameters());
    }
    
    function testRowLevelLockUnknown() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests');
        
        $this->expectException(\InvalidArgumentException::class);
        $query->setSelectRowLocking(\PHP_INT_MAX);
    }
}
