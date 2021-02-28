<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL;

use Plasma\Exception;
use Plasma\QueryBuilderInterface;
use Plasma\SQL\QueryExpressions\BetweenParameter;
use Plasma\SQL\QueryExpressions\Column;
use Plasma\SQL\QueryExpressions\Fragment;
use Plasma\SQL\QueryExpressions\FragmentedWhere;
use Plasma\SQL\QueryExpressions\GroupBy;
use Plasma\SQL\QueryExpressions\Join;
use Plasma\SQL\QueryExpressions\On;
use Plasma\SQL\QueryExpressions\OrderBy;
use Plasma\SQL\QueryExpressions\Parameter;
use Plasma\SQL\QueryExpressions\Subquery;
use Plasma\SQL\QueryExpressions\Table;
use Plasma\SQL\QueryExpressions\Union;
use Plasma\SQL\QueryExpressions\UnionAll;
use Plasma\SQL\QueryExpressions\UnionInterface;
use Plasma\SQL\QueryExpressions\WhereExpression;
use Plasma\SQL\QueryExpressions\WhereInterface;
use Plasma\SQLQueryBuilderInterface;
use Plasma\Utility;

/**
 * Provides an implementation for a SQL querybuilder.
 *
 * This class has interoperability with all other query builders
 * implementing the SQL query builder interface.
 */
class QueryBuilder implements SQLQueryBuilderInterface {
    /**
     * Used internally to describe a `SELECT` query.
     * @var int
     * @internal
     */
    protected const QUERY_TYPE_SELECT = 0x1;
    
    /**
     * Used internally to describe an `INSERT INTO` query.
     * @var int
     * @internal
     */
    protected const QUERY_TYPE_INSERT = 0x2;
    
    /**
     * Used internally to describe an `UPDATE` query.
     * @var int
     * @internal
     */
    protected const QUERY_TYPE_UPDATE = 0x3;
    
    /**
     * Used internally to describe a `DELETE` query.
     * @var int
     * @internal
     */
    protected const QUERY_TYPE_DELETE = 0x4;
    
    /**
     * Used internally to detect `?` placeholders fragment.
     * @var string
     */
    protected const PLACEHOLDERS_REPLACE_REGEX = '/(["\\\']).*?(?<!\\\\)\1(*SKIP)(*F)|\\?/u';
    
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
     * @var Table
     */
    protected $table;
    
    /**
     * @var UnionInterface[]
     */
    protected $unions = array();
    
    /**
     * @var Join[]
     */
    protected $joins = array();
    
    /**
     * @var Column[]|Subquery[]
     */
    protected $selects = array();
    
    /**
     * @var Fragment[]|Parameter[]|Subquery[]
     */
    protected $parameters = array();
    
    /**
     * @var OnConflict|null
     */
    protected $onConflict;
    
    /**
     * @var WhereInterface[]
     */
    protected $havings = array();
    
    /**
     * @var WhereInterface[]
     */
    protected $wheres = array();
    
    /**
     * @var OrderBy[]
     */
    protected $orderBys = array();
    
    /**
     * @var GroupBy[]
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
     * @var string|null
     */
    protected $prefix;
    
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
     * @var GrammarInterface|null
     */
    protected $grammar;
    
    /**
     * Creates a new instance of the querybuilder.
     * @return self|QueryBuilderInterface
     */
    static function create(): QueryBuilderInterface {
        return (new static());
    }
    
    /**
     * Creates a new instance of the querybuilder with a grammar.
     * @param GrammarInterface  $grammar
     * @return self
     */
    static function createWithGrammar(GrammarInterface $grammar): self {
        $qb = new static();
        $qb->grammar = $grammar;
        
        return $qb;
    }
    
    /**
     * Creates a new BetweenParameter for the two between values.
     * @param mixed|QueryExpressions\Fragment  $first
     * @param mixed|QueryExpressions\Fragment  $second
     * @return BetweenParameter
     */
    static function between($first, $second): BetweenParameter {
        if(!($first instanceof Fragment) && !($first instanceof Parameter)) {
            $first = new Parameter($first, !\is_null($first));
        }
        
        if(!($second instanceof Fragment) && !($second instanceof Parameter)) {
            $second = new Parameter($second, !\is_null($second));
        }
        
        return (new BetweenParameter($first, $second));
    }
    
