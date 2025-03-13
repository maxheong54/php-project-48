<?php

namespace Php\Project\Parsers;

use Symfony\Component\Yaml\Yaml;

function getFileContent(string $path): array
{
    $absolutePath = realpath($path);
    if (!$absolutePath) {
        return [];
    }

    $extension = pathinfo($absolutePath, PATHINFO_EXTENSION);
    $fileString = file_get_contents($absolutePath);

    $fileValue = match ($extension) {
        'yml' => Yaml::parseFile($absolutePath),
        'yaml' => Yaml::parseFile($absolutePath),
        'json' => $fileString ? json_decode($fileString, true) : [],
        default => []
    };

    return array_map(
        fn($item) => is_bool($item) ? json_encode($item) : $item,
        $fileValue
    );
}
