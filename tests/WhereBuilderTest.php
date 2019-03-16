<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests;

class WhereBuilderTest extends \PHPUnit\Framework\TestCase {
    function testCreateWhereNullConstStrColumnStrOpStrPara() {
        $where = \Plasma\SQL\WhereBuilder::createWhere(null, 'tbd', '=', 'hello');
        $expected = new \Plasma\SQL\QueryExpressions\Where(
            null,
            (new \Plasma\SQL\QueryExpressions\Column('tbd', null, true)),
            '=',
            (new \Plasma\SQL\QueryExpressions\Parameter('hello', true))
        );
        
        $this->assertEquals($expected, $where);
        $this->assertSame('tbd = ?', $where->getSQL(null));
    }
    
    function testCreateWhereNullConstStrColumnStrOpNoPara() {
        $where = \Plasma\SQL\WhereBuilder::createWhere(null, 'tbd', 'IS NULL');
        $expected = new \Plasma\SQL\QueryExpressions\Where(
            null,
            (new \Plasma\SQL\QueryExpressions\Column('tbd', null, true)),
            'IS NULL',
            null
        );
        
        $this->assertEquals($expected, $where);
        $this->assertSame('tbd IS NULL', $where->getSQL(null));
    }
    
    function testCreateWhereNullConstStrColumnNoOpNoPara() {
        $where = \Plasma\SQL\WhereBuilder::createWhere(null, 'tbd');
        $expected = new \Plasma\SQL\QueryExpressions\Where(
            null,
            (new \Plasma\SQL\QueryExpressions\Column('tbd', null, true)),
            null,
            null
        );
        
        $this->assertEquals($expected, $where);
        $this->assertSame('tbd', $where->getSQL(null));
    }
    
    function testCreateWhereNullConstObjColumnNoOpNoPara() {
        $col = new \Plasma\SQL\QueryExpressions\Column('tbd', null, false);
        
        $where = \Plasma\SQL\WhereBuilder::createWhere(null, $col);
        $expected = new \Plasma\SQL\QueryExpressions\Where(
            null,
            $col,
            null,
            null
        );
        
        $this->assertEquals($expected, $where);
        $this->assertSame('tbd', $where->getSQL(null));
    }
    
    function testCreateWhereStrConstObjColumnNoOpNoPara() {
        $col = new \Plasma\SQL\QueryExpressions\Column('tbd', null, false);
        
        $where = \Plasma\SQL\WhereBuilder::createWhere('AND', $col);
        $expected = new \Plasma\SQL\QueryExpressions\Where(
            'AND',
            $col,
            null,
            null
        );
        
        $this->assertEquals($expected, $where);
        $this->assertSame('AND tbd', $where->getSQL(null));
    }
    
    function testCreateWhereInvalidOperator() {
        $this->expectException(\InvalidArgumentException::class);
        \Plasma\SQL\WhereBuilder::createWhere(null, 'tbd', 'abcd');
    }
    
    function testIsEmpty() {
        $where = new \Plasma\SQL\WhereBuilder();
        $this->assertTrue($where->isEmpty());
        
        $where->and('test', 'IS NULL');
        $this->assertFalse($where->isEmpty());
    }
    
    function testAnd() {
        $where = (new \Plasma\SQL\WhereBuilder())
            ->and('abc', '=', 59);
        
        $this->assertSame('abc = ?', $where->getSQL(null));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(59, true))
        ), $where->getParameters());
    }
    
    function testOr() {
        $where = (new \Plasma\SQL\WhereBuilder())
            ->or('abc', '=', 59);
        
        $this->assertSame('abc = ?', $where->getSQL(null));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(59, true))
        ), $where->getParameters());
    }
    
    function testAndOr() {
        $where = (new \Plasma\SQL\WhereBuilder())
            ->and('abc', '=', 59)
            ->or('a', '<', 50);
        
        $this->assertSame('abc = ? OR a < ?', $where->getSQL(null));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(59, true)),
            (new \Plasma\SQL\QueryExpressions\Parameter(50, true))
        ), $where->getParameters());
    }
    
    function testAndBuilder() {
        $where = (new \Plasma\SQL\WhereBuilder())
            ->and('abc', '=', 59)
            ->andBuilder(
                (new \Plasma\SQL\WhereBuilder())
                    ->and('a', 'IS NULL')
                    ->or('a', '>' , 0)
            );
        
        $this->assertSame('abc = ? AND (a IS NULL OR a > ?)', $where->getSQL(null));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(59, true)),
            (new \Plasma\SQL\QueryExpressions\Parameter(0, true))
        ), $where->getParameters());
    }
    
    function testOrBuilder() {
        $where = (new \Plasma\SQL\WhereBuilder())
            ->and('abc', '=', 59)
            ->orBuilder(
                (new \Plasma\SQL\WhereBuilder())
                    ->and('ac', 'IS NOT NULL')
                    ->or('b', '<>' , 1)
            );
        
        $this->assertSame('abc = ? OR (ac IS NOT NULL OR b <> ?)', $where->getSQL(null));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(59, true)),
            (new \Plasma\SQL\QueryExpressions\Parameter(1, true))
        ), $where->getParameters());
    }
    
    function testAndOrBuilder() {
        $where = (new \Plasma\SQL\WhereBuilder())
            ->and('abc', '=', 59)
            ->andBuilder(
                (new \Plasma\SQL\WhereBuilder())
                    ->and('a', 'IS NULL')
                    ->or('a', '>' , 0)
            )
            ->orBuilder(
                (new \Plasma\SQL\WhereBuilder())
                    ->and('ac', 'IS NOT NULL')
                    ->or('b', '<>' , 1)
                    ->andBuilder(
                        (new \Plasma\SQL\WhereBuilder())
                            ->and('js', '=', 'BAD')
                    )
            );
        
        $this->assertSame('abc = ? AND (a IS NULL OR a > ?) OR (ac IS NOT NULL OR b <> ? AND (js = ?))', $where->getSQL(null));
        $this->assertEquals(array(
            (new \Plasma\SQL\QueryExpressions\Parameter(59, true)),
            (new \Plasma\SQL\QueryExpressions\Parameter(0, true)),
            (new \Plasma\SQL\QueryExpressions\Parameter(1, true)),
            (new \Plasma\SQL\QueryExpressions\Parameter('BAD', true))
        ), $where->getParameters());
    }
}