    /**
     * Creates a new Column.
     * @param string       $name
     * @param string|null  $as
     * @param bool         $allowEscape
     * @return Column
     */
    static function column(string $name, ?string $as = null, bool $allowEscape = true): Column {
        return (new Column($name, $as, $allowEscape));
    }
    
    /**
     * Creates a new Fragment. All placeholders `?` in the operation string will be replaced by the following arguments.
     * Placeholders can be escaped with a backslash.
     * However we will not do any replacement if we do not have sufficient placeholders (counts also for removing the escape).
     * @param string  $operation
     * @param mixed   ...$placeholders  All placeholders will be casted to string.
     * @return Fragment
     */
    static function fragment(string $operation, ...$placeholders): Fragment {
        $i = 0;
        $ppos = 0;
        $len = \count($placeholders);
        
        while($len > $i && ($pos = \strpos($operation, '?', $ppos)) !== false) {
            if(($operation[($pos - 1)] ?? '') === '\\') {
                $operation = \substr($operation, 0, ($pos - 1)).\substr($operation, $pos);
                $ppos = $pos;
                continue;
            }
            
            $operation = \substr($operation, 0, $pos).((string) $placeholders[($i++)]).\substr($operation, ($pos + 1));
            $ppos = 0;
        }
        
        return (new Fragment($operation));
    }
    
