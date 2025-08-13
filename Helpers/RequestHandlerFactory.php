<?php

namespace Services\RestService\Helpers;

use Services\RestService\Controllers\POSTController;
use Services\RestService\Controllers\GetController;
use Services\RestService\Controllers\PutController;

class RequestHandlerFactory
{
    public function createHandler(string $httpMethod): object
    {
        return match ($httpMethod) {
            'POST' => new POSTController(
                new PostRequestHandler(),
                new \Services\Utils\HttpResponses()
            ),
            // 'GET' => new GetController(),
            // 'PUT' => new PutController(),
            default => throw new \InvalidArgumentException("Unsupported HTTP method: {$httpMethod}")
        };
    }
} 