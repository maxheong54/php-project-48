<?php

namespace Php\Project\Differ;

use function Functional\sort;
use function Php\Project\Parsers\getFileContent;
use function Php\Project\Formatter\stringify;

function genDiff(string $pathToFile1, string $pathToFile2, string $format = 'stylish'): string
{
    $valueFile1 = getFileContent($pathToFile1);
    $valueFile2 = getFileContent($pathToFile2);

    $result = getDiff($valueFile1, $valueFile2);

    return stringify($result, $format);
}

function getDiff(array $valueFile1, array $valueFile2): array
{
    $keys = getKeys($valueFile1, $valueFile2);

    $iter = function (array $acc, string $key) use ($valueFile1, $valueFile2): array {
        $hasKey1 = array_key_exists($key, $valueFile1);
        $hasKey2 = array_key_exists($key, $valueFile2);

        $value1 = $hasKey1 ? $valueFile1[$key] : null;
        $value2 = $hasKey2 ? $valueFile2[$key] : null;

        if ($hasKey1 && $hasKey2) {
            if (is_array($value1) && is_array($value2)) {
                $newValue = getDiff($value1, $value2);
                $acc[$key] = ['key' => $key, 'children' => $newValue];
                return $acc;
            }
            if ($value1 === $value2) {
                $acc[] = ['key' => $key, 'status' => '  ', 'value' => $value1];
                return $acc;
            }
        }
        if ($hasKey1) {
            $acc[] = ['key' => $key, 'status' => '- ', 'value' => $value1];
        }
        if ($hasKey2) {
            $acc[] = ['key' => $key, 'status' => '+ ', 'value' => $value2];
        }

        return $acc;
    };

    $result = array_reduce($keys, $iter, []);
    return $result;
}

function getKeys(array $arr1, array $arr2): array
{
    return sort(
        array_unique([...array_keys($arr1), ...array_keys($arr2)]),
        fn($left, $right): int => strcmp($left, $right)
    );
}
