<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

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
        'NOT SIMILAR TO', 'NOT ILIKE', '~~*', '!~~*'
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
    static function createWhere(?string $constraint, $column, ?string $operator, $value = null): \Plasma\SQL\QueryExpressions\Where {
        $operator = ($operator ? \strtoupper($operator) : $operator);
        if($operator !== null && !\in_array($operator, static::$operators, true)) {
            throw new \InvalidArgumentException('Invalid operator given');
        }
        
        if(
            $column !== null &&
            !($column instanceof \Plasma\SQL\QueryExpressions\Column) &&
            !($column instanceof \Plasma\SQL\QueryExpressions\Fragment)
        ) {
            $column = new \Plasma\SQL\QueryExpressions\Column($column);
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
     * @return string
     */
    function getWhere(): string {
        return \implode(' ', \array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) {
            return $where->getSQL();
        }, $this->clausels));
    }
    
    /**
     * Get the parameters.
     * @return \Plasma\SQL\QueryExpressions\Parameter[]
     */
    function getParameters(): array {
        return \array_merge(...\array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) {
            return $where->getParameters();
        }, $this->clausels));
    }
    
    // ---  Creating expressions or values for ease use  --- //
    
    /**
     * Creates a new BetweenParameter for the two between values.
     * Delegates to `QueryBuilder::between`.
     * @param mixed|QueryExpressions\Fragment  $first
     * @param mixed|QueryExpressions\Fragment  $second
     * @return \Plasma\SQL\QueryExpressions\BetweenParameter
     * @see \Plasma\SQL\QueryBuilder::between
     */
    function between($first, $second): \Plasma\SQL\QueryExpressions\BetweenParameter {
        return \Plasma\SQL\QueryBuilder::between($first, $second);
    }
    
    /**
     * Creates a new Fragment. All placeholders `?` in the operation string will be replaced by the following arguments.
     * Delegates to `QueryBuilder::fragment`.
     * @param string  $operation
     * @param string  ...$placeholders
     * @return \Plasma\SQL\QueryExpressions\Fragment
     * @see \Plasma\SQL\QueryBuilder::fragment
     */
    function fragment(string $operation, string ...$placeholders): \Plasma\SQL\QueryExpressions\Fragment {
        return \Plasma\SQL\QueryBuilder::fragment($operation, ...$placeholders);
    }
    
    /**
     * Returns `=`.
     * @return string
     */
    function equalsTo(): string {
        return '=';
    }
    
    /**
     * Returns `!=`.
     * @return string
     */
    function notEqualsTo(): string {
        return '!=';
    }
    
    /**
     * Returns `>`.
     * @return string
     */
    function greatherThan(): string {
        return '>';
    }
    
    /**
     * Returns `>=`.
     * @return string
     */
    function greaterEqualTo(): string {
        return '>=';
    }
    
    /**
     * Returns `<`.
     * @return string
     */
    function lesserThan(): string {
        return '<';
    }
    
    /**
     * Returns `<=`.
     * @return string
     */
    function lesserEqualTo(): string {
        return '<=';
    }
    
    /**
     * Returns `LIKE`.
     * @return string
     */
    function like(): string {
        return 'LIKE';
    }
    
    /**
     * Returns `NOT LIKE`.
     * @return string
     */
    function notLike(): string {
        return 'NOT LIKE';
    }
    
    /**
     * Returns `ILIKE`. Only supported by PostgreSQL.
     * @return string
     */
    function ilike(): string {
        return 'ILIKE';
    }
    
    /**
     * Returns `NOT ILIKE`. Only supported by PostgreSQL.
     * @return string
     */
    function notIlike(): string {
        return 'NOT ILIKE';
    }
    
    /**
     * Returns `IN`.
     * @return string
     */
    function in(): string {
        return 'IN';
    }
    
    /**
     * Returns `NOT IN`.
     * @return string
     */
    function notIn(): string {
        return 'NOT IN';
    }
    
    /**
     * Returns `IS NULL`.
     * @return string
     */
    function isNull(): string {
        return 'IS NULL';
    }
    
    /**
     * Returns `IS NOT NULL`.
     * @return string
     */
    function isNotNull(): string {
        return 'IS NOT NULL';
    }
    
    /**
     * Returns `BETWEEN`.
     * @return string
     */
    function between(): string {
        return 'BETWEEN';
    }
}
