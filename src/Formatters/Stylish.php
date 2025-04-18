<?php

namespace Differ\Formatters\Stylish;

use RuntimeException;

function toString(mixed $value): string
{
    $encoded = json_encode($value);
    return trim($encoded !== false ? $encoded : '', '"');
}

function formatToStylish(array $value, string $replacer = ' ', int $spacesCount = 4): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = $depth * $spacesCount;

        $currentIndent = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);

        $indents = [
            'removed' => '- ',
            'added' => '+ ',
        ];

        $lines = array_map(
            function ($key, $val) use (&$iter, $depth, $replacer, $indentSize, $currentIndent, $indents) {
                if (!is_array($val) || !array_key_exists('status', $val)) {
                    return "{$currentIndent}{$key}: {$iter($val, $depth + 1)}";
                }

                $statusIndent = $indents[$val['status']] ?? '  ';
                $localIndent = str_repeat($replacer, $indentSize - strlen($statusIndent));

                $status = $val['status'];

                switch ($status) {
                    case 'updated':
                        $removedLine =
                            "{$localIndent}{$indents['removed']}{$val['key']}: {$iter($val['oldValue'], $depth + 1)}";
                        $addedLine =
                            "{$localIndent}{$indents['added']}{$val['key']}: {$iter($val['newValue'], $depth + 1)}";
                        return "{$removedLine}\n{$addedLine}";
                    case 'removed':
                    case 'added':
                        return "{$localIndent}{$indents[$status]}{$val['key']}: {$iter($val['value'], $depth + 1)}";
                    case 'parent':
                    case 'unchanged':
                        return "{$localIndent}{$statusIndent}{$val['key']}: {$iter($val['value'], $depth + 1)}";
                    default:
                        throw new RuntimeException("Unexpected node status: {$status}");
                };
            },
            array_keys($currentValue),
            $currentValue
        );

        $joinedLines = implode("\n", $lines);
        return "{\n{$joinedLines}\n{$bracketIndent}}";
    };

    return $iter($value, 1);
}
