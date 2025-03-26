<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use RuntimeException;

function parseData(string $data, string $format): array
{
    $result = match ($format) {
        'yaml' => Yaml::parse($data),
        'json' => json_decode($data, true),
        default => throw new RuntimeException("Unsupported file format {$format}")
    };

    if ($result === null) {
        throw new RuntimeException("Failed to parse {$format} data.");
    }

    return $result;
}
