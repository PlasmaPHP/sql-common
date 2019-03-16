<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class GroupByTest extends \PHPUnit\Framework\TestCase {
    function testGetColumn() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $grp = new \Plasma\SQL\QueryExpressions\GroupBy($col);
        $this->assertSame($col, $grp->getColumn());
    }
    
    function testGetSQL() {
        $col = new \Plasma\SQL\QueryExpressions\Column('abc', null, true);
        $grp = new \Plasma\SQL\QueryExpressions\GroupBy($col);
        $this->assertSame('abc', $grp->getSQL(null));
    }
}
