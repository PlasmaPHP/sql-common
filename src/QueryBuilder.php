<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

class QueryBuilder implements \Plasma\SQLQueryBuilderInterface {
    /**
     * @var int
     * @internal
     */
    const QUERY_TYPE_SELECT = 0x1;
    
    /**
     * @var int
     * @internal
     */
    const QUERY_TYPE_INSERT = 0x2;
    
    /**
     * @var int
     * @internal
     */
    const QUERY_TYPE_UPDATE = 0x3;
    
    /**
     * @var int
     * @internal
     */
    const QUERY_TYPE_DELETE = 0x4;
    
    /**
     * The type of the query.
     * @var int
     */
    protected $type = static::QUERY_TYPE_SELECT;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Table
     */
    protected $table;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Subquery[]
     */
    protected $subqueries = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\UnionInterface[]
     */
    protected $unions = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Join[]
     */
    protected $joins = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Column[]
     */
    protected $selects = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Parameter[]
     */
    protected $parameters = array();
    
    /**
     * @var \Plasma\SQL\OnConflict|null
     */
    protected $onConflict;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\WhereInterface[]
     */
    protected $havings = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\WhereInterface[]
     */
    protected $wheres = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\OrderBy[]
     */
    protected $orderBys = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\GroupBy[]
     */
    protected $groupBys = array();
    
    /**
     * @var int|null
     */
    protected $limit;
    
    /**
     * @var int|null
     */
    protected $offset;
    
    /**
     * @var array  TODO
     */
    protected $associations = array();
    
    /**
     * @var bool
     */
    protected $distinct = false;
    
    /**
     * @var bool
     */
    protected $returning = false;
    
    /**
     * @var mixed|null  TODO
     */
    protected $lock;
    
    /**
     * @var string|null  TODO
     */
    protected $prefix;
    
    /**
     * @var string|null  TODO
     */
    protected $sources;
    
    /**
     * @var array
     */
    protected $windows = array();
    
    /**
     * @var \Plasma\SQL\GrammarInterface|null
     */
    protected $grammar;
    
    /**
     * Constructor.
     */
    protected function __construct() {
        
    }
    
    /**
     * Creates a new instance of the querybuilder.
     * @return self
     */
    static function create(): \Plasma\QueryBuilderInterface {
        return (new static());
    }
    
    /**
     * Creates a new instance of the querybuilder with a grammar.
     * @param \Plasma\SQL\GrammarInterface  $grammar
     * @return self
     */
    static function createWithGrammar(\Plasma\SQL\GrammarInterface $grammar): self {
        $qb = new static();
        $qb->grammar = $grammar;
        
        return $qb;
    }
    
    /**
     * Creates a new BetweenParameter for the two between values.
     * @param mixed|QueryExpressions\Fragment  $first
     * @param mixed|QueryExpressions\Fragment  $second
     * @return \Plasma\SQL\QueryExpressions\BetweenParameter
     */
    static function between($first, $second): \Plasma\SQL\QueryExpressions\BetweenParameter {
        if(!($first instanceof \Plasma\SQL\QueryExpressions\Fragment) && !($first instanceof \Plasma\SQL\QueryExpressions\Parameter)) {
            $first = new \Plasma\SQL\QueryExpressions\Parameter($first);
        }
        
        if(!($second instanceof \Plasma\SQL\QueryExpressions\Fragment) && !($second instanceof \Plasma\SQL\QueryExpressions\Parameter)) {
            $second = new \Plasma\SQL\QueryExpressions\Parameter($second);
        }
        
        return (new \Plasma\SQL\QueryExpressions\BetweenParameter($first, $second));
    }
    
    /**
     * Creates a new Fragment. All placeholders `?` in the operation string will be replaced by the following arguments.
     * @param string  $operation
     * @param string  ...$placeholders
     * @return \Plasma\SQL\QueryExpressions\Fragment
     */
    static function fragment(string $operation, string ...$placeholders): \Plasma\SQL\QueryExpressions\Fragment {
        $i = 0;
        $len = \count($placeholders);
        
        while($len > $i && ($pos = \strpos($operation, '?')) !== false) {
            $operation = \substr($operation, 0, $pos).$placeholders[($i++)].\substr($operation, ($pos + 1));
        }
        
        return (new \Plasma\SQL\QueryExpressions\Fragment($operation));
    }
    
