<?php
/**
 * Plasma SQL common component
 * Copyright 2019 PlasmaPHP, All Rights Reserved
 *
 * Website: https://github.com/PlasmaPHP
 * License: https://github.com/PlasmaPHP/sql-common/blob/master/LICENSE
*/

namespace Plasma\SQL\ColumnDatatypes;

interface ColumnTypeInterface {
    /**
     * Small-range integer, 2 bytes, -32768 to +32767.
     * @var string
     */
    const SQL_SMALLINT = 'SMALLINT';
    
    /**
     * Typical-choice integer, 4 bytes, -2147483648 to +2147483647.
     * @var string
     */
    const SQL_INTEGER = 'INTEGER';
    
    /**
     * Large-range integer, 8 bytes, -9223372036854775808 to 9223372036854775807.
     * @var string
     */
    const SQL_BIGINT = 'BIGINT';
    
    /**
     * User-specified precision, exact.
     * MySQL: The maximum number of digits (M) for DECIMAL is 65. The maximum number of supported decimals (D) is 30.
     * PostgreSQL: Up to 131072 digits before the decimal point; Up to 16383 digits after the decimal point.
     * @var string
     */
    const SQL_DECIMAL = 'DECIMAL';
    
    /**
     * Variable-precision, 32 bits, inexact.
     * @var string
     */
    const SQL_REAL = 'REAL';
    
    /**
     * Variable-precision, 64 bits, inexact.
     * @var string
     */
    const SQL_DOUBLE = 'DOUBLE PRECISION';
    
    /**
     * Boolean. In MySQL this is an alias for `TINYINT(1)`.
     * @var string
     */
    const SQL_BOOL = 'BOOLEAN';
    
    /**
     * Fixed length bits.
     * @var string
     */
    const SQL_BIT = 'BIT';
    
    /**
     * Variable length bits.
     * @var string
     */
    const SQL_VARBIT = 'VARBIT';
    
    /**
     * Fixed-length characters, blank padded.
     * @var string
     */
    const SQL_CHAR = 'CHAR';
    
    /**
     * Variable-length characters with limit.
     * @var string
     */
    const SQL_VARCHAR = 'VARCHAR';
    
    /**
     * Date.
     * @var string
     */
    const SQL_DATE = 'DATE';
    
    /**
     * Time without timezone.
     * MySQL does NOT have any timezone information.
     * Time should ALWAYS be stored in UTC.
     * With `\DateTime` changing the timezone is one call.
     * @var string
     * @see https://secure.php.net/manual/en/datetime.settimezone.php
     */
    const SQL_TIME = 'TIME';
    
    /**
     * Date and time without timezone.
     * MySQL does NOT have any timezone information.
     * Date and time should ALWAYS be stored in UTC.
     * With `\DateTime` changing the timezone is one call.
     *
     * It should be noted that timestamp should be the preferred way to
     * store date and time, with a second column denoting the timezone.
     *
     * This is for compatibility with other DBMS (Postgres has a type with timezone)
     * @var string
     * @see https://secure.php.net/manual/en/datetime.settimezone.php
     */
    const SQL_TIMESTAMP = 'TIMESTAMP';
    
    /**
     * Variable length tiny string, max. 255 ASCII characters.
     * @var string
     */
    const MYSQL_TINYTEXT = 'TINYTEXT';
    
    /**
     * Variable length string, max. 65535 ASCII characters.
     * @var string
     */
    const MYSQL_TEXT = 'TEXT';
    
    /**
     * Variable length medium string, max. 16777215 ASCII characters (16 MiB).
     * @var string
     */
    const MYSQL_MEDIUMTEXT = 'MEDIUMTEXT';
    
    /**
     * Variable length long string, max. 4294967295 ASCII characters (4 GiB).
     * @var string
     */
    const MYSQL_LONGTEXT = 'LONGTEXT';
    
    /**
     * MySQL's extension for Date and time, but still no timezone.
     * @var string
     */
    const MYSQL_DATETIME = 'DATETIME';
    
    /**
     * Variable unlimited length string.
     * @var string
     */
    const PGSQL_TEXT = 'TEXT';
    
    /**
     * Time interval.
     * @var string
     */
    const PGSQL_INTERVAL = 'INTERVAL';
    
    /**
     * PostgreSQL's extension for time with timezone.
     * @var string
     */
    const PGSQL_TIME_WITH_TZ = 'TIME WITH TIME ZONE';
    
    /**
     * PostgreSQL's extension for timestamp with timezone.
     * @var string
     */
    const PGSQL_TIMESTAMP_WITH_TZ = 'TIMESTAMP WITH TIME ZONE';
    
    /**
     * Get the SQL string.
     * @param \Plasma\SQL\GrammarInterface|null       $grammar
     * @param \Plasma\SQL\Blueprints\ColumnBlueprint  $column
     * @return string
     */
    static function getSQL(?\Plasma\SQL\GrammarInterface $grammar, \Plasma\SQL\Blueprints\ColumnBlueprint $column): string;
}
