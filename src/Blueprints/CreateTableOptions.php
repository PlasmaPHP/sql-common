<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Blueprints;

class CreateTableOptions implements CreateTableOptionsInterface {
    /**
     * @var \Plasma\SQL\Blueprints\ColumnIndexBlueprint[]
     */
    protected $indexes = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\ForeignKey[]
     */
    protected $foreignKeys = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\References[]
     */
    protected $references = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Constraint[]
     */
    protected $constraints = array();
    
    /**
     * @var string[]
     */
    protected $checks = array();
    
    /**
     * Creates a new `CREATE TABLE` options.
     * @return $this
     */
    static function create() {
        return (new static());
    }
    
    /**
     * Turns the options into a SQL query.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \RuntimeException
     * @throws \Plasma\Exception
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        
    }
}
