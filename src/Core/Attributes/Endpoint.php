<?php

namespace Mvc\Framework\Core\Attributes;

#[\Attribute(\Attribute::TARGET_METHOD | \Attribute::TARGET_CLASS)]
class Endpoint
{

        public function __construct(private readonly string $path, private readonly string $name){}

        public function getPath(): string
        {
            return $this->path;
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function getUri(): string
        {
            return $this->uri;
        }

}