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
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\FragmentedWhere;
use Plasma\SQL\WhereBuilder;

class FragmentedWhereTest extends TestCase {
    function testGetSQL() {
        $frag = new FragmentedWhere(null, (new Fragment('abc($$)')), (new WhereBuilder()));
        self::assertSame('abc()', $frag->getSQL(null));
    }
    
    function testGetSQL2() {
        $frag = new FragmentedWhere('AND', (new Fragment('abc($$)')), (new WhereBuilder()));
        self::assertSame('AND abc()', $frag->getSQL(null));
    }
    
    function testGetSQLMissingDoubleDollar() {
        $frag = new FragmentedWhere(null, (new Fragment('abc()')), (new WhereBuilder()));
        
        $this->expectException(\LogicException::class);
        $frag->getSQL(null);
    }
    
    function testGetParameters() {
        $frag = new FragmentedWhere('AND', (new Fragment('abc($$)')), (new WhereBuilder()));
        self::assertSame(array(), $frag->getParameters());
    }
}
