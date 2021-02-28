<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
 * @noinspection PhpUnhandledExceptionInspection
 */

namespace Plasma\SQL\Tests;

use PHPUnit\Framework\TestCase;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Parameter;
use Plasma\SQL\QueryExpressions\Where;
use Plasma\SQL\WhereBuilder;

class WhereBuilderTest extends TestCase {
    function testCreateWhereNullConstStrColumnStrOpStrPara() {
        $where = WhereBuilder::createWhere(null, 'tbd', '=', 'hello');
        $expected = new Where(
            null,
            (new Column('tbd', null, true)),
            '=',
            (new Parameter('hello', true))
        );
        
        self::assertEquals($expected, $where);
        self::assertSame('tbd = ?', $where->getSQL(null));
    }
    
    function testCreateWhereNullConstStrColumnStrOpNoPara() {
        $where = WhereBuilder::createWhere(null, 'tbd', 'IS NULL');
        $expected = new Where(
            null,
            (new Column('tbd', null, true)),
            'IS NULL',
            null
        );
        
        self::assertEquals($expected, $where);
        self::assertSame('tbd IS NULL', $where->getSQL(null));
    }
    
    function testCreateWhereNullConstStrColumnNoOpNoPara() {
        $where = WhereBuilder::createWhere(null, 'tbd');
        $expected = new Where(
            null,
            (new Column('tbd', null, true)),
            null,
            null
        );
        
        self::assertEquals($expected, $where);
        self::assertSame('tbd', $where->getSQL(null));
    }
    
    function testCreateWhereNullConstObjColumnNoOpNoPara() {
        $col = new Column('tbd', null, false);
        
        $where = WhereBuilder::createWhere(null, $col);
        $expected = new Where(
            null,
            $col,
            null,
            null
        );
        
        self::assertEquals($expected, $where);
        self::assertSame('tbd', $where->getSQL(null));
    }
    
    function testCreateWhereStrConstObjColumnNoOpNoPara() {
        $col = new Column('tbd', null, false);
        
        $where = WhereBuilder::createWhere('AND', $col);
        $expected = new Where(
            'AND',
            $col,
            null,
            null
        );
        
        self::assertEquals($expected, $where);
        self::assertSame('AND tbd', $where->getSQL(null));
    }
    
    function testCreateWhereInvalidOperator() {
        $this->expectException(\InvalidArgumentException::class);
        WhereBuilder::createWhere(null, 'tbd', 'abcd');
    }
    
    function testIsEmpty() {
        $where = new WhereBuilder();
        self::assertTrue($where->isEmpty());
        
        $where->and('test', 'IS NULL');
        self::assertFalse($where->isEmpty());
    }
    
    function testAnd() {
        $where = (new WhereBuilder())
            ->and('abc', '=', 59);
        
        self::assertSame('abc = ?', $where->getSQL(null));
        self::assertEquals(array(
            (new Parameter(59, true))
        ), $where->getParameters());
    }
    
    function testOr() {
        $where = (new WhereBuilder())
            ->or('abc', '=', 59);
        
        self::assertSame('abc = ?', $where->getSQL(null));
        self::assertEquals(array(
            (new Parameter(59, true))
        ), $where->getParameters());
    }
    
    function testAndOr() {
        $where = (new WhereBuilder())
            ->and('abc', '=', 59)
            ->or('a', '<', 50);
        
        self::assertSame('abc = ? OR a < ?', $where->getSQL(null));
        self::assertEquals(array(
            (new Parameter(59, true)),
            (new Parameter(50, true))
        ), $where->getParameters());
    }
    
    function testAndBuilder() {
        $where = (new WhereBuilder())
            ->and('abc', '=', 59)
            ->andBuilder(
                (new WhereBuilder())
                    ->and('a', 'IS NULL')
                    ->or('a', '>', 0)
            );
        
        self::assertSame('abc = ? AND (a IS NULL OR a > ?)', $where->getSQL(null));
        self::assertEquals(array(
            (new Parameter(59, true)),
            (new Parameter(0, true))
        ), $where->getParameters());
    }
    
    function testOrBuilder() {
        $where = (new WhereBuilder())
            ->and('abc', '=', 59)
            ->orBuilder(
                (new WhereBuilder())
                    ->and('ac', 'IS NOT NULL')
                    ->or('b', '<>', 1)
            );
        
        self::assertSame('abc = ? OR (ac IS NOT NULL OR b <> ?)', $where->getSQL(null));
        self::assertEquals(array(
            (new Parameter(59, true)),
            (new Parameter(1, true))
        ), $where->getParameters());
    }
    
    function testAndOrBuilder() {
        $where = (new WhereBuilder())
            ->and('abc', '=', 59)
            ->andBuilder(
                (new WhereBuilder())
                    ->and('a', 'IS NULL')
                    ->or('a', '>', 0)
            )
            ->orBuilder(
                (new WhereBuilder())
                    ->and('ac', 'IS NOT NULL')
                    ->or('b', '<>', 1)
                    ->andBuilder(
                        (new WhereBuilder())
                            ->and('js', '=', 'BAD')
                    )
            );
        
        self::assertSame('abc = ? AND (a IS NULL OR a > ?) OR (ac IS NOT NULL OR b <> ? AND (js = ?))', $where->getSQL(null));
        self::assertEquals(array(
            (new Parameter(59, true)),
            (new Parameter(0, true)),
            (new Parameter(1, true)),
            (new Parameter('BAD', true))
        ), $where->getParameters());
    }
}
