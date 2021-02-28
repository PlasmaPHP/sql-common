<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests\MySQL;

use Plasma\Exception;
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryBuilder;
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
        
        self::assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertParameter() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new Parameter('hello', true))
            ));
        
        self::assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
        self::assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertFragment() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new Fragment('LOWER(`abc`)'))
            ));
        
        self::assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, LOWER(`abc`))', $query->getQuery());
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
        
        self::assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
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
        
        self::assertSame('INSERT IGNORE INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
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
            'INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `abc` = VALUES(`abc`), `efg` = VALUES(`efg`)',
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
        
        self::assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `abc` = VALUES(`abc`)', $query->getQuery());
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
        
        self::assertSame('INSERT INTO `tests` VALUES ((SELECT * FROM `abc` WHERE `a` = ?))', $query->getQuery());
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
    
    function testReturningUnsupported() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array('abc' => 5))
            ->returning();
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
}
