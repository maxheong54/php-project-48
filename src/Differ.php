<?php

namespace Php\Project\Differ;

use function Functional\sort;
use function Php\Project\Parsers\getFileContent;

function genDiff(string $pathToFile1, string $pathToFile2): string
{
    $valueFile1 = getFileContent($pathToFile1);
    $valueFile2 = getFileContent($pathToFile2);

    $keys = sort(
        array_unique([...array_keys($valueFile1), ...array_keys($valueFile2)]),
        fn($left, $right): int => strcmp($left, $right)
    );

    $iter = function (array $acc, string $key) use ($valueFile1, $valueFile2): array {
        $hasKey1 = array_key_exists($key, $valueFile1);
        $hasKey2 = array_key_exists($key, $valueFile2);

        $value1 = $hasKey1 ? $valueFile1[$key] : null;
        $value2 = $hasKey2 ? $valueFile2[$key] : null;

        if ($hasKey1 && $hasKey2 && $value1 === $value2) {
            $acc[] = "    {$key}: {$value1}";
            return $acc;
        }
        if ($hasKey1) {
            $acc[] = "  - {$key}: {$value1}";
        }
        if ($hasKey2) {
            $acc[] = "  + {$key}: {$value2}";
        }

        return $acc;
    };

    $result = array_reduce($keys, $iter, []);

    return implode("\n", ['{', ...$result, '}']);
}
