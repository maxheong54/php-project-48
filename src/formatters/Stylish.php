<?php

namespace Php\Project\Formatters\Stylish;

function toString(mixed $value): string
{
    $encoded = json_encode($value);
    return trim($encoded !== false ? $encoded : '', '"');
}

function formatToStylish(array $value, string $replacer = ' ', int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = $depth * $spacesCount;

        $currentIndent = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);

        $currentValue = $currentValue['children'] ?? $currentValue;

        $indents = [
            'removed' => '- ',
            'added' => '+ ',
            'updated' => '+-'
        ];

        $lines = array_map(
            function ($key, $val) use (&$iter, $depth, $replacer, $indentSize, $currentIndent, $indents) {
                if (is_array($val) && array_key_exists('status', $val)) {
                    $statusIndent = $indents[$val['status']] ?? '  ';
                    $localIndent = str_repeat($replacer, $indentSize - strlen($statusIndent));

                    if ($val['status'] === 'updated') {
                        $removedLine = "{$localIndent}{$indents['removed']}{$val['key']}: " .
                            "{$iter($val['from'], $depth + 1)}";
                        $addedLine = "{$localIndent}{$indents['added']}{$val['key']}: " .
                            "{$iter($val['to'], $depth + 1)}";
                        return "{$removedLine}\n{$addedLine}";
                    }

                    return "{$localIndent}{$statusIndent}{$val['key']}: {$iter($val['value'], $depth + 1)}";
                }

                return "{$currentIndent}{$key}: {$iter($val, $depth + 1)}";
            },
            array_keys($currentValue),
            $currentValue
        );

        return "{\n" . implode("\n", $lines) . "\n{$bracketIndent}}";
    };

    return $iter($value, 1);
}
