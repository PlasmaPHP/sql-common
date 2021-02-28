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
use Plasma\SQL\QueryExpressions\Constraint;

/**
 * Represents an ON CONFLICT resolution.
 */
class OnConflict {
    /**
     * On conflict, an error will be raised. This is the default.
     * @var int
     * @source
     */
    const RESOLUTION_ERROR = 0;
    
    /**
     * On conflict, nothing will be done. The error will be ignored.
     * @var int
     * @source
     */
    const RESOLUTION_DO_NOTHING = 1;
    
    /**
     * On conflict, replaces all columns, except the primary key.
     * This includes auto generated columns such as `created_at`
     * and `updated_at`.
     * @var int
     * @source
     */
    const RESOLUTION_REPLACE_ALL = 2;
    
    /**
     * On conflict, it will replace the defined columns.
     * @var int
     * @source
     */
    const RESOLUTION_REPLACE_COLUMNS = 4;
    
    /**
     * @var int
     */
    protected $type = self::RESOLUTION_ERROR;
    
    /**
     * @var Column[]
     */
    protected $replaceColumns = array();
    
    /**
     * @var ConflictTargetInterface[]
     */
    protected $conflictTargets = array();
    
    /**
     * Constructor.
     * @param int  $type  The conflict resolution type.
     * @throws \InvalidArgumentException
     */
    function __construct(int $type) {
        switch($type) {
            case static::RESOLUTION_ERROR:
            case static::RESOLUTION_DO_NOTHING:
            case static::RESOLUTION_REPLACE_ALL:
            case static::RESOLUTION_REPLACE_COLUMNS:
                // Do nothing
            break;
            default:
                throw new \InvalidArgumentException('Unknown conflict resolution type');
        }
        
        $this->type = $type;
    }
    
    /**
     * Get the conflict resolution type.
     * For the meanings of the value,
     * see the class constants.
     * @return int
     */
    function getType(): int {
        return $this->type;
    }
    
    /**
     * Get the columns to replace.
     * Only has any value when the correct type is set.
     * @return Column[]
     */
    function getReplaceColumns(): array {
        return $this->replaceColumns;
    }
    
    /**
     * Adds a column to replace with the new value on conflict.
     * This has only any meaning when using the replace columns resolution.
     * @param QueryExpressions\Column|string  $column
     * @return $this
     * @throws \InvalidArgumentException
     */
    function addReplaceColumn($column): self {
        if(\is_string($column)) {
            $column = new Column($column, null, true);
        }
        
        if(!($column instanceof Column)) {
            throw new \InvalidArgumentException('Invalid column (not a string or Column)');
        }
        
        $this->replaceColumns[] = $column;
        return $this;
    }
    
    /**
     * Get the conflict targets.
     * @return ConflictTargetInterface[]
     */
    function getConflictTargets(): array {
        return $this->conflictTargets;
    }
    
    /**
     * Add a conflict target.
     * @param QueryExpressions\Column|QueryExpressions\Constraint|string  $target
     * @return $this
     * @throws \InvalidArgumentException
     */
    function addConflictTarget($target): self {
        if(\is_string($target)) {
            $target = new Column($target, null, true);
        }
        
        if(
            !($target instanceof Column) &&
            !($target instanceof Constraint)
        ) {
            throw new \InvalidArgumentException('Invalid conflict target (not a string or Column or Constraint)');
        }
        
        $this->conflictTargets[] = $target;
        return $this;
    }
}
