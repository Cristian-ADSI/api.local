<?php

namespace Services\RestService\Controllers;

use Services\RestService\Interfaces\RouterControllerInterface;
use Services\RestService\Helpers\RequestValidator;
use Services\RestService\Helpers\RequestHandlerFactory;


class RouterController implements RouterControllerInterface
{
    private RequestValidator $validator;
    private RequestHandlerFactory $handlerFactory;

    public function __construct()
    {
        $this->validator = new RequestValidator();
        $this->handlerFactory = new RequestHandlerFactory();
    }

    public function loadEndpoint(string $httpMethod, array $requestData): array
    {
        // Validar el método HTTP
        $this->validator->validateHttpMethod($httpMethod);
        
        // Validar y obtener el nombre de la tabla
        $this->validator->validateTableName($requestData);
        
        // Crear el handler apropiado y ejecutar la acción
        $handler = $this->handlerFactory->createHandler($httpMethod);

        return $this->executeHandler($handler, $httpMethod, $requestData);        
    }

    private function executeHandler(object $handler, string $httpMethod, array $requestData): array
    {
        $response = [];
        
        match ($httpMethod) {
            'POST' => $response = $handler->createPost($requestData),
            // 'GET' => $handler->getResponse($requestData),
            // 'PUT' => $handler->putResponse($requestData),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$httpMethod}")
        };

        return $response;
    }

    // private function GETRequest()
    // {
    //     $arguments = [
    //         'table'         => $this->tableName,
    //         'startAt'       => $_GET['startAt']   ?? NULL,
    //         'endAt'         => $_GET['endAt']     ?? NULL,
    //         'orderBy'       => $_GET['orderBy']   ?? NULL,
    //         'orderMode'     => $_GET['orderMode'] ?? 'ASC',
    //         'select'        => $_GET['select']    ?? '*',
    //     ];

    //     $filters   = isset($_GET['filterColumns']) && isset($_GET['filterValues']);

    //     if ($filters) {
    //         $arguments['filterColumns']  = $_GET['filterColumns'];
    //         $arguments['filterValues']   = $_GET['filterValues'];

    //         GetController::getResponseFilter($arguments, $this->httpResponse);
    //     } else {

    //         GetController::getResponse($arguments,   $this->httpResponse);
    //     }

    //     return;
    // }

    // private function PUTRequest()
    // {
    //     $key    = $_GET['keyColumn']   ?? '';
    //     $value  = $_GET['keyValue']    ?? '';

    //     $emptyKeyValue = (empty(trim($key)) || empty(trim($value)));

    //     $_PUT = [];
    //     parse_str(file_get_contents('php://input'), $_PUT);

    //     if (count($_PUT) == 0) {
    //         $this->httpResponse->getStatus400("Missing POST parameters in the request");
    //     } elseif ($emptyKeyValue) {
    //         $this->httpResponse->getStatus400("Missing key/value parameters in the request");
    //     } else {
    //         PutController::putResponse($_PUT, $_GET, $this->tableName, $this->httpResponse);
    //     }

    //     return;
    // }
}
