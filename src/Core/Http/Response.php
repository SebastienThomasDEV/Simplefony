<?php

namespace Mvc\Framework\Core\Http;

class Response extends HttpResponse
{
    public function __construct(string $content = '', int $status = 200, array $headers = [])
    {
        parent::__construct($content, $status, $headers);
    }
}