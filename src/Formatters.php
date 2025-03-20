<?php

namespace Differ\Formatters;

use function Differ\Formatters\Json\formatToJson;
use function Differ\Formatters\Stylish\formatToStylish;
use function Differ\Formatters\Plain\formatToPlain;

function stringify(array $data, string $formatName): string
{
    $result = match ($formatName) {
        'plain' => formatToPlain($data),
        'json' => formatToJson($data),
        default => formatToStylish($data)
    };
    return $result;
}
