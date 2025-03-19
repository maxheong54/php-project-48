<?php

namespace Php\Project\Formatters;

use function Php\Project\Formatters\Stylish\formatToStylish;
use function Php\Project\Formatters\Plain\formatToPlain;

function stringify(array $data, string $formatName): string
{
    $result = match ($formatName) {
        'plain' => formatToPlain($data),
        default => formatToStylish($data, ' ', 4)
    };
    return $result;
}
