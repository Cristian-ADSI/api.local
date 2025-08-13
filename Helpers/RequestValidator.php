<?php

namespace Services\RestService\Helpers;

use Services\Utils\HttpResponses;
use Services\Utils\Logger;

class RequestValidator
{
    public function validateTableName(array $requestData): string
    {

        $tableName = $requestData['table'] ?? NULL;
        $tableName = trim($tableName);

        if (empty($tableName)) {
            $errorMessage = "Table name is required and cannot be empty";
            
            echo HttpResponses::getStatus400($errorMessage);
            Logger::log("ERROR", $errorMessage, __FILE__, __LINE__);

            throw new \InvalidArgumentException($errorMessage);
        }

        return $tableName;
    }

    public function validateHttpMethod(string $httpMethod): void
    {
        $supportedMethods = ['POST']; // Agregar GET, PUT, DELETE cuando se implementen
        
        if (!in_array($httpMethod, $supportedMethods)) {
            $errorMessage = "HTTP method '{$httpMethod}' is not supported for this endpoint";
            echo HttpResponses::getStatus405($errorMessage);
            Logger::log("ERROR", $errorMessage, __FILE__, __LINE__);
            
            throw new \InvalidArgumentException($errorMessage);
        }
    }
} 