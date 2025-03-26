<?php

namespace Differ\Formatters;

use RuntimeException;

use function Differ\Formatters\Json\formatToJson;
use function Differ\Formatters\Stylish\formatToStylish;
use function Differ\Formatters\Plain\formatToPlain;

function stringify(array $data, string $formatName): string
{
    $result = match ($formatName) {
        'plain' => formatToPlain($data),
        'json' => formatToJson($data),
        'stylish' => formatToStylish($data),
        default => throw new RuntimeException("Unsupported output format: {$formatName}")
    };
    return $result;
}
