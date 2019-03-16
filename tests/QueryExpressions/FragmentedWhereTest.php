<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class FragmentedWhereTest extends \PHPUnit\Framework\TestCase {
    function testGetSQL() {
        $frag = new \Plasma\SQL\QueryExpressions\FragmentedWhere(null, (new \Plasma\SQL\QueryExpressions\Fragment('abc($$)')), (new \Plasma\SQL\WhereBuilder()));
        $this->assertSame('abc()', $frag->getSQL(null));
    }
    
    function testGetSQL2() {
        $frag = new \Plasma\SQL\QueryExpressions\FragmentedWhere('AND', (new \Plasma\SQL\QueryExpressions\Fragment('abc($$)')), (new \Plasma\SQL\WhereBuilder()));
        $this->assertSame('AND abc()', $frag->getSQL(null));
    }
    
    function testGetSQLMissingDoubleDollar() {
        $frag = new \Plasma\SQL\QueryExpressions\FragmentedWhere(null, (new \Plasma\SQL\QueryExpressions\Fragment('abc()')), (new \Plasma\SQL\WhereBuilder()));
        
        $this->expectException(\LogicException::class);
        $frag->getSQL(null);
    }
    
    function testGetParameters() {
        $frag = new \Plasma\SQL\QueryExpressions\FragmentedWhere('AND', (new \Plasma\SQL\QueryExpressions\Fragment('abc($$)')), (new \Plasma\SQL\WhereBuilder()));
        $this->assertSame(array(), $frag->getParameters());
    }
}
