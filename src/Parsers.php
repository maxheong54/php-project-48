<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function getFileContent(string $path): array
{
    $absolutePath = realpath($path);
    if (!$absolutePath) {
        return [];
    }

    $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
    $fileValue = file_get_contents($absolutePath);

    $fileValue = match ($extension) {
        'yml', 'yaml' => Yaml::parseFile($absolutePath),
        'json' => $fileValue ? json_decode($fileValue, true) : [],
        default => []
    };

    return is_array($fileValue) ? $fileValue : [];
}
