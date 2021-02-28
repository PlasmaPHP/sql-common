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
use Plasma\SQL\OnConflict;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Constraint;

class OnConflictTest extends TestCase {
    function testConstructorType() {
        new OnConflict(OnConflict::RESOLUTION_ERROR);
        new OnConflict(OnConflict::RESOLUTION_DO_NOTHING);
        new OnConflict(OnConflict::RESOLUTION_REPLACE_ALL);
        new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\InvalidArgumentException::class);
        new OnConflict(\PHP_INT_MAX);
    }
    
    function testGetType() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        self::assertSame(OnConflict::RESOLUTION_REPLACE_COLUMNS, $conf->getType());
    }
    
    function testGetReplaceColumns() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        $conf->addReplaceColumn('tbd');
        
        $col1 = new Column('tbd', null, true);
        self::assertEquals(array($col1), $conf->getReplaceColumns());
    }
    
    function testGetReplaceColumns2() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $col1 = new Column('tbd', null, true);
        $col2 = new Column('tba', null, false);
        
        $conf->addReplaceColumn($col1)
            ->addReplaceColumn($col2);
        
        self::assertSame(array($col1, $col2), $conf->getReplaceColumns());
    }
    
    function testGetReplaceColumnsInvalidArg() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\InvalidArgumentException::class);
        $conf->addReplaceColumn(5);
    }
    
    function testGetConflictTargets() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        $conf->addConflictTarget('tbd');
        
        $col1 = new Column('tbd', null, true);
        self::assertEquals(array($col1), $conf->getConflictTargets());
    }
    
    function testGetConflictTargets2() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $col1 = new Column('tbd', null, false);
        $col2 = new Constraint('tba');
        
        $conf->addConflictTarget($col1)
            ->addConflictTarget($col2);
        
        self::assertEquals(array($col1, $col2), $conf->getConflictTargets());
    }
    
    function testGetConflictTargetsInvalidArg() {
        $conf = new OnConflict(OnConflict::RESOLUTION_REPLACE_COLUMNS);
        
        $this->expectException(\InvalidArgumentException::class);
        $conf->addConflictTarget(5);
    }
}