    /**
     * Clones the querybuilder and sets the grammar.
     * @param GrammarInterface  $grammar
     * @return self
     */
    function withGrammar(GrammarInterface $grammar): self {
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
     */
    function from(string $table, ?string $as = null, array $options = array()): self {
        $this->table = new Table($table, $as, ($options['allowEscape'] ?? true));
        return $this;
    }
    
    /**
     * Sets the target table to the given table. Alias for `from`.
     * @param string       $table
     * @param string|null  $as
     * @param array        $options
     * @return $this
     */
    function into(string $table, ?string $as = null, array $options = array()): self {
        return $this->from($table, $as, $options);
    }
    
    /**
     * Adds a DISTINCT flag to this query.
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
            $allowEscape = ($baseEscape && !($column instanceof Fragment));
            
            $this->selects[] = new Column(
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
            if(!($options['onConflict'] instanceof OnConflict)) {
                throw new \InvalidArgumentException('Invalid ON CONFLICT resolution - not an OnConflict instance');
            }
            
            $this->onConflict = $options['onConflict'];
        }
        
        $this->type = static::QUERY_TYPE_INSERT;
        
        $this->selects = array();
        $this->parameters = array();
        
        foreach($row as $column => $value) {
            $this->selects[] = new Column(
                $column,
                null,
                ($options['allowEscape'] ?? true)
            );
            
            $usable = (
                $value instanceof Fragment ||
                $value instanceof Parameter
            );
            
            $this->parameters[] = (
                $usable ?
                $value :
                (new Parameter($value, true))
            );
        }
        
        return $this;
    }
    
    /**
     * Inserts a row using a subquery.
     * @param SQLQueryBuilderInterface  $subquery
     * @return $this
     */
    function insertWithSubquery(SQLQueryBuilderInterface $subquery): self {
        $this->type = static::QUERY_TYPE_INSERT;
        
        $this->selects = array();
        $this->parameters = array(
            (new Subquery($subquery, null))
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
            $this->selects[] = new Column(
                $column,
                null,
                ($options['allowEscape'] ?? true)
            );
            
            $usable = (
                $value instanceof Fragment ||
                $value instanceof Parameter
            );
            
            $this->parameters[] = (
                $usable ?
                $value :
                (new Parameter($value, true))
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
     * @throws Exception
     */
    function on(string $leftside, string $rightside): self {
        $on = new On($leftside, $rightside);
        
        /** @var Join|false  $join */
        $join = \current($this->joins);
        
        if(!$join) {
            throw new Exception('Invalid ON position - there is no JOIN expression');
        }
        
        $join->addOn($on);
        return $this;
    }
    
    /**
     * Put the previous WHERE clause with a logical AND constraint to this WHERE clause.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function where($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
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
    function orWhere($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Extended where building. The callback gets a `WhereExpression` instance, where the callback is supposed to build the WHERE clause.
     * The WHERE clause gets wrapped into parenthesis and with an AND constraint coupled to the previous one.
     * @param callable  $where  Callback signature: `function (\Plasma\SQL\WhereExpression $qb): void`.
     * @return $this
     * @throws Exception
     */
    function whereExt(callable $where): self {
        $builder = new WhereBuilder();
        $where($builder);
        
        if($builder->isEmpty()) {
            throw new Exception('Given callable did nothing with the where builder');
        }
        
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = new WhereExpression($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Extended where building. The callback gets a `WhereExpression` instance, where the callback is supposed to build the WHERE clause.
     * The WHERE clause gets wrapped into parenthesis and with an OR constraint coupled to the previous one.
     * @param callable  $where  Callback signature: `function (\Plasma\SQL\WhereExpression $qb): void`.
     * @return $this
     * @throws Exception
     */
    function orWhereExt(callable $where): self {
        $builder = new WhereBuilder();
        $where($builder);
        
        if($builder->isEmpty()) {
            throw new Exception('Given callable did nothing with the where builder');
        }
        
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = new WhereExpression($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clause with a logical AND constraint to this fragmented WHERE clause.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the WHERE clause (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     */
    function whereFragment(Fragment $fragment, WhereBuilder $builder): self {
        $constraint = (empty($this->wheres) ? null : 'AND');
        $this->wheres[] = new FragmentedWhere($constraint, $fragment, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous WHERE clause with a logical OR constraint to this fragmented WHERE clause.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the WHERE clause (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     */
    function orWhereFragment(Fragment $fragment, WhereBuilder $builder): self {
        $constraint = (empty($this->wheres) ? null : 'OR');
        $this->wheres[] = new FragmentedWhere($constraint, $fragment, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clause with a logical AND constraint to this HAVING clause.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function having($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clause with a logical OR constraint to this HAVING clause.
     * @param string|QueryExpressions\Column|QueryExpressions\Fragment  $column
     * @param string|null                                               $operator
     * @param mixed|QueryExpressions\Parameter|null                     $value     If not a `Parameter` instance, the value will be wrapped into one.
     * @return $this
     * @throws \InvalidArgumentException
     */
    function orHaving($column, ?string $operator = null, $value = null): self {
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = WhereBuilder::createWhere($constraint, $column, $operator, $value);
        
        return $this;
    }
    
    /**
     * Extended having building. The callback gets a `WhereExpression` instance, where the callback is supposed to build the HAVING clause.
     * The HAVING clause gets wrapped into parenthesis and with an AND constraint coupled to the previous one.
     * Since the HAVING clause is syntax-wise the same as the WHERE clause, the WhereExpression gets used for HAVING, too.
     * @param callable  $having  Callback signature: `function (\Plasma\SQL\WhereExpression $qb): void`.
     * @return $this
     * @throws Exception
     */
    function havingExt(callable $having): self {
        $builder = new WhereBuilder();
        $having($builder);
        
        if($builder->isEmpty()) {
            throw new Exception('Given callable did nothing with the having builder');
        }
        
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = new WhereExpression($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Extended having building. The callback gets a `WhereExpression` instance, where the callback is supposed to build the HAVING clause.
     * The HAVING clause gets wrapped into parenthesis and with an OR constraint coupled to the previous one.
     * Since the HAVING clause is syntax-wise the same as the WHERE clause, the WhereExpression gets used for HAVING, too.
     * @param callable  $having  Callback signature: `function (\Plasma\SQL\WhereExpression $qb): void`.
     * @return $this
     * @throws Exception
     */
    function orHavingExt(callable $having): self {
        $builder = new WhereBuilder();
        $having($builder);
        
        if($builder->isEmpty()) {
            throw new Exception('Given callable did nothing with the having builder');
        }
        
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = new WhereExpression($constraint, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clause with a logical AND constraint to this fragmented HAVING clause.
     * Since the HAVING clause is syntax-wise the same as the WHERE clause, the WhereExpression gets used for HAVING, too.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the HAVING clause (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     */
    function havingFragment(Fragment $fragment, WhereBuilder $builder): self {
        $constraint = (empty($this->havings) ? null : 'AND');
        $this->havings[] = new FragmentedWhere($constraint, $fragment, $builder);
        
        return $this;
    }
    
    /**
     * Put the previous HAVING clause with a logical OR constraint to this fragmented HAVING clause.
     * Since the HAVING clause is syntax-wise the same as the WHERE clause, the WhereExpression gets used for HAVING, too.
     * @param QueryExpressions\Fragment  $fragment  The fragment is expected to have `$$` somewhere to inject the HAVING clause (from the builder) into its place.
     * @param WhereBuilder               $builder
     * @return $this
     */
    function orHavingFragment(Fragment $fragment, WhereBuilder $builder): self {
        $constraint = (empty($this->havings) ? null : 'OR');
        $this->havings[] = new FragmentedWhere($constraint, $fragment, $builder);
        
        return $this;
    }
    
    /**
     * Set the limit for the `SELECT` query.
     * @param int|null  $limit
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
        if(!($column instanceof Column)) {
            $column = new Column($column, null, true);
        }
        
        $this->orderBys[] = new OrderBy($column, $descending);
        return $this;
    }
    
    /**
     * Add an `GROUP BY` to the query. This will aggregate.
     * @param QueryExpressions\Column|string  $column
     * @return $this
     */
    function groupBy($column): self {
        if(!($column instanceof Column)) {
            $column = new Column($column, null, true);
        }
        
        $this->groupBys[] = new GroupBy($column);
        return $this;
    }
    
    /**
     * Adds a subquery to the `SELECT` query.
     * @param SQLQueryBuilderInterface  $subquery
     * @param string|null                       $alias
     * @return $this
     */
    function subquery(SQLQueryBuilderInterface $subquery, ?string $alias = null): self {
        $this->selects[] = new Subquery($subquery, $alias);
        return $this;
    }
    
    /**
     * Adds an `UNION` to the `SELECT` query.
     * @param SQLQueryBuilderInterface  $query
     * @return $this
     */
    function union(SQLQueryBuilderInterface $query): self {
        $this->unions[] = new Union($query);
        return $this;
    }
    
    /**
     * Adds an `UNION ALL` to the `SELECT` query.
     * @param SQLQueryBuilderInterface  $query
     * @return $this
     */
    function unionAll(SQLQueryBuilderInterface $query): self {
        $this->unions[] = new UnionAll($query);
        return $this;
    }
    
    /**
     * Sets the prefix.
     * When using MySQL, the prefix points to a database. In case
     * of PostgreSQL, the prefix points to a schema.
     * @param string  $prefix
     * @return $this
     */
    function setPrefix(string $prefix): self {
        $this->prefix = $prefix;
        return $this;
    }
    
    /**
     * Returns the query.
     * All `?` placeholders are replaced by the correct syntax, depending on the grammar.
     * @return string
     * @throws Exception
     */
    function getQuery() {
        if($this->table === null) {
            throw new Exception('No table was set - use QueryBuilder::from()');
        }
        
        switch($this->type) {
            case static::QUERY_TYPE_SELECT:
                return $this->buildQuerySelect();
            case static::QUERY_TYPE_INSERT:
                return $this->buildQueryInsert();
            case static::QUERY_TYPE_UPDATE:
                return $this->buildQueryUpdate();
            case static::QUERY_TYPE_DELETE:
                return $this->buildQueryDelete();
            default:
                throw new Exception('Unknown query type - expecting SELECT, INSERT, UPDATE or DELETE');
        }
    }
    
    /**
     * Returns the associated parameters for the query.
     * @return array
     * @throws Exception
     */
    function getParameters(): array {
        if($this->table === null) {
            throw new Exception('No table was set - use QueryBuilder::from()');
        }
        
        switch($this->type) {
            case static::QUERY_TYPE_SELECT:
                return $this->buildParametersSelect();
            case static::QUERY_TYPE_INSERT:
                return $this->buildParametersInsert();
            case static::QUERY_TYPE_UPDATE:
                return $this->buildParametersUpdate();
            case static::QUERY_TYPE_DELETE:
                return $this->buildParametersDelete();
            default:
                throw new Exception('Unknown query type - expecting SELECT, INSERT, UPDATE or DELETE');
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
        $tableC = new Table($table, $as, ($options['allowEscape'] ?? true));
        $join = new Join($type, $tableC);
        
        $this->joins[] = $join;
        \end($this->joins);
    }
    
    /**
     * Builds the SELECT query.
     * @return string
     * @throws Exception
     */
    protected function buildQuerySelect(): string {
        /** @var GrammarInterface $this->grammar */
        
        $sql = 'SELECT';
        $placeholders = ($this->grammar !== null ? $this->grammar->getPlaceholderCallable() : null);
        
        if($this->distinct) {
            $sql .= ' DISTINCT';
        }
        
        foreach($this->selects as $select) {
            $sql .= ' '.$select->getSQL($this->grammar).',';
        }
        
        $sql = \substr($sql, 0, -1);
        
        $sql .= ' FROM '.($this->prefix ? $this->prefix.'.' : '').$this->table->getSQL($this->grammar);
        
        foreach($this->joins as $join) {
            $sql .= ' '.$join->getSQL($this->grammar);
        }
        
        if(!empty($this->wheres)) {
            $sql .= ' WHERE';
            $wheres = '';
            
            foreach($this->wheres as $where) {
                $wheres .= ' '.$where->getSQL($this->grammar);
            }
            
            if($placeholders !== null) {
                $wheres = Utility::parseParameters($wheres, $placeholders, static::PLACEHOLDERS_REPLACE_REGEX)['query'];
            }
            
            $sql .= $wheres;
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
            $havings = '';
            
            foreach($this->havings as $having) {
                $havings .= ' '.$having->getSQL($this->grammar);
            }
            
            if($placeholders !== null) {
                $havings = Utility::parseParameters($havings, $placeholders, static::PLACEHOLDERS_REPLACE_REGEX)['query'];
            }
            
            $sql .= $havings;
        }
        
        // TODO: Window would be here
        
        if(!empty($this->unions)) {
            $sql = '('.$sql.')';
            
            foreach($this->unions as $union) {
                $sql .= ' '.($union instanceof UnionAll ? 'UNION ALL' : 'UNION');
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
                throw new Exception('Grammar does not support row-level locking');
            }
            
            $sql .= ' '.$this->grammar->getSQLForRowLocking($this->selectRowLocking);
        }
        
        return $sql;
    }
    
    /**
     * Builds the INSERT query.
     * @return string
     * @throws Exception
     */
    protected function buildQueryInsert(): string {
        /** @var GrammarInterface $this->grammar */
        
        if($this->onConflict !== null) {
            if($this->grammar === null) {
                throw new Exception('ON CONFLICT was specified, but no grammar was set');
            }
            
            $conflict = $this->grammar->onConflictToSQL($this, $this->onConflict, $this->selects, $this->parameters);
        } else {
            $conflict = null;
        }
        
        /** @var ConflictResolution|null  $conflict */
        
        if($conflict === null) {
            $sql = 'INSERT INTO';
        } else {
            $sql = $conflict->getKeyword();
        }
        
        $sql .= ' '.($this->prefix ? $this->prefix.'.' : '').$this->table->getSQL($this->grammar);
        $placeholders = ($this->grammar !== null ? $this->grammar->getPlaceholderCallable() : null);
        
        if(!empty($this->selects)) {
            $sql .= ' (';
            
            foreach($this->selects as $select) {
                if($select instanceof Subquery) {
                    throw new Exception('Invalid INSERT fields, found subquery inserted - remove calls to QueryBuilder::subquery()');
                }
                
                $sql .= $select->getSQL($this->grammar).', ';
            }
            
            $sql = \substr($sql, 0, -2).')';
        }
        
        $sql .= ' VALUES (';
        
        foreach($this->parameters as $parameter) {
            $sql .= (
                $parameter instanceof Fragment ||
                $parameter instanceof Subquery ?
                $parameter->getSQL($this->grammar) :
                ($placeholders !== null ? $placeholders() : '?')
            ).', ';
        }
        
        $sql = \substr($sql, 0, -2).')';
        
        if($conflict !== null && !empty($conflict->getAppendum())) {
            $sql .= ' '.$conflict->getAppendum();
        }
        
        if($this->returning) {
            if($this->grammar === null || !$this->grammar->supportsReturning()) {
                throw new Exception('Grammar does not support RETURNING');
            }
            
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }
    
    /**
     * Builds the UPDATE query.
     * @return string
     * @throws Exception
     */
    protected function buildQueryUpdate(): string {
        /** @var GrammarInterface $this->grammar */
        
        $sql = 'UPDATE '.($this->prefix ? $this->prefix.'.' : '').$this->table->getSQL($this->grammar).' SET';
        $placeholders = ($this->grammar !== null ? $this->grammar->getPlaceholderCallable() : null);
        
        foreach($this->selects as $key => $column) {
            $parameter = $this->parameters[$key];
            $parameter = (
                $parameter instanceof Fragment ?
                $parameter->getSQL() :
                ($placeholders !== null ? $placeholders() : '?')
            );
            
            $sql .= ' '.$column->getSQL($this->grammar).' = '.$parameter.',';
        }
        
        $sql = \substr($sql, 0, -1);
        
        if(!empty($this->wheres)) {
            $sql .= ' WHERE';
            $wheres = '';
            
            foreach($this->wheres as $where) {
                $wheres .= ' '.$where->getSQL($this->grammar);
            }
            
            if($placeholders !== null) {
                $wheres = Utility::parseParameters($wheres, $placeholders, static::PLACEHOLDERS_REPLACE_REGEX)['query'];
            }
            
            $sql .= $wheres;
        }
        
        if($this->returning) {
            if($this->grammar === null || !$this->grammar->supportsReturning()) {
                throw new Exception('Grammar does not support RETURNING');
            }
            
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }
    
    /**
     * Builds the DELETE query.
     * @return string
     * @throws Exception
     */
    protected function buildQueryDelete(): string {
        /** @var GrammarInterface $this->grammar */
        
        $sql = 'DELETE FROM '.($this->prefix ? $this->prefix.'.' : '').$this->table->getSQL($this->grammar);
        $placeholders = ($this->grammar !== null ? $this->grammar->getPlaceholderCallable() : null);
        
        if(!empty($this->wheres)) {
            $sql .= ' WHERE';
            $wheres = '';
            
            foreach($this->wheres as $where) {
                $wheres .= ' '.$where->getSQL($this->grammar);
            }
            
            if($placeholders !== null) {
                $wheres = Utility::parseParameters($wheres, $placeholders, static::PLACEHOLDERS_REPLACE_REGEX)['query'];
            }
            
            $sql .= $wheres;
        }
        
        if($this->returning) {
            if($this->grammar === null || !$this->grammar->supportsReturning()) {
                throw new Exception('Grammar does not support RETURNING');
            }
            
            $sql .= ' RETURNING *';
        }
        
        return $sql;
    }
    
    /**
     * Builds the SELECT query parameters.
     * @return array
     * @throws Exception
     */
    protected function buildParametersSelect(): array {
        $parameters = array();
        
        if(!empty($this->selects)) {
            foreach($this->selects as $select) {
                if($select instanceof Subquery) {
                    foreach($select->getParameters() as $param) {
                        $parameters[] = $param;
                    }
                }
            }
        }
        
        if(!empty($this->wheres)) {
            $parameters = \array_merge($parameters, ...\array_map(function (WhereInterface $where) {
                return \array_map(function ($parameter) {
                    return $this->unpackParameter($parameter);
                }, $where->getParameters());
            }, $this->wheres));
        }
        
        if(!empty($this->havings)) {
            $parameters = \array_merge($parameters, ...\array_map(function (WhereInterface $having) {
                return \array_map(function ($parameter) {
                    return $this->unpackParameter($parameter);
                }, $having->getParameters());
            }, $this->havings));
        }
        
        // TODO: Window would be here
        
        if(!empty($this->unions)) {
            $parameters = \array_merge($parameters, ...\array_map(static function (UnionInterface $union) {
                return $union->getParameters();
            }, $this->unions));
        }
        
        return $parameters;
    }
    
    /**
     * Builds the INSERT query parameters.
     * @return array
     * @throws Exception
     */
    protected function buildParametersInsert(): array {
        $params = \array_filter($this->parameters, static function ($parameter) {
            return !($parameter instanceof Fragment);
        });
        
        return \array_merge(...\array_map(function ($parameter) {
            if($parameter instanceof Subquery) {
                return $parameter->getParameters();
            }
            
            return array($this->unpackParameter($parameter));
        }, $params));
    }
    
    /**
     * Builds the UPDATE query parameters.
     * @return array
     * @throws Exception
     */
    protected function buildParametersUpdate(): array {
        $parameters = array();
        
        foreach($this->parameters as $pos => $parameter) {
            if(!($parameter instanceof Fragment)) {
                $parameters[] = $this->unpackParameter($parameter);
            }
        }
        
        if(!empty($this->wheres)) {
            $parameters = \array_merge($parameters, ...\array_map(function (WhereInterface $where) {
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
     * @throws Exception
     */
    protected function buildParametersDelete(): array {
        if(!empty($this->wheres)) {
            return \array_merge(...\array_map(function (WhereInterface $where) {
                $params = \array_filter($where->getParameters(), static function ($parameter) {
                    return !($parameter instanceof Fragment);
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
     * @throws Exception
     */
    protected function unpackParameter($parameter) {
        if($parameter instanceof Fragment) { // Probably never reachable
            return $parameter->getSQL(); // @codeCoverageIgnore
        }
        
        if($parameter->hasValue()) {
            return $parameter->getValue();
        }
        
        throw new Exception('Parameter #'.\spl_object_hash($parameter).' has no value');
    }
}
