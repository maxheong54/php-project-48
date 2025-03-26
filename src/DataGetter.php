<?php

namespace Differ\DataGetter;

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
