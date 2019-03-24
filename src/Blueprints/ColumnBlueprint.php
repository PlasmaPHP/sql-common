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
 * A blueprint used for creating and altering columns.
 */
class ColumnBlueprint implements ColumnBlueprintInterface {
    /**
     * @var \Plasma\SQL\QueryExpressions\Column
     */
    protected $name;
    
    /**
     * @var string
     */
    protected $type;
    
    /**
     * @var bool
     */
    protected $nullable = false;
    
    /**
     * @var bool
     */
    protected $unsigned = false;
    
    /**
     * @var int|null
     */
    protected $length;
    
    /**
     * @var int|null
     */
    protected $fraction;
    
    /**
     * @var bool
     */
    protected $hasDefault = false;
    
    /**
     * @var mixed
     */
    protected $default;
    
    /**
     * @var string|null
     */
    protected $collate;
    
    /**
     * @var bool
     */
    protected $autoIncrement = false;
    
    /**
     * @var bool
     */
    protected $primary = false;
    
    /**
     * @var bool
     */
    protected $unique = false;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\References|null
     */
    protected $references;
    
    /**
     * @var \Plasma\SQL\QueryExpressions\Constraint|null
     */
    protected $constraint;
    
    /**
     * @var string|null
     */
    protected $check;
    
    /**
     * Creates a new blueprint.
     * @return self
     */
    static function create() {
        return (new static());
    }
    
    /**
     * Get the name.
     * @return \Plasma\SQL\QueryExpressions\Column
     */
    function getName(): \Plasma\SQL\QueryExpressions\Column {
        return $this->name;
    }
    
    /**
     * Set the name.
     * @param \Plasma\SQL\QueryExpressions\Column|string  $name
     * @return $this
     * @throws \InvalidArgumentException
     */
    function setName($name): self {
        if($name instanceof \Plasma\SQL\QueryExpressions\Column && $name->getAlias() !== null) {
            throw new \InvalidArgumentException('Invalid Column instance - no alias allowed');
        }
        
        $this->name = ($name instanceof \Plasma\SQL\QueryExpressions\Column ? $name : (new \Plasma\SQL\QueryExpressions\Column($name, null, true)));
        return $this;
    }
    
    /**
     * Get the type.
     * @return string
     * @see \Plasma\SQL\ColumnDatatypes\ColumnTypeInterface
     */
    function getType(): string {
        return $this->type;
    }
    
    /**
     * Set the type.
     * @param string  $type
     * @return $this
     */
    function setType(string $type): self {
        $this->type = $type;
        return $this;
    }
    
    /**
     * Whether the column accepts null.
     * @return bool
     */
    function getNullable(): bool {
        return $this->nullable;
    }
    
    /**
     * Set the column to accept null.
     * @param bool  $nullable
     * @return $this
     */
    function setNullable(bool $nullable): self {
        $this->nullable = $nullable;
        return $this;
    }
    
    /**
     * Whether the column is unsigned.
     * @return bool
     */
    function getUnsigned(): bool {
        return $this->unsigned;
    }
    
    /**
     * Set the column as unsigned.
     * @param bool  $unsigned
     * @return $this
     */
    function setUnsigned(bool $unsigned): self {
        $this->unsigned = $unsigned;
        return $this;
    }
    
    /**
     * Get the column length.
     * @return int|null
     */
    function getLength(): ?int {
        return $this->length;
    }
    
    /**
     * Set the column length.
     * @param int|null  $length  `null` = default.
     * @return $this
     */
    function setLength(?int $length): self {
        $this->length = $length;
        return $this;
    }
    
    /**
     * Get the column fraction (used by decimal).
     * @return int|null
     */
    function getFraction(): ?int {
        return $this->fraction;
    }
    
    /**
     * Set the column fraction (used by decimal).
     * @param int  $length
     * @return $this
     */
    function setFraction(int $fraction): self {
        $this->fraction = $fraction;
        return $this;
    }
    
    /**
     * Whether the column has a default value.
     * @return bool
     */
    function hasDefault(): bool {
        return $this->hasDefault;
    }
    
    /**
     * Get the default value.
     * @return mixed
     */
    function getDefault() {
        return $this->default;
    }
    
    /**
     * Set the default value.
     * @param mixed  $default
     * @return $this
     */
    function setDefault($default): self {
        $this->hasDefault = true;
        $this->default = $default;
        
        return $this;
    }
    
    /**
     * Get the column collate.
     * @return string|null
     */
    function getCollate(): ?string {
        return $this->collate;
    }
    
