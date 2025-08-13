<?php
namespace Services\RestService\Models;


class GetModel
{

    public static function getData($ARGUMENTS)
    {
        // Validación de parámetros requeridos
        if (!isset($ARGUMENTS['table']) || !isset($ARGUMENTS['select'])) {
            return [
                'error' => 'Missing required parameters: table and select are required'
            ];
        }

        // Sanitización básica
        $ARGUMENTS['table'] = trim($ARGUMENTS['table']);
        $ARGUMENTS['select'] = trim($ARGUMENTS['select']);

        if (empty($ARGUMENTS['table']) || empty($ARGUMENTS['select'])) {
            return [
                'error' => 'Table and select parameters cannot be empty'
            ];
        }

        // Validación de seguridad para nombres de tabla y columnas
        if (!GetModel::isValidTableName($ARGUMENTS['table'])) {
            return [
                'error' => 'Invalid table name format'
            ];
        }

        if (!GetModel::isValidColumnList($ARGUMENTS['select'])) {
            return [
                'error' => 'Invalid column list format'
            ];
        }

        try {
            $query = GetModel::setQuery($ARGUMENTS);

            $stmt = Connection::connect()->prepare($query);
            $stmt->execute();

            $data = $stmt->fetchAll(PDO::FETCH_CLASS);

            if (empty($data)) {
                return  $data = [
                    'message' =>  'Table is empty'
                ];
            } else {

                return $data;
            }
        } catch (PDOException $err) {

            error_log('ERROR::GetModel=>getData() ' . $err->getMessage());

            return ["PDOException" => $err->getMessage()];
        }
    }

