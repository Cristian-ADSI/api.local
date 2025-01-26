<?php

use app\Connection;

class GetModel
{

    public static function getData($ARGUMENTS)
    {
        try {
            $query = getModel::setQuery($ARGUMENTS);

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

        $filterValues = $ARGUMENTS['filterValues'];
        $filterValuesArray = explode(";", $filterValues);

        $filterColumns = $ARGUMENTS['filterColumns'];
        $filterColumnsArray = explode(";", $filterColumns);

        try {
            $query = getModel::setQueryFilter($ARGUMENTS);

            $stmt = Connection::connect()->prepare($query);

            foreach ($filterColumnsArray as $index => $value) {
                $stmt->bindParam(":" . $value, $filterValuesArray[$index], PDO::PARAM_STR);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS);
        } catch (PDOException $err) {

            error_log('ERROR::getModel=>getDataFilter() ' . $err->getMessage());

            return ["PDOException" => $err->getMessage()];
        }
    }

    private static function setFilters($FILTERS)
    {
        $filters = $FILTERS[0] . " = :" . $FILTERS[0] . " ";

        foreach ($FILTERS as $index => $value) {
            if ($index > 0) {
                $filters .= "AND " . $value . " = :" . $value . " ";
            }
        }

        return $filters;
    }

    private static function setQuery($ARGUMENTS)
    {
        $table =     $ARGUMENTS['table'];
        $select =    $ARGUMENTS['select']; //TODO: Renombrar "select" por "columns"
        $orderBy =   $ARGUMENTS['orderBy'];
        $orderMode = $ARGUMENTS['orderMode'];
        $startAt =   $ARGUMENTS['startAt'];
        $endAt =     $ARGUMENTS['endAt'];


        $limited =  isset($startAt) && isset($endAt)     ? TRUE : FALSE;
        $ordered =  isset($orderBy) && isset($orderMode) ? TRUE : FALSE;

        // Simple Query  without Order & Limit 

        $query = "SELECT $select FROM $table";
        // Only Order Query 
        if (!$limited &&  $ordered) {
            $query = "SELECT $select FROM $table ORDER BY $orderBy $orderMode";
        }
        // Only Limit Query 
        if ($limited && !$ordered) {
            $query = "SELECT $select FROM $table LIMIT $startAt,$endAt";
        }
        // Order & Limit Query
        if ($limited &&  $ordered) {
            $query = "SELECT $select FROM $table ORDER BY $orderBy $orderMode LIMIT $startAt,$endAt";
        }

        return $query;
    }

    private static function setQueryFilter($ARGUMENTS)
    {
        $table          = $ARGUMENTS['table'];
        $endAt          = $ARGUMENTS['endAt'];
        $orderBy        = $ARGUMENTS['orderBy'];
        $orderMode      = $ARGUMENTS['orderMode'];
        $select         = $ARGUMENTS['select'];
        $startAt        = $ARGUMENTS['startAt'];
        $filterColumns  = $ARGUMENTS['filterColumns'];

        $columnsArray = explode(";", $filterColumns);
        $filterSentence = GetModel::setFilters($columnsArray);

        $ordered = isset($orderBy) && isset($orderMode) ? TRUE : FALSE;
        $limited = isset($startAt) && isset($endAt)     ? TRUE : FALSE;

        // Simple Filtered Query
        $query = "SELECT $select FROM $table WHERE  $filterSentence";

        // Order & Limit Filtered Query
        if ($ordered   &&  $limited) {

            $query = "SELECT $select FROM $table 
            WHERE  $filterSentence 
            ORDER BY $orderBy $orderMode 
            LIMIT $startAt,$endAt";
        }
        // Only Order Filtered Query 
        if ($ordered   && !$limited) {
            $query = "SELECT $select FROM $table 
            WHERE  $filterSentence 
            ORDER BY $orderBy $orderMode";
        }
        // Only Limit Filtered Query 
        if (!$ordered  && $limited) {

            $query = "SELECT $select FROM $table 
            WHERE $filterSentence 
            LIMIT $startAt,$endAt";
        }

        return $query;
    }
}
