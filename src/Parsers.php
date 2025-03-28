<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use RuntimeException;

function parseData(string $data, string $format): array
{
    $result = match ($format) {
        'yaml' => Yaml::parse($data),
        'json' => json_decode($data, true, 512, JSON_THROW_ON_ERROR),
        default => throw new RuntimeException("Unsupported file format {$format}")
    };

    return $result;
}
