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
 * Describes how to interact with column index blueprints.
 */
interface ColumnIndexBlueprintInterface extends \Plasma\SQL\BlueprintInterface {
    /**
     * Get the name.
     * @return \Plasma\SQL\QueryExpressions\Column
     */
    function getName(): \Plasma\SQL\QueryExpressions\Column;
    
    /**
     * Set the name.
     * @param \Plasma\SQL\QueryExpressions\Column|string  $name
     * @return self
     * @throws \InvalidArgumentException
     */
    function setName($name): self;
    
    /**
     * Whether the column is the primary key.
     * @return bool
     */
    function getPrimary(): bool;
    
    /**
     * Set the column as primary key.
     * @param bool  $primary
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
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
     * @return self
     */
    function setCheck(?string $check): self;
}
