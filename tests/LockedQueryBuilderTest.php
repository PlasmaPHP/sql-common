<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests;

class LockedQueryBuilderTest extends \PHPUnit\Framework\TestCase {
    function testCreate() {
        $this->expectException(\LogicException::class);
        \Plasma\SQL\LockedQueryBuilder::create();
    }
    
    function testConstructor() {
        $sql = 'SELECT 1';
        $params = array(5);
        
        $qb = new \Plasma\SQL\LockedQueryBuilder($sql, $params);
        
        $this->assertSame($sql, $qb->getQuery());
        $this->assertSame($params, $qb->getParameters());
    }
}
