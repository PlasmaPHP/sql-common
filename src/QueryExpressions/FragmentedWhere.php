<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\QueryExpressions;

use Plasma\SQL\GrammarInterface;
use Plasma\SQL\WhereBuilder;

/**
 * Represents a fragmented WHERE clause.
 */
class FragmentedWhere implements WhereInterface {
    /**
     * @var string|null
     */
    protected $constraint;
    
    /**
     * @var Fragment
     */
    protected $fragment;
    
    /**
     * @var WhereExpression
     */
    protected $builder;
    
    /**
     * Constructor.
     * @param string|null   $constraint
     * @param Fragment      $fragment
     * @param WhereBuilder  $builder
     */
    function __construct(?string $constraint, Fragment $fragment, WhereBuilder $builder) {
        $this->constraint = $constraint;
        $this->fragment = $fragment;
        $this->builder = $builder;
    }
    
    /**
     * Get the SQL string for this.
     * @param GrammarInterface|null  $grammar
     * @return string
     * @throws \LogicException
     */
    function getSQL(?GrammarInterface $grammar): string {
        $where = $this->fragment->getSQL();
        $pos = \strpos($where, '$$');
        
        if($pos === false) {
            throw new \LogicException('Fragmented WHERE clause has no "$$" to inject the WHERE clause into the fragment');
        }
        
        $sql = \substr($where, 0, $pos).$this->builder->getSQL($grammar).\substr($where, ($pos + 2));
        return ($this->constraint ? $this->constraint.' ' : '').$sql;
    }
    
    /**
     * Get the parameters.
     * @return Parameter[]
     */
    function getParameters(): array {
        return $this->builder->getParameters();
    }
}
