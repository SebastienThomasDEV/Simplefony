<?php

namespace Mvc\Framework\Core\Http;

class JsonResponse extends HttpResponse
{
    public function __construct(array $data = [], int $status = 200, array $headers = [])
    {
        parent::__construct(json_encode($data), $status, $headers);
    }

}