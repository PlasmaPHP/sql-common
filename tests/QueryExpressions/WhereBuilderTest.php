<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class WhereBuilderTest extends \PHPUnit\Framework\TestCase {
    function testGetSQL() {
        $where = new \Plasma\SQL\QueryExpressions\WhereBuilder(null, (new \Plasma\SQL\WhereBuilder())->and('o', 'IS NULL'));
        $this->assertSame('(o IS NULL)', $where->getSQL(null));
    }
    
    function testGetSQL2() {
        $where = new \Plasma\SQL\QueryExpressions\WhereBuilder('AND', (new \Plasma\SQL\WhereBuilder())->and('o', 'IS NULL'));
        $this->assertSame('AND (o IS NULL)', $where->getSQL(null));
    }
    
    function testGetParameters() {
        $where = new \Plasma\SQL\QueryExpressions\WhereBuilder('AND', (new \Plasma\SQL\WhereBuilder())->and('o', '=', 5));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(5, true))
        ), $where->getParameters());
    }
    
    function testGetParametersEmpty() {
        $where = new \Plasma\SQL\QueryExpressions\WhereBuilder('AND', (new \Plasma\SQL\WhereBuilder())->and('o', 'IS NULL'));
        $this->assertSame(array(), $where->getParameters());
    }
}
