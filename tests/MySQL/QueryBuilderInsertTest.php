<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\MySQL;

class QueryBuilderInsertTest extends TestCase {
    function testInsert() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => 'hello'
            ));
        
        $this->assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertParameter() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Parameter('hello', true))
            ));
        
        $this->assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
        $this->assertSame(array(500, 'hello'), $query->getParameters());
    }
    
    function testInsertFragment() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array(
                'abc' => 500,
                'efg' => (new \Plasma\SQL\QueryExpressions\Fragment('LOWER(`abc`)'))
            ));
        
        $this->assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, LOWER(`abc`))', $query->getQuery());
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
        
        $this->assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
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
        
        $this->assertSame('INSERT IGNORE INTO `tests` (`abc`, `efg`) VALUES (?, ?)', $query->getQuery());
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
        
        $this->assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `abc` = VALUES(`abc`), `efg` = VALUES(`efg`)', $query->getQuery());
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
        
        $this->assertSame('INSERT INTO `tests` (`abc`, `efg`) VALUES (?, ?) ON DUPLICATE KEY UPDATE `abc` = VALUES(`abc`)', $query->getQuery());
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
        
        $this->assertSame('INSERT INTO `tests` VALUES ((SELECT * FROM `abc` WHERE `a` = ?))', $query->getQuery());
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
    
    function testReturningUnsupported() {
        $query = \Plasma\SQL\QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->insert(array('abc' => 5))
            ->returning();
        
        $this->expectException(\Plasma\Exception::class);
        $query->getQuery();
    }
}
