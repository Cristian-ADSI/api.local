<?php
namespace Services\RestService;

use Services\RestService\Interfaces\RouterControllerInterface;
use Services\Utils\HttpResponses;

class Api
{
    private HttpResponses               $httpResponse;
    private RouterControllerInterface   $routerController;

    public function __construct(?RouterControllerInterface $routerController = null)
    {
        $this->httpResponse = new HttpResponses();
        $this->routerController = $routerController ?? $this->createDefaultRouterController();
    }

    public function run($httpMethod, $requestData)
    {
        return $this->routerController->loadEndpoint($httpMethod, $requestData);
    }

    /**
     * Creates the default router controller
     * This method is separated to maintain single responsibility
     * 
     * @return RouterControllerInterface
     */
    private function createDefaultRouterController()
    {
        return new \Services\RestService\Controllers\RouterController($this->httpResponse);
    }
}
