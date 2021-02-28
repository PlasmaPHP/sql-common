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
use Plasma\Exception;
use Plasma\SQL\Grammar\MySQL;
use Plasma\SQL\Grammar\PostgreSQL;
use Plasma\SQL\QueryBuilder;
use Plasma\SQL\QueryExpressions\BetweenParameter;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;

class QueryBuilderTest extends TestCase {
    function testCreateAndGrammar() {
        $query = QueryBuilder::create();
        $query2 = $query->withGrammar((new MySQL()));
        
        self::assertNotSame($query, $query2);
    }
    
    function testBetween() {
        $between = QueryBuilder::between('abc', 'efg');
        self::assertInstanceOf(BetweenParameter::class, $between);
        
        self::assertTrue($between->hasValue());
        
        [$first, $second] = $between->getValue();
        self::assertInstanceOf(Parameter::class, $first);
        self::assertSame('abc', $first->getValue());
        
        self::assertInstanceOf(Parameter::class, $second);
        self::assertSame('efg', $second->getValue());
    }
    
    function testColumn() {
        $column = QueryBuilder::column('abc', 'a', false);
        self::assertInstanceOf(Column::class, $column);
        
        $expected = new Column('abc', 'a', false);
        self::assertEquals($expected, $column);
    }
    
    function testFragment() {
        $fragment = QueryBuilder::fragment('ABC()');
        self::assertInstanceOf(Fragment::class, $fragment);
        
        self::assertSame('ABC()', $fragment->getSQL());
    }
    
    function testFragment1() {
        $fragment = QueryBuilder::fragment('ABC(?)', 'e');
        self::assertInstanceOf(Fragment::class, $fragment);
        
        self::assertSame('ABC(e)', $fragment->getSQL());
    }
    
    function testFragment2() {
        $fragment = QueryBuilder::fragment('ABC(?, ?)', 'e', 'f');
        self::assertInstanceOf(Fragment::class, $fragment);
        
        self::assertSame('ABC(e, f)', $fragment->getSQL());
    }
    
    function testFragmentEscaped() {
        $fragment = QueryBuilder::fragment('ABC(?, \?)', 'e', 'f');
        self::assertInstanceOf(Fragment::class, $fragment);
        
        self::assertSame('ABC(e, ?)', $fragment->getSQL());
    }
    
    function testFragmentEscapedWithReplaceAfter() {
        $fragment = QueryBuilder::fragment('ABC(?, \?, ?)', 'e', 'f');
        self::assertInstanceOf(Fragment::class, $fragment);
        
        self::assertSame('ABC(e, ?, f)', $fragment->getSQL());
    }
    
    function testFragmentEscaped2() {
        $fragment = QueryBuilder::fragment('ABC(?, \?, \?)', 'e', 'f', 'g');
        self::assertInstanceOf(Fragment::class, $fragment);
        
        self::assertSame('ABC(e, ?, ?)', $fragment->getSQL());
    }
    
    function testGetQueryNoTable() {
        $this->expectException(Exception::class);
        QueryBuilder::create()->getQuery();
    }
    
    function testGetParametersNoTable() {
        $this->expectException(Exception::class);
        QueryBuilder::create()->getParameters();
    }
    
    function testGetQueryNoType() {
        $this->expectException(Exception::class);
        QueryBuilder::create()->from('tests')->getQuery();
    }
    
    function testGetParametersNoType() {
        $this->expectException(Exception::class);
        QueryBuilder::create()->from('tests')->getParameters();
    }
    
    function testGetParametersWithNoValue() {
        $this->expectException(Exception::class);
        QueryBuilder::create()
            ->from('tests')
            ->insert(array('a' => (new Parameter())))
            ->getParameters();
    }
    
    function testReplacePlaceholdersAndConflictFragments() {
        $query = QueryBuilder::createWithGrammar((new PostgreSQL()))
            ->from('test')
            ->select()
            ->where('abc', '=', 5)
            ->where(
                QueryBuilder::fragment('haha(?, "?")', 'a')
            )
            ->orWhere('a', '>', 5)
            ->getQuery();
        
        self::assertSame(
            'SELECT * FROM "test" WHERE "abc" = $1 AND haha(a, "?") OR "a" > $2',
            $query
        );
    }
}
