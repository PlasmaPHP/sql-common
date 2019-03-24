<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\Blueprints;

/**
 * A blueprint used for creating and altering tables.
 */
class TableBlueprint implements TableBlueprintInterface {
    /**
     * Used internally to describe a query creating a table.
     * @var int
     * @internal
     */
    const QUERY_TYPE_CREATE_TABLE = 0x1;
    
    /**
     * Used internally to describe a query altering a table.
     * @var int
     * @internal
     */
    const QUERY_TYPE_ALTER_TABLE = 0x2;
    
    /**
     * Used internally to describe a query dropping a table.
     * @var int
     * @internal
     */
    const QUERY_TYPE_DROP_TABLE = 0x3;
    
    /**
     * Used internally to describe a query creating a table column.
     * @var int
     * @internal
     */
    const QUERY_TYPE_ADD_COLUMN = 0x10;
    
    /**
     * Used internally to describe a query altering a table column.
     * @var int
     * @internal
     */
    const QUERY_TYPE_ALTER_COLUMN = 0x11;
    
    /**
     * Used internally to describe a query dropping a table column.
     * @var int
     * @internal
     */
    const QUERY_TYPE_DROP_COLUMN = 0x12;
    
    /**
     * @var int|null
     */
    protected $type;
    
    /**
     * @var string|null
     */
    protected $prefix;
    
    /**
     * @var string
     */
    protected $table;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Table|string|null
     */
    protected $tableLike;
    
    /**
     * @var bool
     */
    protected $temporary = false;
    
    /**
     * @var bool
     */
    protected $ifNotExists = false;
    
    /**
     * @var \Plasma\SQL\Blueprints\ColumnBlueprintInterface[]
     */
    protected $columns = array();
    
    /**
     * @var \Plasma\SQL\GrammarInterface|null
     */
    protected $grammar;
    
    /**
     * Creates a new blueprint.
     * @return self
     */
    static function create() {
        return (new static());
    }
    
    /**
     * Creates a new blueprint with grammar.
     * @param \Plasma\SQL\GrammarInterface  $grammar
     * @return self
     */
    static function createWithGrammar(\Plasma\SQL\GrammarInterface $grammar) {
        $tbp = new static();
        $tbp->grammar = $grammar;
        
        return $tbp;
    }
    
    /**
     * Clones the blueprint and sets the grammar.
     * @param \Plasma\SQL\GrammarInterface  $grammar
     * @return self
     */
    function withGrammar(\Plasma\SQL\GrammarInterface $grammar): self {
        $tbp = clone $this;
        $tbp->grammar = $grammar;
        
        return $tbp;
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
     * Sets the table.
     * @param string  $table
     * @return $this
     */
    function setTable(string $table): self {
        $this->table = $table;
        return $this;
    }
    
    /**
     * Sets the table as temporary. Only affects `CREATE TABLE`.
     * @param bool  $temporary
     * @return $this
     */
    function setTemporary(bool $temporary = true): self {
        $this->temporary = $temporary;
        return $this;
    }
    
    /**
     * Creates a new table with the specified columns.
     * @param \Plasma\SQL\Blueprints\CreateTableOptionsInterface|null  $options
     * @return $this
     */
    function createTable(?\Plasma\SQL\Blueprints\CreateTableOptionsInterface $options = null): self {
        $this->type = static::QUERY_TYPE_CREATE_TABLE;
        return $this;
    }
    
    /**
     * Creates a new table based on the definition of another table.
     * @param \Plasma\SQL\QueryExpressions\Table|string                $table
     * @param \Plasma\SQL\Blueprints\CreateTableOptionsInterface|null  $options
     * @return $this
     */
    function createTableLike($table, ?\Plasma\SQL\Blueprints\CreateTableOptionsInterface $options = null): self {
        $this->type = static::QUERY_TYPE_CREATE_TABLE;
        $this->columns = array();
        $this->tableLike = $table;
        
        return $this;
    }
    
    /**
     * Creates the table if the table does not exist yet (only checks name),
     * or drops the table if the table exists.
     * @return $this
     */
    function ifNotExists(): self {
        $this->ifNotExists = true;
        return $this;
    }
    
    /**
     * Alters an existing table with the specified options.
     * @return $this
     */
    function alterTable(): self {
        $this->type = static::QUERY_TYPE_ALTER_TABLE;
        $this->columns = array();
        
        return $this;
    }
    
    /**
     * Drops the table.
     * @return $this
     */
    function dropTable(): self {
        $this->type = static::QUERY_TYPE_DROP_TABLE;
        return $this;
    }
    
    /**
     * Adds a column to an existing table, or adds a new column to a new table definition.
     * @param \Plasma\SQL\Blueprints\ColumnBlueprintInterface  $column
     * @return $this
     */
    function addColumn(\Plasma\SQL\Blueprints\ColumnBlueprintInterface $column): self {
        if($this->type === static::QUERY_TYPE_CREATE_TABLE) {
            $this->columns[] = $column;
        } else {
            $this->type = static::QUERY_TYPE_ADD_COLUMN;
            $this->columns = array($column);
        }
        
        return $this;
    }
    
    /**
     * Alters an existing column of a table with the specified options.
     * @param \Plasma\SQL\Blueprints\ColumnBlueprintInterface  $column
     * @return $this
     */
    function alterColumn(\Plasma\SQL\Blueprints\ColumnBlueprintInterface $column): self {
        $this->type = static::QUERY_TYPE_ALTER_COLUMN;
        $this->columns = array($column);
        
        return $this;
    }
    
    /**
     * Drops an existing column from a table.
     * @param \Plasma\SQL\Blueprints\ColumnBlueprintInterface  $column
     * @return $this
     */
    function dropColumn(\Plasma\SQL\Blueprints\ColumnBlueprintInterface $column): self {
        $this->type = static::QUERY_TYPE_DROP_COLUMN;
        $this->columns = array($column);
        
        return $this;
    }
    
    /**
     * Returns a locked querybuilder to run against the database.
     * @return \Plasma\QueryBuilderInterface
     */
    function getQuerybuilder(): \Plasma\QueryBuilderInterface {
        return (new \Plasma\SQL\LockedQueryBuilder($this->getSQL($this->grammar), array()));
    }
    
    /**
     * Turns the blueprint into a SQL query.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \RuntimeException
     * @throws \Plasma\Exception
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        switch($this->type) {
            default:
                throw new \Plasma\Exception('Unknown query type');
            break;
        }
    }
}
