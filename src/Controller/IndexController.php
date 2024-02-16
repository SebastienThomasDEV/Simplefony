<?php

namespace Mvc\Framework\Controller;

use Mvc\Framework\Core\AbstractController;
use Mvc\Framework\Core\Attributes\Endpoint;
use Mvc\Framework\Core\Utils\Request;

class IndexController extends AbstractController
{
    #[Endpoint('/', 'index')]
    public final function index(Request $request): void
    {
        dd($request);
        $this->send([
            'message' => 'Hello World'
        ]);
    }





}