    public static function getDataFilter($ARGUMENTS)
    {
        // Validación de parámetros requeridos
        if (!isset($ARGUMENTS['table']) || !isset($ARGUMENTS['select']) || 
            !isset($ARGUMENTS['filterValues']) || !isset($ARGUMENTS['filterColumns'])) {
            return [
                'error' => 'Missing required parameters: table, select, filterValues and filterColumns are required'
            ];
        }

        // Sanitización básica
        $ARGUMENTS['table'] = trim($ARGUMENTS['table']);
        $ARGUMENTS['select'] = trim($ARGUMENTS['select']);
        $ARGUMENTS['filterValues'] = trim($ARGUMENTS['filterValues']);
        $ARGUMENTS['filterColumns'] = trim($ARGUMENTS['filterColumns']);

        if (empty($ARGUMENTS['table']) || empty($ARGUMENTS['select']) || 
            empty($ARGUMENTS['filterValues']) || empty($ARGUMENTS['filterColumns'])) {
            return [
                'error' => 'Table, select, filterValues and filterColumns parameters cannot be empty'
            ];
        }

        // Validación de seguridad para nombres de tabla y columnas
        if (!GetModel::isValidTableName($ARGUMENTS['table'])) {
            return [
                'error' => 'Invalid table name format'
            ];
        }

        if (!GetModel::isValidColumnList($ARGUMENTS['select'])) {
            return [
                'error' => 'Invalid column list format'
            ];
        }

        // Validación de seguridad para nombres de columnas de filtro
        $filterColumnsArray = explode(";", $ARGUMENTS['filterColumns']);
        foreach ($filterColumnsArray as $column) {
            if (!GetModel::isValidColumnName(trim($column))) {
                return [
                    'error' => 'Invalid filter column name format: ' . $column
                ];
            }
        }

        $filterValues = $ARGUMENTS['filterValues'];
        $filterValuesArray = explode(";", $filterValues);

        $filterColumns = $ARGUMENTS['filterColumns'];
        $filterColumnsArray = explode(";", $filterColumns);

        // Validación de que los arrays tengan la misma longitud
        if (count($filterValuesArray) !== count($filterColumnsArray)) {
            return [
                'error' => 'filterValues and filterColumns must have the same number of elements'
            ];
        }

        try {
            $query = GetModel::setQueryFilter($ARGUMENTS);

            $stmt = Connection::connect()->prepare($query);

            foreach ($filterColumnsArray as $index => $value) {
                $stmt->bindParam(":" . $value, $filterValuesArray[$index], PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $err) {

            error_log('ERROR::GetModel=>getDataFilter() ' . $err->getMessage());

            return ["PDOException" => $err->getMessage()];
        }
    }

    private static function setFilters($FILTERS)
    {
        $filters = $FILTERS[0] . " = :" . $FILTERS[0] . " ";

        foreach ($FILTERS as $index => $value) {
            if ($index > 0) {
                $filters .= " AND " . $value . " = :" . $value . " ";
            }
        }

        return $filters;
    }

    private static function setQuery($ARGUMENTS)
    {
        $table     = $ARGUMENTS['table'];
        $select    = $ARGUMENTS['select']; //TODO: Renombrar "select" por "columns"
        $orderBy   = isset($ARGUMENTS['orderBy']) ? trim($ARGUMENTS['orderBy']) : null;
        $orderMode = isset($ARGUMENTS['orderMode']) ? trim($ARGUMENTS['orderMode']) : null;
        $startAt   = isset($ARGUMENTS['startAt']) ? (int)$ARGUMENTS['startAt'] : null;
        $endAt     = isset($ARGUMENTS['endAt']) ? (int)$ARGUMENTS['endAt'] : null;

        // Validación de parámetros opcionales
        if ($orderBy !== null && empty($orderBy)) {
            $orderBy = null;
        }
        if ($orderMode !== null && empty($orderMode)) {
            $orderMode = null;
        }
        if ($startAt !== null && $startAt < 0) {
            $startAt = null;
        }
        if ($endAt !== null && $endAt < 0) {
            $endAt = null;
        }


        // Validación de seguridad para orderBy
        if ($orderBy !== null && !GetModel::isValidColumnName($orderBy)) {
            $orderBy = null;
        }

        // Validación de seguridad para orderMode
        if ($orderMode !== null && !in_array(strtoupper($orderMode), ['ASC', 'DESC'])) {
            $orderMode = null;
        }

        $limited = isset($startAt) && isset($endAt)     ? TRUE : FALSE;
        $ordered = isset($orderBy) && isset($orderMode) ? TRUE : FALSE;

        // Simple Query  without Order & Limit 

        $query = "SELECT $select FROM $table";
        // Only Order Query 
        if (!$limited && $ordered) {
            $query = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
        }
        // Only Limit Query 
        if ($limited && !$ordered) {
            $query = "SELECT $select FROM $table LIMIT $startAt,$endAt";
        }
        // Order & Limit Query
        if ($limited && $ordered) {
            $query = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
        }

        return $query;
    }

    private static function setQueryFilter($ARGUMENTS)
    {
        $table         = $ARGUMENTS['table'];
        $endAt         = isset($ARGUMENTS['endAt']) ? (int)$ARGUMENTS['endAt'] : null;
        $orderBy       = isset($ARGUMENTS['orderBy']) ? trim($ARGUMENTS['orderBy']) : null;
        $orderMode     = isset($ARGUMENTS['orderMode']) ? trim($ARGUMENTS['orderMode']) : null;
        $select        = $ARGUMENTS['select'];
        $startAt       = isset($ARGUMENTS['startAt']) ? (int)$ARGUMENTS['startAt'] : null;
        $filterColumns = $ARGUMENTS['filterColumns'];

        // Validación de parámetros opcionales
        if ($orderBy !== null && empty($orderBy)) {
            $orderBy = null;
        }
        if ($orderMode !== null && empty($orderMode)) {
            $orderMode = null;
        }
        if ($startAt !== null && $startAt < 0) {
            $startAt = null;
        }
        if ($endAt !== null && $endAt < 0) {
            $endAt = null;
        }

        // Validación de seguridad para orderBy
        if ($orderBy !== null && !GetModel::isValidColumnName($orderBy)) {
            $orderBy = null;
        }

        // Validación de seguridad para orderMode
        if ($orderMode !== null && !in_array(strtoupper($orderMode), ['ASC', 'DESC'])) {
            $orderMode = null;
        }

        $columnsArray = explode(";", $filterColumns);
        $filterSentence = GetModel::setFilters($columnsArray);

        $ordered = isset($orderBy) && isset($orderMode) ? TRUE : FALSE;
        $limited = isset($startAt) && isset($endAt)     ? TRUE : FALSE;

        // Simple Filtered Query
        $query = "SELECT $select FROM $table WHERE  $filterSentence";

        // Order & Limit Filtered Query
        if ($ordered && $limited) {

            $query = "SELECT $select FROM $table 
            WHERE $filterSentence 
            ORDER BY $orderBy $orderMode 
            LIMIT $startAt,$endAt";
        }
        // Only Order Filtered Query 
        if ($ordered && !$limited) {
            $query = "SELECT $select FROM $table 
            WHERE $filterSentence 
            ORDER BY $orderBy $orderMode";
        }
        // Only Limit Filtered Query 
        if (!$ordered && $limited) {

            $query = "SELECT $select FROM $table 
            WHERE $filterSentence 
            LIMIT $startAt,$endAt";
        }

        return $query;
    }

    /**
     * Valida que el nombre de la tabla sea seguro
     * @param string $tableName
     * @return bool
     */
    private static function isValidTableName($tableName)
    {
        // Solo permite letras, números, guiones bajos y puntos
        // No permite espacios, caracteres especiales ni SQL keywords
        return preg_match('/^[a-zA-Z0-9_\.]+$/', $tableName) && 
               !preg_match('/^(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|UNION|WHERE|FROM|AND|OR)$/i', $tableName);
    }

    /**
     * Valida que la lista de columnas sea segura
     * @param string $columnList
     * @return bool
     */
    private static function isValidColumnList($columnList)
    {
        // Permite múltiples columnas separadas por comas
        $columns = explode(',', $columnList);
        foreach ($columns as $column) {
            $column = trim($column);
            if (!GetModel::isValidColumnName($column)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Valida que el nombre de una columna sea seguro
     * @param string $columnName
     * @return bool
     */
    private static function isValidColumnName($columnName)
    {
        // Solo permite letras, números y guiones bajos
        // No permite espacios, caracteres especiales ni SQL keywords
        return preg_match('/^[a-zA-Z0-9_]+$/', $columnName) && 
               !preg_match('/^(SELECT|INSERT|UPDATE|DELETE|DROP|CREATE|ALTER|UNION|WHERE|FROM|AND|OR|ORDER|BY|LIMIT|GROUP|HAVING)$/i', $columnName);
    }
}
