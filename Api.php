<?php
namespace Services\Rest;

use Services\Rest\Interfaces\RouterControllerInterface;
use Services\Utils\HttpResponses;

class Api
{
    private HttpResponses               $httpResponse;
    private RouterControllerInterface   $routerController;

    public function __construct(?RouterControllerInterface $routerController = null)
    {
        $this->httpResponse = new HttpResponses();
        $this->routerController = $this->createDefaultRouterController();
    }

    public function run($httpMethod, $requestData)
    {
        if ($this->routerController === null) {
            $this->routerController = $this->createDefaultRouterController();
        }

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
        return new \Services\Rest\Controllers\RouterController($this->httpResponse);
    }
}
