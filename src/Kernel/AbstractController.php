<?php

namespace Mvc\Framework\Kernel;
use Mvc\Framework\Kernel\Http\JsonResponse;
use Mvc\Framework\Kernel\Http\Response;

class AbstractController
{
    /**
     * send a JSON response to the client with the given data as an array
     *
     * @param array $vars
     * @return void
     */
    public final function send(array $vars): void
    {
        try {
            $response = new JsonResponse($vars, 200, ['Content-Type' => 'application/json']);
            $response->send();
        } catch (\Exception $e) {
            $vars = [
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
            $response = new JsonResponse($vars, 500, ['Content-Type' => 'application/json']);
            $response->send();
        }
    }


}
