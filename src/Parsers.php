<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;
use RuntimeException;

function getFileContent(string $path): array
{
    $fileValue = file_get_contents($path);
    if ($fileValue === false) {
        throw new RuntimeException("Failed to read file: {$path}");
    }

    $extension = pathinfo($path, PATHINFO_EXTENSION);
    $format = match ($extension) {
        'yaml', 'yml' => 'yaml',
        'json' => 'json',
        default => throw new RuntimeException("Unsupported file format {$extension}")
    };

    return ['data' => $fileValue, 'format' => $format];
}

function parseData(string $data, string $format): array
{
    $result = match ($format) {
        'yaml' => Yaml::parse($data),
        'json' => json_decode($data, true),
        default => throw new \Error("Unsupported file format {$format}")
    };

    return $result;
}
