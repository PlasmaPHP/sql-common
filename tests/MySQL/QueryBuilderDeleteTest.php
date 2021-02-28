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
use Plasma\SQL\QueryBuilder;

class QueryBuilderDeleteTest extends TestCase {
    function testDelete() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->delete();
        
        self::assertSame('DELETE FROM `tests`', $query->getQuery());
        self::assertSame(array(), $query->getParameters());
    }
    
    function testDeleteWhere() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->from('tests')
            ->where('abc', 'IS NULL')
            ->orWhere('efg', '=', 250)
            ->delete();
        
        self::assertSame('DELETE FROM `tests` WHERE `abc` IS NULL OR `efg` = ?', $query->getQuery());
        self::assertSame(array(250), $query->getParameters());
    }
    
    function testReturningUnsupported() {
        $query = QueryBuilder::createWithGrammar($this->grammar)
            ->into('tests')
            ->delete()
            ->returning();
        
        $this->expectException(Exception::class);
        $query->getQuery();
    }
}
