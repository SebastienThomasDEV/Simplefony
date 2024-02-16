<?php

namespace Mvc\Framework\Core;
use Mvc\Framework\Core\Http\JsonResponse;
use Mvc\Framework\Core\Http\Response;

class AbstractController
{
    /**
     * send a JSON response
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
