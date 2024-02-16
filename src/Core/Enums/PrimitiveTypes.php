<?php

namespace Mvc\Framework\Core\Enums;

enum PrimitiveTypes
{
    case STRING;
    case INT;
    case FLOAT;
    case BOOL;
    case ARRAY;
    case OBJECT;
    case NULL;

    public static function isPrimitiveFromString(string $type): bool
    {
        return match ($type) {
            'string', 'int', 'float', 'bool', 'array', 'object', 'null' => true,
            default => false,
        };
    }

}
