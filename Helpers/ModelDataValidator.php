<?php

namespace Services\RestService\Helpers;

use Services\RestService\Interfaces\ModelValidatorInterface;

/**
 * Helper for validating allowed tables and columns for models.
 */
class ModelDataValidator implements ModelValidatorInterface
{
    /**
     * Whitelist of allowed tables.
     * @var array<string>
     */
    private static array $allowedTables = ['orders'];

    /**
     * Whitelist of allowed columns per table.
     * @var array<string, array<string>>
     */
    private static array $allowedColumns = [
        'orders' => ['id_protein', 'id_rice', 'id_salad', 'id_juice','id_snack','is_menu_day'],
    ];

    /**
     * Validates if the table name is allowed.
     *
     * @param string $table The table name to validate.
     * @return void
     * @throws \InvalidArgumentException If the table name is not allowed.
     */
    public function validateTable(string $table): void
    {
        if (!in_array($table, self::$allowedTables, true)) {
            throw new \InvalidArgumentException("Invalid table name");
        }
    }

    /**
     * Validates if the column name is allowed for the given table.
     *
     * @param string $table The table name.
     * @param string $column The column name to validate.
     * @return void
     * @throws \InvalidArgumentException If the column name is not allowed for the table.
     */
    public function validateColumn(string $table, string $column): void
    {
        if (!isset(self::$allowedColumns[$table]) || !in_array($column, self::$allowedColumns[$table], true)) {
            throw new \InvalidArgumentException("Invalid column name: $column");
        }
    }
}
