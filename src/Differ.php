<?php

namespace Differ\Differ;

use Exception;

use function Functional\sort;
use function Differ\DataGetter\getFileContent;
use function Differ\Formatters\stringify;
use function Differ\Parsers\parseData;

function genDiff(string $pathToFile1, string $pathToFile2, string $formatName = 'stylish'): string
{
    $file1 = getFileContent($pathToFile1);
    $file2 = getFileContent($pathToFile2);

    $valueFile1 = parseData($file1['data'], $file1['format']);
    $valueFile2 = parseData($file2['data'], $file2['format']);

    $result = getDiff($valueFile1, $valueFile2);

    return stringify($result, $formatName);
}

function getDiff(array $valueFile1, array $valueFile2): array
{
    $keys = getKeys($valueFile1, $valueFile2);

    $iter = function (array $acc, string $key) use ($valueFile1, $valueFile2): array {

        $value1 = $valueFile1[$key] ?? null;
        $value2 = $valueFile2[$key] ?? null;

        if (
            (is_array($value1) && !array_is_list($value1)) &&
            (is_array($value2) && !array_is_list($value2))
        ) {
            $newValue = getDiff($value1, $value2);
            return [...$acc, ['key' => $key, 'status' => 'parent', 'value' => $newValue]];
        }

        if (!array_key_exists($key, $valueFile1)) {
            return [...$acc, ['key' => $key, 'status' => 'added', 'value' => $value2]];
        }

        if (!array_key_exists($key, $valueFile2)) {
            return [...$acc, ['key' => $key, 'status' => 'removed', 'value' => $value1]];
        }

        if ($value1 === $value2) {
            return [...$acc, ['key' => $key, 'status' => 'unchanged', 'value' => $value1]];
        }

        return [
            ...$acc,
            [
                'key' => $key,
                'status' => 'updated',
                'oldValue' => $value1,
                'newValue' => $value2,
            ],
        ];
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
