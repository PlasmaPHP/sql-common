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
use Plasma\SQL\QueryExpressions\Join;
use Plasma\SQL\QueryExpressions\On;
use Plasma\SQL\QueryExpressions\Table;

class JoinTest extends TestCase {
    function testGetType() {
        $table = new Table('a', null, false);
        $join = new Join('INNER', $table);
        self::assertSame('INNER', $join->getType());
    }
    
    function testGetTable() {
        $table = new Table('a', null, false);
        $join = new Join('INNER', $table);
        self::assertSame($table, $join->getTable());
    }
    
    function testGetOns() {
        $table = new Table('a', null, false);
        $join = new Join('INNER', $table);
        self::assertSame(array(), $join->getOns());
    }
    
    function testAddOns() {
        $table = new Table('a', null, false);
        $join = new Join('INNER', $table);
        
        $on = new On('a.d', 'd.a');
        $join->addOn($on);
        
        self::assertSame(array($on), $join->getOns());
    }
    
    function testGetSQL() {
        $table = new Table('a', null, false);
        $join = new Join('INNER', $table);
        
        $on = new On('a.d', 'd.a');
        $join->addOn($on)
            ->addOn($on);
        
        self::assertSame('INNER JOIN a ON a.d = d.a AND a.d = d.a', $join->getSQL(null));
    }
    
    function testGetSQLWithAlias() {
        $table = new Table('a', 'ad', false);
        $join = new Join('INNER', $table);
        
        $on = new On('a.d', 'd.a');
        $join->addOn($on)
            ->addOn($on);
        
        self::assertSame('INNER JOIN a AS ad ON a.d = d.a AND a.d = d.a', $join->getSQL(null));
    }
    
    function testToString() {
        $table = new Table('a', null, false);
        $join = new Join('INNER', $table);
        
        $on = new On('a.d', 'd.a');
        $join->addOn($on)
            ->addOn($on);
        
        self::assertSame('INNER JOIN a ON a.d = d.a AND a.d = d.a', ((string) $join));
    }
}
