<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class UnionAllTest extends \PHPUnit\Framework\TestCase {
    function testGetSQL() {
        $union = new \Plasma\SQL\QueryExpressions\UnionAll(\Plasma\SQL\QueryBuilder::create()->from('tests')->select());
        $this->assertSame('SELECT * FROM tests', $union->getSQL(null));
    }
    
    function testGetParameters() {
        $union = new \Plasma\SQL\QueryExpressions\UnionAll(\Plasma\SQL\QueryBuilder::create()->from('tests')->select());
        $this->assertSame(array(), $union->getParameters());
    }
    
    function testToString() {
        $union = new \Plasma\SQL\QueryExpressions\UnionAll(\Plasma\SQL\QueryBuilder::create()->from('tests')->select());
        $this->assertSame('SELECT * FROM tests', ((string) $union));
    }
}
