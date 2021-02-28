<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests\QueryExpressions;

use PHPUnit\Framework\TestCase;
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\Subquery;

class SubqueryTest extends TestCase {
    function testGetSQL() {
        $subquery = new Subquery(QueryBuilder::create()->from('tests')->select(), 't');
        self::assertSame('(SELECT * FROM tests) AS t', $subquery->getSQL(null));
    }
    
    function testGetParameters() {
        $subquery = new Subquery(QueryBuilder::create()->from('tests')->insert(array('a' => 5)), 't');
        self::assertEquals(array(5), $subquery->getParameters());
    }
    
    function testToString() {
        $subquery = new Subquery(QueryBuilder::create()->from('tests')->select(), null);
        self::assertSame('(SELECT * FROM tests)', ((string) $subquery));
    }
}
