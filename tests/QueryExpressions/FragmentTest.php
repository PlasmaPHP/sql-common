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

class FragmentTest extends TestCase {
    function testAllowEscape2() {
        $frag = new Fragment('abc');
        self::assertFalse($frag->allowEscape());
    }
    
    function testGetSQL() {
        $frag = new Fragment('abc');
        self::assertSame('abc', $frag->getSQL());
    }
    
    function testToString() {
        $frag = new Fragment('abc');
        self::assertSame('abc', ((string) $frag));
    }
}
