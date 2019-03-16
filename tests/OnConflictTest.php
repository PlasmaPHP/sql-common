<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Tests;

class OnConflictTest extends \PHPUnit\Framework\TestCase {
    function testConstructorType() {
        new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_ERROR);
        new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_DO_NOTHING);
        new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_ALL);
        new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\InvalidArgumentException::class);
        new \Plasma\SQL\OnConflict(\PHP_INT_MAX);
    }
    
    function testGetType() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        $this->assertSame(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS, $conf->getType());
    }
    
    function testGetReplaceColumns() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        $conf->addReplaceColumn('tbd');
        
        $col1 = new \Plasma\SQL\QueryExpressions\Column('tbd', null, true);
        $this->assertEquals(array($col1), $conf->getReplaceColumns());
    }
    
    function testGetReplaceColumns2() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $col1 = new \Plasma\SQL\QueryExpressions\Column('tbd', null, true);
        $col2 = new \Plasma\SQL\QueryExpressions\Column('tba', null, false);
        
        $conf->addReplaceColumn($col1)
            ->addReplaceColumn($col2);
        
        $this->assertSame(array($col1, $col2), $conf->getReplaceColumns());
    }
    
    function testGetReplaceColumnsInvalidArg() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\InvalidArgumentException::class);
        $conf->addReplaceColumn(5);
    }
    
    function testGetConflictTargets() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        $conf->addConflictTarget('tbd');
        
        $col1 = new \Plasma\SQL\QueryExpressions\Column('tbd', null, true);
        $this->assertEquals(array($col1), $conf->getConflictTargets());
    }
    
    function testGetConflictTargets2() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $col1 = new \Plasma\SQL\QueryExpressions\Column('tbd', null, false);
        $col2 = new \Plasma\SQL\QueryExpressions\Constraint('tba');
        
        $conf->addConflictTarget($col1)
            ->addConflictTarget($col2);
        
        $this->assertEquals(array($col1, $col2), $conf->getConflictTargets());
    }
    
    function testGetConflictTargetsInvalidArg() {
        $conf = new \Plasma\SQL\OnConflict(\Plasma\SQL\OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\InvalidArgumentException::class);
        $conf->addConflictTarget(5);
    }
}
