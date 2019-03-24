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
 * Describes how to interact with column blueprints.
 */
interface ColumnBlueprintInterface extends \Plasma\SQL\BlueprintInterface {
    /**
     * Get the name.
     * @return \Plasma\SQL\QueryExpressions\Column
     */
    function getName(): \Plasma\SQL\QueryExpressions\Column;
    
    /**
     * Set the name.
     * @param \Plasma\SQL\QueryExpressions\Column|string  $name
     * @return $this
     * @throws \InvalidArgumentException
     */
    function setName($name): self;
    
    /**
     * Get the type.
     * @return string
     * @see \Plasma\SQL\ColumnDatatypes\ColumnTypeInterface
     */
    function getType(): string;
    
    /**
     * Set the type.
     * @param string  $type
     * @return $this
     */
    function setType(string $type): self;
    
    /**
     * Whether the column accepts null.
     * @return bool
     */
    function getNullable(): bool;
    
    /**
     * Set the column to accept null.
     * @param bool  $nullable
     * @return $this
     */
    function setNullable(bool $nullable): self;
    
    /**
     * Whether the column is unsigned.
     * @return bool
     */
    function getUnsigned(): bool;
    
    /**
     * Set the column as unsigned.
     * @param bool  $unsigned
     * @return $this
     */
    function setUnsigned(bool $unsigned): self;
    
    /**
     * Get the column length.
     * @return int|null
     */
    function getLength(): ?int;
    
    /**
     * Set the column length.
     * @param int|null  $length  `null` = default.
     * @return $this
     */
    function setLength(?int $length): self;
    
    /**
     * Get the column fraction (used by decimal).
     * @return int|null
     */
    function getFraction(): ?int;
    
    /**
     * Set the column fraction (used by decimal).
     * @param int  $length
     * @return $this
     */
    function setFraction(int $fraction): self;
    
    /**
     * Whether the column has a default value.
     * @return bool
     */
    function hasDefault(): bool;
    
    /**
     * Get the default value.
     * @return mixed
     */
    function getDefault();
    
    /**
     * Set the default value.
     * @param mixed  $default
     * @return $this
     */
    function setDefault($default): self;
    
    /**
     * Get the column collate.
     * @return string|null
     */
    function getCollate(): ?string;
    
    /**
     * Set the column collate.
     * @param string|null  $collate
     * @return $this
     */
    function setCollate(?string $collate): self;
    
    /**
     * Whether the column is auto incremented.
     * @return bool
     */
    function getAutoIncrement(): bool;
    
    /**
     * Set the column to auto incremented.
     * @param bool  $autoIncrement
     * @return $this
     */
    function setAutoIncrement(bool $autoIncrement = true): self;
    
    /**
     * Whether the column is the primary key.
     * @return bool
     */
    function getPrimary(): bool;
    
    /**
     * Set the column as primary key.
     * @param bool  $primary
     * @return $this
     */
    function setPrimary(bool $primary = true): self;
    
    /**
     * Whether the column is the unique key.
     * @return bool
     */
    function getUnique(): bool;
    
    /**
     * Set the column as unique key.
     * @param bool  $unique
     * @return $this
     */
    function setUnique(bool $unique = true): self;
    
    /**
     * Get the reference the column references.
     * @return \Plasma\SQL\QueryExpressions\References|null
     */
    function getReferences(): ?\Plasma\SQL\QueryExpressions\References;
    
    /**
     * Set the column reference.
     * @param \Plasma\SQL\QueryExpressions\References|null  $references
     * @return $this
     */
    function setReferences(?\Plasma\SQL\QueryExpressions\References $references): self;
    
    /**
     * Get the column constraint.
     * @return \Plasma\SQL\QueryExpressions\Constraint|null
     */
    function getConstraint(): ?\Plasma\SQL\QueryExpressions\Constraint;
    
    /**
     * Set the column constraint.
     * @param \Plasma\SQL\QueryExpressions\Constraint|null  $constraint
     * @return $this
     */
    function setConstraint(?\Plasma\SQL\QueryExpressions\Constraint $constraint): self;
    
    /**
     * Get the column constraint check.
     * @return string|null
     */
    function getCheck(): ?string;
    
    /**
     * Set the column constraint check.
     * @param string|null  $check
     * @return $this
     */
    function setCheck(?string $check): self;
}
