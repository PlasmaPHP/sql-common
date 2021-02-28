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
use Plasma\SQL\QueryExpressions\Union;

class UnionTest extends TestCase {
    function testGetSQL() {
        $union = new Union(QueryBuilder::create()->from('tests')->select());
        self::assertSame('SELECT * FROM tests', $union->getSQL(null));
    }
    
    function testGetParameters() {
        $union = new Union(QueryBuilder::create()->from('tests')->select());
        self::assertSame(array(), $union->getParameters());
    }
    
    function testToString() {
        $union = new Union(QueryBuilder::create()->from('tests')->select());
        self::assertSame('SELECT * FROM tests', ((string) $union));
    }
}
