<?php
namespace Services\RestService\Interfaces;

interface RouterControllerInterface
{
    /**
     * Load and process the endpoint based on the HTTP method and data
     * 
     * @param string $method The HTTP method (GET, POST, PUT, DELETE)
     * @param array $data The request data
     * @return mixed The response from the endpoint
     */
    public function loadEndpoint( string $httpMethod, array $requestData):array;
} 