    /**
     * Clones the querybuilder and sets the grammar.
     * @param \Plasma\SQL\GrammarInterface  $grammar
     * @return self
     */
    function withGrammar(\Plasma\SQL\GrammarInterface $grammar): self {
        $qb = clone $this;
        $qb->grammar = $grammar;
        
        return $qb;
    }
    
    /**
     * Sets the target table to the given table.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the table name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     *
     */
    function from(string $table, ?string $as = null, array $options = array()): self {
        $this->table = new \Plasma\SQL\QueryExpressions\Table($table, $as, ($options['allowEscape'] ?? true));
        return $this;
    }
    
    /**
     * Adds a DISTINCT flag to this query.
     * @param bool  $flag
     * @return $this
     */
    function distinct(): self {
        $this->distinct = true;
        return $this;
    }
    
    /**
     * Adds a RETURNING flag to this query.
     * Not all DBMS support this!
     * @return $this
     */
    function returning(): self {
        $this->returning = true;
        return $this;
    }
    
    /**
     * Select columns with an optional column alias (as the key).
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the table name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string|QueryExpressions\Fragment|string[]|QueryExpressions\Fragment[]  $columns
     * @param array                                                                  $options
     * @return $this
     */
    function select($columns = array('*'), array $options = array()): self {
        $this->type = static::QUERY_TYPE_SELECT;
        $baseEscape = ($options['allowEscape'] ?? true);
        
        if(!\is_array($columns)) {
            $columns = array($columns);
        }
        
        $this->selects = array();
        foreach($columns as $key => $column) {
            $allowEscape = ($baseEscape && !($column instanceof \Plasma\SQL\QueryExpressions\Fragment));
            
            $this->selects[] = new \Plasma\SQL\QueryExpressions\Column(
                $column,
                (\is_string($key) ? $key : null),
                $allowEscape
            );
        }
        
        return $this;
    }
    
    /**
     * Insert a row.
     * If you want to insert multiple rows, pass in `Parameter`s as values,
     * prepare the query once and execute it multiple times while setting
     * the `Parameter` values.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the table name is allowed, defaults to true)
     *     'onConflict' => OnConflict, (describes ON CONFLICT resolution)
     * )
     * ```
     *
     * @param array  $row
     * @param array  $options
     * @return $this
     * @throws \InvalidArgumentException
     */
    function insert(array $row, array $options = array()): self {
        if(isset($options['onConflict'])) {
            if(!($options['onConflict'] instanceof \Plasma\SQL\OnConflict)) {
                throw new \InvalidArgumentException('Invalid ON CONFLICT resolution - not an OnConflict instance');
            }
            
            $this->onConflict = $options['onConflict'];
        }
        
        $this->type = static::QUERY_TYPE_INSERT;
        
        $this->selects = array();
        $this->parameters = array();
        
        foreach($row as $column => $value) {
            $this->selects[] = new \Plasma\SQL\QueryExpressions\Column(
                $column,
                null,
                ($options['allowEscape'] ?? true)
            );
            
            $usable = (
                $value instanceof \Plasma\SQL\QueryExpressions\Fragment ||
                $value instanceof \Plasma\SQL\QueryExpressions\Parameter
            );
            
            $this->parameters[] = (
                $usable ?
                $value :
                (new \Plasma\SQL\QueryExpressions\Parameter($value, true))
            );
        }
        
        return $this;
    }
    
    /**
     * Updates the rows passing the selection.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the table name is allowed, defaults to true)
     * )
     * ```
     *
     * @param array  $row
     * @param array  $options
     * @return $this
     */
    function update(array $row, array $options = array()): self {
        $this->type = static::QUERY_TYPE_UPDATE;
        
        $this->selects = array();
        $this->parameters = array();
        
        foreach($row as $column => $value) {
            $this->selects[] = new \Plasma\SQL\QueryExpressions\Column(
                $column,
                null,
                ($options['allowEscape'] ?? true)
            );
            
            $usable = (
                $value instanceof \Plasma\SQL\QueryExpressions\Fragment ||
                $value instanceof \Plasma\SQL\QueryExpressions\Parameter
            );
            
            $this->parameters[] = (
                $usable ?
                $value :
                (new \Plasma\SQL\QueryExpressions\Parameter($value, true))
            );
        }
        
        return $this;
    }
    
