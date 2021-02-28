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
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\GroupBy;

class GroupByTest extends TestCase {
    function testGetColumn() {
        $col = new Column('abc', null, true);
        $grp = new GroupBy($col);
        self::assertSame($col, $grp->getColumn());
    }
    
    function testGetSQL() {
        $col = new Column('abc', null, true);
        $grp = new GroupBy($col);
        self::assertSame('abc', $grp->getSQL(null));
    }
}
