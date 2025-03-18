<?php

namespace Php\Project\Formatter;

function stringify(array $data, string $format): string
{
    $result = match ($format) {
        default => stylish($data, ' ', 4)
    };
    return $result;
}

function toString(mixed $value): string
{
    $encoded = json_encode($value);
    return trim($encoded !== false ? $encoded : '', '"');
}

function stylish(array $value, string $replacer = ' ', int $spacesCount = 1): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $indentSize = $depth * $spacesCount;

        $currentIndent = str_repeat($replacer, $indentSize);
        $bracketIndent = str_repeat($replacer, $indentSize - $spacesCount);

        $currentValue = $currentValue['children'] ?? $currentValue;

        $lines = array_map(
            function ($key, $val) use (&$iter, $depth, $replacer, $indentSize, $currentIndent) {
                if (is_array($val) && array_key_exists('status', $val)) {
                    $localIndent = str_repeat($replacer, $indentSize - strlen($val['status']));
                    return "{$localIndent}{$val['status']}{$val['key']}: {$iter($val['value'], $depth + 1)}";
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