    /**
     * Deletes rows passing the selection.
     * @return $this
     */
    function delete(): self {
        $this->type = static::QUERY_TYPE_DELETE;
        return $this;
    }
    
    /**
     * Adds a JOIN query with the table and optional alias.
     * @param string       $table
     * @param string|null  $as
     * @return $this
     */
    function join(string $table, ?string $as = null): self {
        $this->buildJoin('INNER', $table, $as);
        return $this;
    }
    
    /**
     * Adds a INNER JOIN query with the table and optional alias.
     * @param string       $table
     * @param string|null  $as
     * @return $this
     */
    function innerJoin(string $table, ?string $as = null): self {
        $this->buildJoin('INNER', $table, $as);
        return $this;
    }
    
    /**
     * Adds a OUTER JOIN query with the table and optional alias.
     * @param string       $table
     * @param string|null  $as
     * @return $this
     */
    function outerJoin(string $table, ?string $as = null): self {
        $this->buildJoin('OUTER', $table, $as);
        return $this;
    }
    
    /**
     * Adds a JOIN query with the table and optional alias.
     * @param string       $table
     * @param string|null  $as
     * @return $this
     */
    function leftJoin(string $table, ?string $as = null): self {
        $this->buildJoin('LEFT', $table, $as);
        return $this;
    }
    
    /**
     * Adds a RIGHT JOIN query with the table and optional alias.
     * @param string       $table
     * @param string|null  $as
     * @return $this
     */
    function rightJoin(string $table, ?string $as = null): self {
        $this->buildJoin('RIGHT', $table, $as);
        return $this;
    }
    
