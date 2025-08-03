<?php

namespace Services\Rest\Controllers;

use Services\Rest\Controllers\GetController;
use Services\Rest\Controllers\POSTController;
use Services\Rest\Controllers\PutController;
use Services\Rest\Interfaces\RouterControllerInterface;
use Services\Rest\Helpers\PostRequestHandler;
use Services\Utils\HttpResponses;


class RouterController implements RouterControllerInterface
{
    private $routeArray;
    private $route;
    private HttpResponses $httpResponse;
    private $table;

    public function __construct(HttpResponses $httpResponse)
    {
        $this->routeArray = explode('/', $_SERVER['REQUEST_URI']);
        $this->routeArray = array_filter($this->routeArray);

        $this->httpResponse = $httpResponse;
        $this->route = '';
        $this->table = '';
    }

    public function loadEndpoint(string $httpMethod, array $requestData)
    {
        // 
        // $this->route = $this->routeArray[2] ?? NULL;

        // if (is_null($this->route)) {
        //     $this->httpResponse->getStatus400("Table wasn't specified through the request");
        //     return;
        // } else {
        //     $this->table = explode("?", trim($this->route) ?? '')[0];
        // }

        // if (empty(trim($this->table))) {
        //     $this->httpResponse->getStatus400("Table wasn't specified through the request");
        //     return;
        // } else {
        //     switch ($method) {
        //         case 'GET':
        //             $this->GETRequest();
        //             break;
        //         case 'POST':
        //             $this->POSTRequest();
        //             break;
        //         case 'PUT':
        //             $this->PUTRequest();
        //             break;
        //         case 'DELETE':
        //             break;
        //         default:
        //             $this->httpResponse->getStatus405();
        //             break;
        //     }
        // }

        echo "Hello World";
        return[];
    }

    private function GETRequest()
    {
        $arguments = [
            'table'         => $this->table,
            'startAt'       => $_GET['startAt']   ?? NULL,
            'endAt'         => $_GET['endAt']     ?? NULL,
            'orderBy'       => $_GET['orderBy']   ?? NULL,
            'orderMode'     => $_GET['orderMode'] ?? 'ASC',
            'select'        => $_GET['select']    ?? '*',
        ];

        $filters   = isset($_GET['filterColumns']) && isset($_GET['filterValues']);

        if ($filters) {
            $arguments['filterColumns']  = $_GET['filterColumns'];
            $arguments['filterValues']   = $_GET['filterValues'];

            GetController::getResponseFilter($arguments, $this->httpResponse);
        } else {

            GetController::getResponse($arguments,   $this->httpResponse);
        }

        return;
    }

    private function POSTRequest()
    {

        if (count($_POST) > 0) {
            $postController = new POSTController(
                new PostRequestHandler(),
                new HttpResponses()
            );

            $postController->createPost($_POST);
        } else {
            $this->httpResponse->getStatus400("Missing POST parameters in the request");
        }

        return;
    }

    private function PUTRequest()
    {
        $key    = $_GET['keyColumn']   ?? '';
        $value  = $_GET['keyValue']    ?? '';

        $emptyKeyValue = (empty(trim($key)) || empty(trim($value)));

        $_PUT = [];
        parse_str(file_get_contents('php://input'), $_PUT);

        if (count($_PUT) == 0) {
            $this->httpResponse->getStatus400("Missing POST parameters in the request");
        } elseif ($emptyKeyValue) {
            $this->httpResponse->getStatus400("Missing key/value parameters in the request");
        } else {
            PutController::putResponse($_PUT, $_GET, $this->table, $this->httpResponse);
        }

        return;
    }
}
