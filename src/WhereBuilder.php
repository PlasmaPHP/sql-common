<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

/**
 * Used to build more complex WHERE and HAVING clausels.
 */
class WhereBuilder {
    /**
     * @var \Plasma\SQL\QueryExpressions\WhereInterface[]
     */
    protected $clausels = array();
    
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
     * @return \Plasma\SQL\QueryExpressions\Where
     * @throws \InvalidArgumentException
     */
    static function createWhere(?string $constraint, $column, ?string $operator = null, $value = null): \Plasma\SQL\QueryExpressions\Where {
        $operator = ($operator !== null ? \strtoupper($operator) : $operator);
        if($operator !== null && !\in_array($operator, static::$operators, true)) {
            throw new \InvalidArgumentException('Invalid operator given');
        }
        
        if(
            !($column instanceof \Plasma\SQL\QueryExpressions\Column) &&
            !($column instanceof \Plasma\SQL\QueryExpressions\Fragment)
        ) {
            $column = new \Plasma\SQL\QueryExpressions\Column($column, null, true);
        }
        
        if(
            $value !== null &&
            !($value instanceof \Plasma\SQL\QueryExpressions\Parameter)
        ) {
            $value = new \Plasma\SQL\QueryExpressions\Parameter($value, true);
        }
        
        return (new \Plasma\SQL\QueryExpressions\Where($constraint, $column, $operator, $value));
    }
    
    /**
     * Put the previous WHERE clausel with a logical AND constraint to this WHERE clausel.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function and($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->clausels) ? null : 'AND');
        $this->clausels[] = static::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clausel with a logical OR constraint to this WHERE clausel.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function or($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->clausels) ? null : 'OR');
        $this->clausels[] = static::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Put the WHERE builder with a logical AND constraint to this builder. The WHERE clausel of the builder gets wrapped into parenthesis.
     * @param self  $builder
     * @return $this
     */
    function andBuilder(self $builder): self {
        $constraint = (empty($this->clausels) ? null : 'AND');
        $this->clausels[] = new \Plasma\SQL\QueryExpressions\WhereBuilder($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Put the WHERE builder with a logical OR constraint to this builder. The WHERE clausel of the builder gets wrapped into parenthesis.
     * @param self  $builder
     * @return $this
     */
    function orBuilder(self $builder): self {
        $constraint = (empty($this->clausels) ? null : 'OR');
        $this->clausels[] = new \Plasma\SQL\QueryExpressions\WhereBuilder($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Whether the where builder is empty (no clausels).
     * @return bool
     */
    function isEmpty(): bool {
        return empty($this->clausels);
    }
    
    /**
     * Get the SQL string for the where clausel.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        return \implode(' ', \array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) use ($grammar) {
            return $where->getSQL($grammar);
        }, $this->clausels));
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        if(empty($this->clausels)) {
            return array();
        }
        
        return \array_merge(...\array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) {
            return $where->getParameters();
        }, $this->clausels));
    }
}
