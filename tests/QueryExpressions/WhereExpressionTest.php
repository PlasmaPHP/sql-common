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
use Plasma\SQL\QueryExpressions\Parameter;
use Plasma\SQL\QueryExpressions\WhereExpression;
use Plasma\SQL\WhereBuilder;

class WhereExpressionTest extends TestCase {
    function testGetSQL() {
        $where = new WhereExpression(null, (new WhereBuilder())->and('o', 'IS NULL'));
        self::assertSame('(o IS NULL)', $where->getSQL(null));
    }
    
    function testGetSQL2() {
        $where = new WhereExpression('AND', (new WhereBuilder())->and('o', 'IS NULL'));
        self::assertSame('AND (o IS NULL)', $where->getSQL(null));
    }
    
    function testGetParameters() {
        $where = new WhereExpression('AND', (new WhereBuilder())->and('o', '=', 5));
        self::assertEquals(array(
            (new Parameter(5, true))
        ), $where->getParameters());
    }
    
    function testGetParametersEmpty() {
        $where = new WhereExpression('AND', (new WhereBuilder())->and('o', 'IS NULL'));
        self::assertSame(array(), $where->getParameters());
    }
}
