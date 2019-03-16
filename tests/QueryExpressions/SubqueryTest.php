<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class SubqueryTest extends \PHPUnit\Framework\TestCase {
    function testGetSQL() {
        $subquery = new \Plasma\SQL\QueryExpressions\Subquery(\Plasma\SQL\QueryBuilder::create()->from('tests')->select(), 't');
        $this->assertSame('(SELECT * FROM tests) AS t', $subquery->getSQL(null));
    }
    
    function testGetParameters() {
        $subquery = new \Plasma\SQL\QueryExpressions\Subquery(\Plasma\SQL\QueryBuilder::create()->from('tests')->insert(array('a' => 5)), 't');
        $this->assertEquals(array(5), $subquery->getParameters());
    }
    
    function testToString() {
        $subquery = new \Plasma\SQL\QueryExpressions\Subquery(\Plasma\SQL\QueryBuilder::create()->from('tests')->select(), null);
        $this->assertSame('(SELECT * FROM tests)', ((string) $subquery));
    }
}
