<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\Parameter;
use Plasma\SQL\QueryExpressions\Where;
use Plasma\SQL\QueryExpressions\WhereInterface;

/**
 * Used to build more complex WHERE and HAVING clauses.
 */
class WhereBuilder {
    /**
     * @var WhereInterface[]
     */
    protected $clauses = array();
    
    /**
     * All known and allowed operators.
     * @var string[]
     */
    protected static $operators = array(
        '=', '<', '>', '<=', '>=', '<>', '!=', '<=>',
        'LIKE', 'LIKE BINARY', 'NOT LIKE', 'ILIKE',
        '&', '|', '^', '<<', '>>',
        'RLIKE', 'REGEXP', 'NOT REGEXP',
        '~', '~*', '!~', '!~*', 'SIMILAR TO',
        'NOT SIMILAR TO', 'NOT ILIKE', '~~*', '!~~*',
        'IN', 'NOT IN', 'BETWEEN', 'IS NULL', 'IS NOT NULL'
    );
    
    /**
     * Creates a WHERE expression.
     * @param string|null                                               $constraint
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return Where
     * @throws \InvalidArgumentException
     */
    static function createWhere(?string $constraint, $column, ?string $operator = null, $value = null): Where {
        $operator = ($operator !== null ? \strtoupper($operator) : $operator);
        if($operator !== null && !\in_array($operator, static::$operators, true)) {
            throw new \InvalidArgumentException('Invalid operator given');
        }
        
        if(
            !($column instanceof Column) &&
            !($column instanceof Fragment)
        ) {
            $column = new Column($column, null, true);
        }
        
        if(
            $value !== null &&
            !($value instanceof Parameter)
        ) {
            $value = new Parameter($value, true);
        }
        
        return (new Where($constraint, $column, $operator, $value));
    }
    
    /**
     * Put the previous WHERE clause with a logical AND constraint to this WHERE clause.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function and($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->clauses) ? null : 'AND');
        $this->clauses[] = static::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clause with a logical OR constraint to this WHERE clause.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function or($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->clauses) ? null : 'OR');
        $this->clauses[] = static::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Put the WHERE builder with a logical AND constraint to this builder. The WHERE clause of the builder gets wrapped into parenthesis.
     * @param self  $builder
     * @return $this
     */
    function andBuilder(self $builder): self {
        $constraint = (empty($this->clauses) ? null : 'AND');
        $this->clauses[] = new QueryExpressions\WhereExpression($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Put the WHERE builder with a logical OR constraint to this builder. The WHERE clause of the builder gets wrapped into parenthesis.
     * @param self  $builder
     * @return $this
     */
    function orBuilder(self $builder): self {
        $constraint = (empty($this->clauses) ? null : 'OR');
        $this->clauses[] = new QueryExpressions\WhereExpression($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Whether the where builder is empty (no clauses).
     * @return bool
     */
    function isEmpty(): bool {
        return empty($this->clauses);
    }
    
    /**
     * Get the SQL string for the where clause.
     * Placeholders use `?`.
     * @param GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?GrammarInterface $grammar): string {
        return \implode(' ', \array_map(static function (WhereInterface $where) use ($grammar) {
            return $where->getSQL($grammar);
        }, $this->clauses));
    }
    
    /**
     * Get the parameters.
     * @return Parameter[]
     */
    function getParameters(): array {
        if(empty($this->clauses)) {
            return array();
        }
        
        return \array_merge(...\array_map(static function (WhereInterface $where) {
            return $where->getParameters();
        }, $this->clauses));
    }
}
