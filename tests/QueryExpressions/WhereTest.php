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
use Plasma\SQL\QueryExpressions\BetweenParameter;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Parameter;
use Plasma\SQL\QueryExpressions\Where;

class WhereTest extends TestCase {
    function testGetSQLNullOp() {
        $where = new Where(null, (new Column('abc', null, false)), null, null);
        self::assertSame('abc', $where->getSQL(null));
    }
    
    function testGetSQLNullValue() {
        $where = new Where('AND', (new Column('abc', null, false)), 'IS NULL', null);
        self::assertSame('AND abc IS NULL', $where->getSQL(null));
    }
    
    function testGetSQLParam() {
        $parameter = new Parameter(5);
        $where = new Where('AND', (new Column('abc', null, false)), '=', $parameter);
        self::assertSame('AND abc = ?', $where->getSQL(null));
    }
    
    function testGetSQLIn() {
        $parameter = new Parameter(array(5));
        $where = new Where('AND', (new Column('abc', null, false)), 'IN', $parameter);
        self::assertSame('AND abc IN (?)', $where->getSQL(null));
    }
    
    function testGetSQLInNoArray() {
        $parameter = new Parameter(5);
        $where = new Where('AND', (new Column('abc', null, false)), 'IN', $parameter);
        
        $this->expectException(\LogicException::class);
        $where->getSQL(null);
    }
    
    function testGetSQLBetween() {
        $parameter = new BetweenParameter((new Parameter(5)), (new Parameter(2)));
        $where = new Where('OR', (new Column('abc', null, false)), 'BETWEEN', $parameter);
        self::assertSame('OR abc BETWEEN ? AND ?', $where->getSQL(null));
    }
    
    function testGetParameter() {
        $parameter = new Parameter(5);
        $where = new Where('AND', (new Column('abc', null, false)), '=', $parameter);
        self::assertSame($parameter, $where->getParameter());
    }
    
    function testGetParametersNullOp() {
        $where = new Where(null, (new Column('abc', null, false)), null, null);
        self::assertSame(array(), $where->getParameters());
    }
    
    function testGetParametersNullValue() {
        $where = new Where('AND', (new Column('abc', null, false)), 'IS NULL', null);
        self::assertSame(array(), $where->getParameters());
    }
    
    function testGetParametersParam() {
        $parameter = new Parameter(5);
        $where = new Where('AND', (new Column('abc', null, false)), '=', $parameter);
        self::assertSame(array($parameter), $where->getParameters());
    }
    
    function testGetParametersIn() {
        $parameter = new Parameter(array(5));
        $where = new Where('AND', (new Column('abc', null, false)), 'IN', $parameter);
        
        self::assertEquals(array(
            (new Parameter(5, true))
        ), $where->getParameters());
    }
    
    function testGetParametersInNoArray() {
        $parameter = new Parameter(5);
        $where = new Where('AND', (new Column('abc', null, false)), 'IN', $parameter);
        
        $this->expectException(\LogicException::class);
        $where->getParameters();
    }
    
    function testGetParametersBetween() {
        $parameter = new BetweenParameter((new Parameter(5)), (new Parameter(2)));
        $where = new Where('OR', (new Column('abc', null, false)), 'BETWEEN', $parameter);
        self::assertSame($parameter->getValue(), $where->getParameters());
    }
}
