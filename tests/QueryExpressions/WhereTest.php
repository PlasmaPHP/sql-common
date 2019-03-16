<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests\QueryExpressions;

class WhereTest extends \PHPUnit\Framework\TestCase {
    function testGetSQLNullOp() {
        $where = new \Plasma\SQL\QueryExpressions\Where(null, (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), null, null);
        $this->assertSame('abc', $where->getSQL(null));
    }
    
    function testGetSQLNullValue() {
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'IS NULL', null);
        $this->assertSame('AND abc IS NULL', $where->getSQL(null));
    }
    
    function testGetSQLParam() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(5);
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), '=', $parameter);
        $this->assertSame('AND abc = ?', $where->getSQL(null));
    }
    
    function testGetSQLIn() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(array(5));
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'IN', $parameter);
        $this->assertSame('AND abc IN (?)', $where->getSQL(null));
    }
    
    function testGetSQLInNoArray() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(5);
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'IN', $parameter);
        
        $this->expectException(\LogicException::class);
        $where->getSQL(null);
    }
    
    function testGetSQLBetween() {
        $parameter = new \Plasma\SQL\QueryExpressions\BetweenParameter(5, 2);
        $where = new \Plasma\SQL\QueryExpressions\Where('OR', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'BETWEEN', $parameter);
        $this->assertSame('OR abc BETWEEN ? AND ?', $where->getSQL(null));
    }
    
    function testGetParameter() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(5);
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), '=', $parameter);
        $this->assertSame($parameter, $where->getParameter());
    }
    
    function testGetParametersNullOp() {
        $where = new \Plasma\SQL\QueryExpressions\Where(null, (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), null, null);
        $this->assertSame(array(), $where->getParameters());
    }

    function testGetParametersNullValue() {
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'IS NULL', null);
        $this->assertSame(array(), $where->getParameters());
    }

    function testGetParametersParam() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(5);
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), '=', $parameter);
        $this->assertSame(array($parameter), $where->getParameters());
    }

    function testGetParametersIn() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(array(5));
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'IN', $parameter);
        
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(5, true))
        ), $where->getParameters());
    }

    function testGetParametersInNoArray() {
        $parameter = new \Plasma\SQL\QueryExpressions\Parameter(5);
        $where = new \Plasma\SQL\QueryExpressions\Where('AND', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'IN', $parameter);
        
        $this->expectException(\LogicException::class);
        $where->getParameters();
    }

    function testGetParametersBetween() {
        $parameter = new \Plasma\SQL\QueryExpressions\BetweenParameter(5, 2);
        $where = new \Plasma\SQL\QueryExpressions\Where('OR', (new \Plasma\SQL\QueryExpressions\Column('abc', null, false)), 'BETWEEN', $parameter);
        $this->assertSame($parameter->getValue(), $where->getParameters());
    }
}
