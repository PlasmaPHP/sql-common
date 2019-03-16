<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

/**
 * Represents a fragmented WHERE clausel.
 */
class FragmentedWhere implements WhereInterface {
    /**
     * @var string|null
     */
    protected $constraint;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Fragment
     */
    protected $fragment;
    
    /**
     * @var \Plasma\SQL\WhereBuilder
     */
    protected $builder;
    
    /**
     * Constructor.
     * @param string|null                               $constraint
     * @param \Plasma\SQL\QueryExpressions\Fragment     $fragment
     * @param \Plasma\SQL\WhereBuilder                  $builder
     */
    function __construct(?string $constraint, \Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder) {
        $this->constraint = $constraint;
        $this->fragment = $fragment;
        $this->builder = $builder;
    }
    
    /**
     * Get the SQL string for this.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \LogicException
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        $where = $this->fragment->getSQL();
        $pos = \strpos($where, '$$');
        
        if($pos === false) {
            throw new \LogicException('Fragmented WHERE clausel has no "$$" to inject the WHERE clausel into the fragment');
        }
        
        $sql = \substr($where, 0, $pos).$this->builder->getSQL($grammar).\substr($where, ($pos + 2));
        return ($this->constraint ? $this->constraint.' ' : '').$sql;
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        return $this->builder->getParameters();
    }
}
