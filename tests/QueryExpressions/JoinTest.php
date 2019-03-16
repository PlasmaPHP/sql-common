<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class JoinTest extends \PHPUnit\Framework\TestCase {
    function testGetType() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', null, false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        $this->assertSame('INNER', $join->getType());
    }
    
    function testGetTable() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', null, false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        $this->assertSame($table, $join->getTable());
    }
    
    function testGetOns() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', null, false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        $this->assertSame(array(), $join->getOns());
    }
    
    function testAddOns() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', null, false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        
        $on = new \Plasma\SQL\QueryExpressions\On('a.d', 'd.a');
        $join->addOn($on);
        
        $this->assertSame(array($on), $join->getOns());
    }
    
    function testGetSQL() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', null, false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        
        $on = new \Plasma\SQL\QueryExpressions\On('a.d', 'd.a');
        $join->addOn($on)
            ->addOn($on);
        
        $this->assertSame('INNER JOIN a ON a.d = d.a AND a.d = d.a', $join->getSQL(null));
    }
    
    function testGetSQLWithAlias() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', 'ad', false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        
        $on = new \Plasma\SQL\QueryExpressions\On('a.d', 'd.a');
        $join->addOn($on)
            ->addOn($on);
        
        $this->assertSame('INNER JOIN a AS ad ON a.d = d.a AND a.d = d.a', $join->getSQL(null));
    }
    
    function testToString() {
        $table = new \Plasma\SQL\QueryExpressions\Table('a', null, false);
        $join = new \Plasma\SQL\QueryExpressions\Join('INNER', $table);
        
        $on = new \Plasma\SQL\QueryExpressions\On('a.d', 'd.a');
        $join->addOn($on)
            ->addOn($on);
        
        $this->assertSame('INNER JOIN a ON a.d = d.a AND a.d = d.a', ((string) $join));
    }
}
