<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests;

class QueryBuilderTest extends \PHPUnit\Framework\TestCase {
    function testCreateAndGrammar() {
        $query = \Plasma\SQL\QueryBuilder::create();
        $query2 = $query->withGrammar((new \Plasma\SQL\Grammar\MySQL()));
        
        $this->assertNotSame($query, $query2);
    }
    
    function testBetween() {
        $between = \Plasma\SQL\QueryBuilder::between('abc', 'efg');
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\BetweenParameter::class, $between);
        
        $this->assertTrue($between->hasValue());
        
        [ $first, $second ] = $between->getValue();
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\Parameter::class, $first);
        $this->assertSame('abc', $first->getValue());
        
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\Parameter::class, $second);
        $this->assertSame('efg', $second->getValue());
    }
    
    function testColumn() {
        $column = \Plasma\SQL\QueryBuilder::column('abc', 'a', false);
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\Column::class, $column);
        
        $expected = new \Plasma\SQL\QueryExpressions\Column('abc', 'a', false);
        $this->assertEquals($expected, $column);
    }
    
    function testFragment() {
        $fragment = \Plasma\SQL\QueryBuilder::fragment('ABC()');
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\Fragment::class, $fragment);
        
        $this->assertSame('ABC()', $fragment->getSQL());
    }
    
    function testFragment1() {
        $fragment = \Plasma\SQL\QueryBuilder::fragment('ABC(?)', 'e');
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\Fragment::class, $fragment);
        
        $this->assertSame('ABC(e)', $fragment->getSQL());
    }
    
    function testFragment2() {
        $fragment = \Plasma\SQL\QueryBuilder::fragment('ABC(?, ?)', 'e', 'f');
        $this->assertInstanceOf(\Plasma\SQL\QueryExpressions\Fragment::class, $fragment);
        
        $this->assertSame('ABC(e, f)', $fragment->getSQL());
    }
    
    function testGetQueryNoTable() {
        $this->expectException(\Plasma\Exception::class);
        \Plasma\SQL\QueryBuilder::create()->getQuery();
    }
    
    function testGetParametersNoTable() {
        $this->expectException(\Plasma\Exception::class);
        \Plasma\SQL\QueryBuilder::create()->getParameters();
    }
    
    function testGetQueryNoType() {
        $this->expectException(\Plasma\Exception::class);
        \Plasma\SQL\QueryBuilder::create()->from('tests')->getQuery();
    }
    
    function testGetParametersNoType() {
        $this->expectException(\Plasma\Exception::class);
        \Plasma\SQL\QueryBuilder::create()->from('tests')->getParameters();
    }
    
    function testGetParametersWithNoValue() {
        $this->expectException(\Plasma\Exception::class);
        \Plasma\SQL\QueryBuilder::create()
            ->from('tests')
            ->insert(array('a' => (new \Plasma\SQL\QueryExpressions\Parameter())))
            ->getParameters();
    }
}
