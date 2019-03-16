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
     * Locks the row for update.
     * @var int
     * @see https://dev.mysql.com/doc/refman/8.0/en/innodb-locking-reads.html
     * @see https://www.postgresql.org/docs/9.5/explicit-locking.html#LOCKING-ROWS
     */
    const ROW_LOCKING_FOR_UPDATE = 0x1;
    
    /**
     * Locks the row for no key update.
     * @var int
     * @see https://www.postgresql.org/docs/9.5/explicit-locking.html#LOCKING-ROWS
     */
    const ROW_LOCKING_FOR_NO_KEY_UPDATE = 0x2;
    
    /**
     * Locks the row for share.
     * @var int
     * @see https://dev.mysql.com/doc/refman/8.0/en/innodb-locking-reads.html
     * @see https://www.postgresql.org/docs/9.5/explicit-locking.html#LOCKING-ROWS
     */
    const ROW_LOCKING_FOR_SHARE = 0x3;
    
    /**
     * Locks the row for key share.
     * @var int
     * @see https://www.postgresql.org/docs/9.5/explicit-locking.html#LOCKING-ROWS
     */
    const ROW_LOCKING_FOR_KEY_SHARE = 0x4;
    
    /**
     * The type of the query.
     * @var int
     */
    protected $type;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Table
     */
    protected $table;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\UnionInterface[]
     */
    protected $unions = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Join[]
     */
    protected $joins = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Column[]|\Plasma\SQL\QueryExpressions\Subquery[]
     */
    protected $selects = array();
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Fragment[]|\Plasma\SQL\QueryExpressions\Parameter[]|\Plasma\SQL\QueryExpressions\Subquery[]
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
     * @var bool
     */
    protected $distinct = false;
    
    /**
     * @var int
     */
    protected $selectRowLocking = 0;
    
    /**
     * @var bool
     */
    protected $returning = false;
    
    /**
     * @var \Plasma\SQL\GrammarInterface|null
     */
    protected $grammar;
    
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
            $first = new \Plasma\SQL\QueryExpressions\Parameter($first, !\is_null($first));
        }
        
        if(!($second instanceof \Plasma\SQL\QueryExpressions\Fragment) && !($second instanceof \Plasma\SQL\QueryExpressions\Parameter)) {
            $second = new \Plasma\SQL\QueryExpressions\Parameter($second, !\is_null($second));
        }
        
        return (new \Plasma\SQL\QueryExpressions\BetweenParameter($first, $second));
    }
    
    /**
     * Creates a new Column.
     * @param string       $name
     * @param string|null  $as
     * @param bool         $allowEscape
     * @return \Plasma\SQL\QueryExpressions\Column
     */
    static function column(string $name, ?string $as = null, bool $allowEscape = true): \Plasma\SQL\QueryExpressions\Column {
        return (new \Plasma\SQL\QueryExpressions\Column($name, $as, $allowEscape));
    }
    
    /**
     * Creates a new Fragment. All placeholders `?` in the operation string will be replaced by the following arguments.
     * @param string  $operation
     * @param mixed   ...$placeholders  All placeholders will be casted to string.
     * @return \Plasma\SQL\QueryExpressions\Fragment
     */
    static function fragment(string $operation, ...$placeholders): \Plasma\SQL\QueryExpressions\Fragment {
        $i = 0;
        $len = \count($placeholders);
        
        while($len > $i && ($pos = \strpos($operation, '?')) !== false) {
            $operation = \substr($operation, 0, $pos).((string) $placeholders[($i++)]).\substr($operation, ($pos + 1));
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
     * Sets the target table to the given table. Alias for `from`.
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     *
     */
    function into(string $table, ?string $as = null, array $options = array()): self {
        return $this->from($table, $as, $options);
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
     * Sets the SELECT row-level locking.
     * @param int  $lock  See the class constants.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function setSelectRowLocking(int $lock): self {
        switch($lock) {
            case static::ROW_LOCKING_FOR_UPDATE:
            case static::ROW_LOCKING_FOR_NO_KEY_UPDATE:
            case static::ROW_LOCKING_FOR_SHARE:
            case static::ROW_LOCKING_FOR_KEY_SHARE:
                // Do nothing
            break;
            default:
                throw new \InvalidArgumentException('Unknown select row-level locking mode');
            break;
        }
        
        $this->selectRowLocking = $lock;
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
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
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
     * Inserts a row using a subquery.
     * @param \Plasma\SQLQueryBuilderInterface  $subquery
     * @return $this
     */
    function insertWithSubquery(\Plasma\SQLQueryBuilderInterface $subquery): self {
        $this->type = static::QUERY_TYPE_INSERT;
        
        $this->selects = array();
        $this->parameters = array(
            (new \Plasma\SQL\QueryExpressions\Subquery($subquery, null))
        );
        
        return $this;
    }
    
    /**
     * Updates the rows passing the selection.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
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
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     */
    function join(string $table, ?string $as = null, array $options = array()): self {
        $this->buildJoin('', $table, $as, $options);
        return $this;
    }
    
    /**
     * Adds a INNER JOIN query with the table and optional alias.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     */
    function innerJoin(string $table, ?string $as = null, array $options = array()): self {
        $this->buildJoin('INNER', $table, $as, $options);
        return $this;
    }
    
    /**
     * Adds a OUTER JOIN query with the table and optional alias.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     */
    function outerJoin(string $table, ?string $as = null, array $options = array()): self {
        $this->buildJoin('OUTER', $table, $as, $options);
        return $this;
    }
    
    /**
     * Adds a JOIN query with the table and optional alias.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     */
    function leftJoin(string $table, ?string $as = null, array $options = array()): self {
        $this->buildJoin('LEFT', $table, $as, $options);
        return $this;
    }
    
    /**
     * Adds a RIGHT JOIN query with the table and optional alias.
     *
     * Options:
     * ```
     * array(
     *     'allowEscape' => bool, (whether escaping the column name is allowed, defaults to true)
     * )
     * ```
     *
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     */
    function rightJoin(string $table, ?string $as = null, array $options = array()): self {
        $this->buildJoin('RIGHT', $table, $as, $options);
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
     */
    function whereFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $fragment, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clausel with a logical OR constraint to this fragmented WHERE clausel.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the WHERE clausel (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     */
    function orWhereFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $fragment, $builder);
        
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
     */
    function havingFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $fragment, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clausel with a logical OR constraint to this fragmented HAVING clausel.
     * Since the HAVING clausel is syntax-wise the same as the WHERE clausel, the WhereBuilder gets used for HAVING, too.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somehaving to inject the HAVING clausel (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     */
    function orHavingFragment(\Plasma\SQL\QueryExpressions\Fragment $fragment, \Plasma\SQL\WhereBuilder $builder): self {
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = new \Plasma\SQL\QueryExpressions\FragmentedWhere($constraint, $fragment, $builder);
        
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
            $column = new \Plasma\SQL\QueryExpressions\Column($column, null, true);
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
            $column = new \Plasma\SQL\QueryExpressions\Column($column, null, true);
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
        $this->selects[] = new \Plasma\SQL\QueryExpressions\Subquery($subquery, $alias);
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
        if($this->table === null) {
            throw new \Plasma\Exception('No table was set - use QueryBuilder::from()');
        }
        
        switch($this->type) {
            case static::QUERY_TYPE_SELECT:
                return $this->buildQuerySelect();
            break;
            case static::QUERY_TYPE_INSERT:
                return $this->buildQueryInsert();
            break;
            case static::QUERY_TYPE_UPDATE:
                return $this->buildQueryUpdate();
            break;
            case static::QUERY_TYPE_DELETE:
                return $this->buildQueryDelete();
            break;
            default:
                throw new \Plasma\Exception('Unknown query type - expecting SELECT, INSERT, UPDATE or DELETE');
            break;
        }
    }
    
    /**
     * Returns the associated parameters for the query.
     * @return array
     * @throws \Plasma\Exception
     */
    function getParameters(): array {
        if($this->table === null) {
            throw new \Plasma\Exception('No table was set - use QueryBuilder::from()');
        }
        
        switch($this->type) {
            case static::QUERY_TYPE_SELECT:
                return $this->buildParametersSelect();
            break;
            case static::QUERY_TYPE_INSERT:
                return $this->buildParametersInsert();
            break;
            case static::QUERY_TYPE_UPDATE:
                return $this->buildParametersUpdate();
            break;
            case static::QUERY_TYPE_DELETE:
                return $this->buildParametersDelete();
            break;
            default:
                throw new \Plasma\Exception('Unknown query type - expecting SELECT, INSERT, UPDATE or DELETE');
            break;
        }
    }
    
    /**
     * Builds the join.
     * @param string       $type
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return void
     */
    protected function buildJoin(string $type, string $table, ?string $as, array $options): void {
        $table = new \Plasma\SQL\QueryExpressions\Table($table, $as, ($options['allowEscape'] ?? true));
        $join = new \Plasma\SQL\QueryExpressions\Join($type, $table);
        
        $this->joins[] = $join;
        \end($this->joins);
    }
    
    /**
     * Builds the SELECT query.
     * @return string
     * @throws \Plasma\Exception
     */
    protected function buildQuerySelect(): string {
        /** @var \Plasma\SQL\GrammarInterface  $this->grammar */
        
        $sql = 'SELECT';
        
        if($this->distinct) {
            $sql .= ' DISTINCT';
        }
        
        foreach($this->selects as $select) {
            $sql .= ' '.$select->getSQL($this->grammar).',';
        }
        
        $sql = \substr($sql, 0, -1);
        
        $sql .= ' FROM '.$this->table->getSQL($this->grammar);
        
        foreach($this->joins as $join) {
            $sql .= ' '.$join->getSQL($this->grammar);
        }
        
        if(!empty($this->wheres)) {
            $sql .= ' WHERE';
            
            foreach($this->wheres as $where) {
                $sql .= ' '.$where->getSQL($this->grammar);
            }
        }
        
        if(!empty($this->groupBys)) {
            $sql .= ' GROUP BY';
            
            foreach($this->groupBys as $groupBy) {
                $sql .= ' '.$groupBy->getSQL($this->grammar).',';
            }
            
            $sql = \substr($sql, 0, -1);
        }
        
        if(!empty($this->havings)) {
            $sql .= ' HAVING';
            
            foreach($this->havings as $having) {
                $sql .= ' '.$having->getSQL($this->grammar);
            }
        }
        
        // TODO: Window would be here
        
        if(!empty($this->unions)) {
            $sql = '('.$sql.')';
            
            foreach($this->unions as $union) {
                $sql .= ' '.($union instanceof \Plasma\SQL\QueryExpressions\UnionAll ? 'UNION ALL' : 'UNION');
                $sql .= ' ('.$union->getSQL($this->grammar).')';
            }
        }
        
        if(!empty($this->orderBys)) {
            $sql .= ' ORDER BY';
            
            foreach($this->orderBys as $orderBy) {
                $sql .= ' '.$orderBy->getSQL($this->grammar).',';
            }
            
            $sql = \substr($sql, 0, -1);
        }
        
        if($this->limit !== null) {
            $sql .= ' LIMIT '.$this->limit;
        }
        
        if($this->offset !== null) {
            $sql .= ' OFFSET '.$this->offset;
        }
        
        if($this->selectRowLocking > 0) {
            if($this->grammar === null || !$this->grammar->supportsRowLocking()) {
                throw new \Plasma\Exception('Grammar does not support row-level locking');
            }
            
            $sql .= ' '.$this->grammar->getSQLForRowLocking($this->selectRowLocking);
        }
        
        return $sql;
    }
    
    /**
     * Builds the INSERT query.
     * @return string
     * @throws \Plasma\Exception
     */
    protected function buildQueryInsert(): string {
        /** @var \Plasma\SQL\GrammarInterface  $this->grammar */
        
        if($this->onConflict !== null) {
            if($this->grammar === null) {
                throw new \Plasma\Exception('ON CONFLICT was specified, but no grammar was set');
            }
            
            $conflict = $this->grammar->onConflictToSQL($this, $this->onConflict, $this->selects, $this->parameters);
        } else {
            $conflict = null;
        }
        
        /** @var \Plasma\SQL\ConflictResolution|null  $conflict */
        
        if($conflict === null) {
            $sql = 'INSERT INTO';
        } else {
            /** @var \Plasma\SQL\ConflictResolution  $conflict */
            $sql = $conflict->getKeyword();
        }
        
        $sql .= ' '.$this->table->getSQL($this->grammar);
        
        if(!empty($this->selects)) {
            $sql .= ' (';
            
            foreach($this->selects as $select) {
                if($select instanceof \Plasma\SQL\QueryExpressions\Subquery) {
                    throw new \Plasma\Exception('Invalid INSERT fields, found subquery inserted - remove calls to QueryBuilder::subquery()');
                }
                
                $sql .= $select->getSQL($this->grammar).', ';
            }
            
            $sql = \substr($sql, 0, -2).')';
        }
        
        $sql .= ' VALUES (';
        
        foreach($this->parameters as $parameter) {
            $sql .= (
                $parameter instanceof \Plasma\SQL\QueryExpressions\Fragment ||
                $parameter instanceof \Plasma\SQL\QueryExpressions\Subquery ?
                $parameter->getSQL($this->grammar) :
                '?'
            ).', ';
        }
        
        $sql = \substr($sql, 0, -2).')';
        
        if($conflict !== null && !empty($conflict->getAppendum())) {
            /** @var \Plasma\SQL\ConflictResolution  $conflict */
            $sql .= ' '.$conflict->getAppendum();
        }
        
        if($this->returning) {
            if($this->grammar === null || !$this->grammar->supportsReturning()) {
                throw new \Plasma\Exception('Grammar does not support RETURNING');
            }
            
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }
    
    /**
     * Builds the UPDATE query.
     * @return string
     * @throws \Plasma\Exception
     */
    protected function buildQueryUpdate(): string {
        /** @var \Plasma\SQL\GrammarInterface  $this->grammar */
        
        $sql = 'UPDATE '.$this->table->getSQL($this->grammar).' SET';
        
        foreach($this->selects as $key => $column) {
            $parameter = $this->parameters[$key];
            $parameter = ($parameter instanceof \Plasma\SQL\QueryExpressions\Fragment ? $parameter->getSQL() : '?');
            
            $sql .= ' '.$column->getSQL($this->grammar).' = '.$parameter.',';
        }
        
        $sql = \substr($sql, 0, -1);
        
        if(!empty($this->wheres)) {
            $sql .= ' WHERE';
            
            foreach($this->wheres as $where) {
                $sql .= ' '.$where->getSQL($this->grammar);
            }
        }
        
        if($this->returning) {
            if($this->grammar === null || !$this->grammar->supportsReturning()) {
                throw new \Plasma\Exception('Grammar does not support RETURNING');
            }
            
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }
    
    /**
     * Builds the DELETE query.
     * @return string
     * @throws \Plasma\Exception
     */
    protected function buildQueryDelete(): string {
        /** @var \Plasma\SQL\GrammarInterface  $this->grammar */
        
        $sql = 'DELETE FROM '.$this->table->getSQL($this->grammar);
        
        if(!empty($this->wheres)) {
            $sql .= ' WHERE';
            
            foreach($this->wheres as $where) {
                $sql .= ' '.$where->getSQL($this->grammar);
            }
        }
        
        if($this->returning) {
            if($this->grammar === null || !$this->grammar->supportsReturning()) {
                throw new \Plasma\Exception('Grammar does not support RETURNING');
            }
            
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }

    /**
     * Builds the SELECT query parameters.
     * @return array
     * @throws \Plasma\Exception
     */
    protected function buildParametersSelect(): array {
        $parameters = array();
        
        if(!empty($this->selects)) {
            foreach($this->selects as $select) {
                if($select instanceof \Plasma\SQL\QueryExpressions\Subquery) {
                    foreach($select->getParameters() as $param) {
                        $parameters[] = $param;
                    }
                }
            }
        }
        
        if(!empty($this->wheres)) {
            $parameters = \array_merge($parameters, ...\array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) {
                return \array_map(function ($parameter) {
                    return $this->unpackParameter($parameter);
                }, $where->getParameters());
            }, $this->wheres));
        }
        
        if(!empty($this->havings)) {
            $parameters = \array_merge($parameters, ...\array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $having) {
                return \array_map(function ($parameter) {
                    return $this->unpackParameter($parameter);
                }, $having->getParameters());
            }, $this->havings));
        }
        
        // TODO: Window would be here
        
        if(!empty($this->unions)) {
            $parameters = \array_merge($parameters, ...\array_map(function (\Plasma\SQL\QueryExpressions\UnionInterface $union) {
                return $union->getParameters();
            }, $this->unions));
        }
        
        return $parameters;
    }

    /**
     * Builds the INSERT query parameters.
     * @return array
     * @throws \Plasma\Exception
     */
    protected function buildParametersInsert(): array {
        $params = \array_filter($this->parameters, function ($parameter) {
            return !($parameter instanceof \Plasma\SQL\QueryExpressions\Fragment);
        });
        
        return \array_merge(...\array_map(function ($parameter) {
            if($parameter instanceof \Plasma\SQL\QueryExpressions\Subquery) {
                return $parameter->getParameters();
            }
            
            return array($this->unpackParameter($parameter));
        }, $params));
    }

    /**
     * Builds the UPDATE query parameters.
     * @return array
     * @throws \Plasma\Exception
     */
    protected function buildParametersUpdate(): array {
        $parameters = array();
        
        foreach($this->parameters as $pos => $parameter) {
            if(!($parameter instanceof \Plasma\SQL\QueryExpressions\Fragment)) {
                $parameters[] = $this->unpackParameter($parameter);
            }
        }
        
        if(!empty($this->wheres)) {
            $parameters = \array_merge($parameters, ...\array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) {
                return \array_map(function ($parameter) {
                    return $this->unpackParameter($parameter);
                }, $where->getParameters());
            }, $this->wheres));
        }
        
        return $parameters;
    }

    /**
     * Builds the DELETE query parameters.
     * @return array
     * @throws \Plasma\Exception
     */
    protected function buildParametersDelete(): array {
        if(!empty($this->wheres)) {
            return \array_merge(...\array_map(function (\Plasma\SQL\QueryExpressions\WhereInterface $where) {
                $params = \array_filter($where->getParameters(), function ($parameter) {
                    return !($parameter instanceof \Plasma\SQL\QueryExpressions\Fragment);
                });
                
                return \array_map(function ($parameter) {
                    return $this->unpackParameter($parameter);
                }, $params);
            }, $this->wheres));
        }
        
        return array();
    }
    
    /**
     * Unpacks a parameter.
     * @param QueryExpressions\Fragment|QueryExpressions\Parameter  $parameter
     * @return mixed
     * @throws \Plasma\Exception
     */
    protected function unpackParameter($parameter) {
        if($parameter instanceof \Plasma\SQL\QueryExpressions\Fragment) { // Probably never reachable
            return $parameter->getSQL(); // @codeCoverageIgnore
        } elseif($parameter->hasValue()) {
            return $parameter->getValue();
        }
        
        throw new \Plasma\Exception('Parameter #'.\spl_object_hash($parameter).' has no value');
    }
}