    /**
     * Adds an `ON` expression to the last `JOIN` expression.
     * One `JOIN` expression can have multiple `ON` expressions.
     * @param string  $leftside
     * @param string  $rightside
     * @return $this
     * @throws \Plasma\Exception
     */
    function on(string $leftside, string $rightside): self {
        $on = new \Plasma\SQL\QueryExpressions\On($leftside, $rightside);
        
        /** @var \Plasma\SQL\QueryExpressions\Join|false  $join */
        $join = \current($this->joins);
        
        if(!$join) {
            throw new \Plasma\Exception('Invalid ON position - there is no JOIN expression');
        }
        
        /** @var \Plasma\SQL\QueryExpressions\Join  $join */
        $join->addOn($on);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clausel with a logical AND constraint to this WHERE clausel.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function where($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = \Plasma\SQL\WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
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
    function orWhere($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = \Plasma\SQL\WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Extended where building. The callback gets a `WhereBuilder` instance, where the callback is supposed to build the WHERE clausel.
     * The WHERE clausel gets wrapped into parenthesis and with an AND constraint coupled to the previous one.
     * @param callable  $where  Callback signature: `function (\Plasma\SQL\WhereBuilder $qb): void`.
     * @return $this
     * @throws \Plasma\Exception
     */
    function whereExt(callable $where): self {
        $builder = new \Plasma\SQL\WhereBuilder();
        $where($builder);
        
        if($builder->isEmpty()) {
            throw new \Plasma\Exception('Given callable did nothing with the where builder');
        }
        
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = new \Plasma\SQL\QueryExpressions\WhereBuilder($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Extended where building. The callback gets a `WhereBuilder` instance, where the callback is supposed to build the WHERE clausel.
     * The WHERE clausel gets wrapped into parenthesis and with an OR constraint coupled to the previous one.
     * @param callable  $where  Callback signature: `function (\Plasma\SQL\WhereBuilder $qb): void`.
     * @return $this
     * @throws \Plasma\Exception
     */
    function orWhereExt(callable $where): self {
        $builder = new \Plasma\SQL\WhereBuilder();
        $where($builder);
        
        if($builder->isEmpty()) {
            throw new \Plasma\Exception('Given callable did nothing with the where builder');
        }
        
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = new \Plasma\SQL\QueryExpressions\WhereBuilder($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clausel with a logical AND constraint to this fragmented WHERE clausel.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the WHERE clausel (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     * @throws \InvalidArgumentException
     */
    function whereFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $sql = $fragment->getSQL();
        $pos = \strpos($sql, '$$');
        
        if($pos === false) {
            throw new \InvalidArgumentException('Invalid fragment given - can not find "$$" in the fragment');
        }
        
        $sql = \substr($sql, 0, $pos).$builder->getWhere().\substr($sql, ($pos + 2));
        $parameters = $builder->getParameters();
        
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $sql, $parameters);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clausel with a logical OR constraint to this fragmented WHERE clausel.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the WHERE clausel (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     * @throws \InvalidArgumentException
     */
    function orWhereFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $sql = $fragment->getSQL();
        $pos = \strpos($sql, '$$');
        
        if($pos === false) {
            throw new \InvalidArgumentException('Invalid fragment given - can not find "$$" in the fragment');
        }
        
        $sql = \substr($sql, 0, $pos).$builder->getWhere().\substr($sql, ($pos + 2));
        $parameters = $builder->getParameters();
        
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $sql, $parameters);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clausel with a logical AND constraint to this HAVING clausel.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function having($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = \Plasma\SQL\WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clausel with a logical OR constraint to this HAVING clausel.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function orHaving($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = \Plasma\SQL\WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Extended having building. The callback gets a `WhereBuilder` instance, where the callback is supposed to build the HAVING clausel.
     * The HAVING clausel gets wrapped into parenthesis and with an AND constraint coupled to the previous one.
     * Since the HAVING clausel is syntax-wise the same as the WHERE clausel, the WhereBuilder gets used for HAVING, too.
     * @param callable  $having  Callback signature: `function (\Plasma\SQL\WhereBuilder $qb): void`.
     * @return $this
     * @throws \Plasma\Exception
     */
    function havingExt(callable $having): self {
        $builder = new \Plasma\SQL\WhereBuilder();
        $having($builder);
        
        if($builder->isEmpty()) {
            throw new \Plasma\Exception('Given callable did nothing with the having builder');
        }
        
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = new \Plasma\SQL\QueryExpressions\WhereBuilder($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Extended having building. The callback gets a `WhereBuilder` instance, where the callback is supposed to build the HAVING clausel.
     * The HAVING clausel gets wrapped into parenthesis and with an OR constraint coupled to the previous one.
     * Since the HAVING clausel is syntax-wise the same as the WHERE clausel, the WhereBuilder gets used for HAVING, too.
     * @param callable  $having  Callback signature: `function (\Plasma\SQL\WhereBuilder $qb): void`.
     * @return $this
     * @throws \Plasma\Exception
     */
    function orHavingExt(callable $having): self {
        $builder = new \Plasma\SQL\WhereBuilder();
        $having($builder);
        
        if($builder->isEmpty()) {
            throw new \Plasma\Exception('Given callable did nothing with the having builder');
        }
        
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = new \Plasma\SQL\QueryExpressions\WhereBuilder($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clausel with a logical AND constraint to this fragmented HAVING clausel.
     * Since the HAVING clausel is syntax-wise the same as the WHERE clausel, the WhereBuilder gets used for HAVING, too.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somehaving to inject the HAVING clausel (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     * @throws \InvalidArgumentException
     */
    function havingFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $sql = $fragment->getSQL();
        $pos = \strpos($sql, '$$');
        
        if($pos === false) {
            throw new \InvalidArgumentException('Invalid fragment given - can not find "$$" in the fragment');
        }
        
        $sql = \substr($sql, 0, $pos).$builder->getWhere().\substr($sql, ($pos + 2));
        $parameters = $builder->getParameters();
        
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $sql, $parameters);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clausel with a logical OR constraint to this fragmented HAVING clausel.
     * Since the HAVING clausel is syntax-wise the same as the WHERE clausel, the WhereBuilder gets used for HAVING, too.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somehaving to inject the HAVING clausel (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     * @throws \InvalidArgumentException
     */
    function orHavingFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $sql = $fragment->getSQL();
        $pos = \strpos($sql, '$$');
        
        if($pos === false) {
            throw new \InvalidArgumentException('Invalid fragment given - can not find "$$" in the fragment');
        }
        
        $sql = \substr($sql, 0, $pos).$builder->getWhere().\substr($sql, ($pos + 2));
        $parameters = $builder->getParameters();
        
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $sql, $parameters);
        
        return $this;
    }
    
    /**
     * Set the limit for the `SELECT` query.
     * @param int|null  $offset
     * @return $this
     */
    function limit(?int $limit): self {
        $this->limit = $limit;
        return $this;
    }
    
    /**
     * Set the offset for the `SELECT` query.
     * @param int|null  $offset
     * @return $this
     */
    function offset(?int $offset): self {
        $this->offset = $offset;
        return $this;
    }
    
    /**
     * Add an `ORDER BY` to the query. This will aggregate.
     * @param QueryExpressions\Column|string  $column
     * @param bool                            $descending
     * @return $this
     */
    function orderBy($column, bool $descending = false): self {
        if(!($column instanceof \Plasma\SQL\QueryExpressions\Column)) {
            $column = new \Plasma\SQL\QueryExpressions\Column($column, null, false, '');
        }
        
        $this->orderBys[] = new \Plasma\SQL\QueryExpressions\OrderBy($column, $descending);
        return $this;
    }
    
    /**
     * Add an `GROUP BY` to the query. This will aggregate.
     * @param QueryExpressions\Column|string  $column
     * @return $this
     */
    function groupBy($column): self {
        if(!($column instanceof \Plasma\SQL\QueryExpressions\Column)) {
            $column = new \Plasma\SQL\QueryExpressions\Column($column, null, false, '');
        }
        
        $this->groupBys[] = new \Plasma\SQL\QueryExpressions\GroupBy($column);
        return $this;
    }
    
    /**
     * Adds a subquery to the `SELECT` query.
     * @param \Plasma\SQLQueryBuilderInterface  $subquery
     * @param string|null               $alias
     * @return $this
     */
    function subquery(\Plasma\SQLQueryBuilderInterface $subquery, ?string $alias = null): self {
        $this->subqueries[] = new \Plasma\SQL\QueryExpressions\Subquery($subquery, $alias);
        return $this;
    }
    
    /**
     * Adds an `UNION` to the `SELECT` query.
     * @param \Plasma\SQLQueryBuilderInterface  $subquery
     * @return $this
     */
    function union(\Plasma\SQLQueryBuilderInterface $query): self {
        $this->unions[] = new \Plasma\SQL\QueryExpressions\Union($query);
        return $this;
    }
    
    /**
     * Adds an `UNION ALL` to the `SELECT` query.
     * @param \Plasma\SQLQueryBuilderInterface  $subquery
     * @return $this
     */
    function unionAll(\Plasma\SQLQueryBuilderInterface $query): self {
        $this->unions[] = new \Plasma\SQL\QueryExpressions\UnionAll($query);
        return $this;
    }
    
    /**
     * Returns the query.
     * @return string
     * @throws \Plasma\Exception
     */
    function getQuery() {
        // TODO
        
        /*if($this->builtQuery === null) {
            if(empty($this->selector) || empty($this->tablename)) {
                throw new \Plasma\Exception('You need to do something first');
            }
            
            if(!empty($this->whereClausel)) {
                $where = 'WHERE '.\implode(' ', $this->whereClausel);
            } else {
                $where = '';
            }
            
            if(!empty($this->havingClausel)) {
                $having = 'HAVING '.\implode(' ', $this->havingClausel);
            } else {
                $having = '';
            }
            
            \ksort($this->options);
            $this->builtQuery = \trim($this->selector.' '.$this->tablename.$this->appendor.' '.$where.' '.\implode(' ', $this->options).' '.$having);
        }
        
        return $this->builtQuery;*/
    }
    
    /**
     * Returns the associated parameters for the query.
     * @return array
     */
    function getParameters(): array {
        // TODO
        
        //return \array_merge(\array_values($this->queryValues), \array_values($this->whereValues), \array_values($this->havingValues));
    }
    
    /**
     * Get a property. This is only for use with the grammar.
     * This method is NOT part of the public API and may change at any time.
     * @param string  $name
     * @return mixed
     * @throws \InvalidArgumentException
     * @internal
     */
    function _getProperty(string $name) {
        if(\property_exists($this, $name)) {
            return $this->$name;
        }
        
        throw new \InvalidArgumentException('Unknown property "'.$name.'"');
    }
    
    /**
     * Builds the join.
     * @param string       $type
     * @param string       $table
     * @param string|null  $as
     * @return void
     */
    protected function buildJoin(string $type, string $table, ?string $as): void {
        $table = new \Plasma\SQL\QueryExpressions\Table($table, $as);
        $join = new \Plasma\SQL\QueryExpressions\Join($type, $table);
        
        $this->joins[] = $join;
        \end($this->joins);
    }
}
