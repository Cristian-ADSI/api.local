<?php
namespace Services\Rest\Controllers;

use Services\Rest\Models\GetModel;

/**
 * Controlador para manejar las operaciones GET de la API REST
 * 
 * Esta clase proporciona métodos para procesar solicitudes GET
 * y generar respuestas HTTP apropiadas.
 */
class GetController
{
    /**
     * Procesa una solicitud GET estándar y devuelve la respuesta
     * 
     * @param mixed $ARGUMENTS Argumentos de la consulta (filtros, parámetros, etc.)
     * @param object $HTTPRESPONSE Objeto para manejar las respuestas HTTP
     * @return void
     * @throws InvalidArgumentException Si los parámetros requeridos no están definidos
     */
    public static function getResponse($ARGUMENTS, $HTTPRESPONSE)
    {
        // Validar parámetros de entrada
        if (!isset($ARGUMENTS) || !isset($HTTPRESPONSE)) {
            throw new InvalidArgumentException('Los parámetros ARGUMENTS y HTTPRESPONSE son requeridos');
        }

        $queryData = GetModel::getData($ARGUMENTS);

        return GetController::setResponse($queryData, $HTTPRESPONSE);
    }

    /**
     * Procesa una solicitud GET con filtros específicos y devuelve la respuesta
     * 
     * @param mixed $ARGUMENTS Argumentos de la consulta con filtros aplicados
     * @param object $HTTPRESPONSE Objeto para manejar las respuestas HTTP
     * @return void
     * @throws InvalidArgumentException Si los parámetros requeridos no están definidos
     */
    public static function getResponseFilter($ARGUMENTS, $HTTPRESPONSE)
    {
        // Validar parámetros de entrada
        if (!isset($ARGUMENTS) || !isset($HTTPRESPONSE)) {
            throw new InvalidArgumentException('Los parámetros ARGUMENTS y HTTPRESPONSE son requeridos');
        }

        $queryData = GetModel::getDataFilter($ARGUMENTS);
        return GetController::setResponse($queryData, $HTTPRESPONSE);
    }

    /**
     * Configura y envía la respuesta HTTP basada en los datos de la consulta
     * 
     * @param array $QUERY_DATA Datos obtenidos de la consulta a la base de datos
     * @param object $HTTPRESPONSE Objeto para manejar las respuestas HTTP
     * @return void
     */
    private static function setResponse($QUERY_DATA, $HTTPRESPONSE)
    {
        // Validar que QUERY_DATA sea un array
        if (!is_array($QUERY_DATA)) {
            echo $HTTPRESPONSE->getStatus404('Datos de consulta inválidos');
            return;
        }

        // Verificar si hay error de PDO
        if (isset($QUERY_DATA['PDOException'])) {
            echo $HTTPRESPONSE->getStatus404($QUERY_DATA['PDOException']);
            return;
        }

        // Verificar si hay datos para devolver
        if (empty($QUERY_DATA)) {
            echo $HTTPRESPONSE->getStatus404('No se encontraron datos');
            return;
        }

        // Devolver respuesta exitosa
        echo $HTTPRESPONSE->getStatus200($QUERY_DATA, count($QUERY_DATA));
    }
}
