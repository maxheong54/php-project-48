<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getFileContent(string $path): array
{
    $absolutePath = realpath($path);
    if ($absolutePath === false) {
        return [];
    }

    $fileValue = file_get_contents($absolutePath);
    if ($fileValue === false) {
        return [];
    }

    $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
    $format = match ($extension) {
        'yaml', 'yml' => 'yaml',
        'json' => 'json',
        default => throw new \Error("Unsupported file format {$extension}")
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

    return is_array($result) ? $result : [];
}
