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
 * Used to describe which keyword is used for the insert and what is appended to the SQL.
 */
class ConflictResolution {
    /**
     * @var string
     */
    protected $keyword;
    
    /**
     * @var string
     */
    protected $appendum;
    
    /**
     * Constructor.
     * @param string  $keyword
     * @param string  $appendum
     */
    function __construct(string $keyword, string $appendum) {
        $this->keyword = $keyword;
        $this->appendum = $appendum;
    }
    
    /**
     * Get the SQL keyword for the insert.
     * @return string;
     */
    function getKeyword(): string {
        return $this->keyword;
    }
    
    /**
     * Get the string to append to the SQL query.
     * @return string
     */
    function getAppendum(): string {
        return $this->appendum;
    }
}