    /**
     * Set the column collate.
     * @param string|null  $collate
     * @return $this
     */
    function setCollate(?string $collate): self {
        $this->collate = $collate;
        return $this;
    }
    
    /**
     * Whether the column is auto incremented.
     * @return bool
     */
    function getAutoIncrement(): bool {
        return $this->autoIncrement;
    }
    
    /**
     * Set the column to auto incremented.
     * @param bool  $autoIncrement
     * @return $this
     */
    function setAutoIncrement(bool $autoIncrement = true): self {
        $this->autoIncrement = $autoIncrement;
        return $this;
    }
    
    /**
     * Whether the column is the primary key.
     * @return bool
     */
    function getPrimary(): bool {
        return $this->primary;
    }
    
    /**
     * Set the column as primary key.
     * @param bool  $primary
     * @return $this
     */
    function setPrimary(bool $primary = true): self {
        $this->primary = $primary;
        return $this;
    }
    
    /**
     * Whether the column is the unique key.
     * @return bool
     */
    function getUnique(): bool {
        return $this->unique;
    }
    
    /**
     * Set the column as unique key.
     * @param bool  $unique
     * @return $this
     */
    function setUnique(bool $unique = true): self {
        $this->unique = $unique;
        return $this;
    }
    
    /**
     * Get the reference the column references.
     * @return \Plasma\SQL\QueryExpressions\References|null
     */
    function getReferences(): ?\Plasma\SQL\QueryExpressions\References {
        return $this->references;
    }
    
    /**
     * Set the column reference.
     * @param \Plasma\SQL\QueryExpressions\References|null  $references
     * @return $this
     */
    function setReferences(?\Plasma\SQL\QueryExpressions\References $references): self {
        $this->references = $references;
        return $this;
    }
    
    /**
     * Get the column constraint.
     * @return \Plasma\SQL\QueryExpressions\Constraint|null
     */
    function getConstraint(): ?\Plasma\SQL\QueryExpressions\Constraint {
        return $this->constraint;
    }
    
    /**
     * Set the column constraint.
     * @param \Plasma\SQL\QueryExpressions\Constraint|null  $constraint
     * @return $this
     */
    function setConstraint(?\Plasma\SQL\QueryExpressions\Constraint $constraint): self {
        $this->constraint = $constraint;
        return $this;
    }
    
    /**
     * Get the column constraint check.
     * @return string|null
     */
    function getCheck(): ?string {
        return $this->check;
    }
    
    /**
     * Set the column constraint check.
     * @param string|null  $check
     * @return $this
     */
    function setCheck(?string $check): self {
        $this->check = $check;
        return $this;
    }
    
    /**
     * Turns the blueprint into a SQL query.
     * @param \Plasma\SQL\GrammarInterface|null  $grammar
     * @return string
     * @throws \RuntimeException
     * @throws \Plasma\Exception
     */
    function getSQL(?\Plasma\SQL\GrammarInterface $grammar): string {
        if($this->type !== null) {
            $type = (\strpos($type, '\\') === false ? '\\Plasma\\SQL\\Blueprints\\'.\str_replace(' ', '', \ucwords(\strtolower($this->type))).'Type' : $this->type);
            if(!\class_exists($type, true)) {
                throw new \RuntimeException('Unknown type "'.$this->type.'" - unable to handle column blueprint');
            }
        }
        
        /** @var \Plasma\SQL\ColumnDatatypes\ColumnTypeInterface  $type */
        
        $sql = $this->name->getSQL($grammar).($this->type !== null ? ' '.$type::getSQL($grammar, $this) : '');
        
        if($this->collate !== null) {
            $sql .= ' COLLATE '.$this->collate;
        }
        
        if($this->constraint !== null) {
            $sql .= ' CONSTRAINT '.$this->constraint->getName();
        }
        
        if($this->references !== null) {
            if($grammar === null) {
                throw new \Plasma\Exception('Grammar can not be null when using references');
            }
            
            $sql .= 'REFERENCES '.$this->references->getSQL($grammar);
        } elseif($this->check !== null) {
            $sql .= ' CHECK('.$this->check.')';
        } elseif($this->primary) {
            $sql .= ' PRIMARY KEY';
        } elseif($this->unique) {
            $sql .= ' UNIQUE';
        } elseif($this->hasDefault) {
            $sql .= ' DEFAULT '.(\is_string($this->default) ? '"'.$this->default.'"' : $this->default);
        } elseif($this->nullable) {
            $sql .= ' NULL';
        } elseif($this->type !== null) {
            $sql .= ' NOT NULL';
        }
        
        return $sql;
    }
}
