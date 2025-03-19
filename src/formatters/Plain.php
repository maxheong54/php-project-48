<?php

namespace Php\Project\Formatters\Plain;

function toString(mixed $value): string
{
    $encoded = json_encode($value) ?: '';
    return str_replace('"', "'", $encoded);
}

function formatToPlain(array $value): string
{
    $iter = function ($currentValue, $depth) use (&$iter) {

        $lines = array_map(
            function ($val) use (&$iter, $depth,) {
                $currentKey = empty($depth) ? $val['key'] : "{$depth}.{$val['key']}";
                if (array_key_exists('children', $val)) {
                    return $iter($val['children'], $currentKey);
                }

                switch ($val['status']) {
                    case 'added':
                        $value = is_array($val['value']) ? '[complex value]' : toString($val['value']);
                        return "Property '{$currentKey}' was added with value: {$value}";
                    case 'removed':
                        $value = is_array($val['value']) ? '[complex value]' : toString($val['value']);
                        return "Property '{$currentKey}' was removed";
                    case 'updated':
                        $from = is_array($val['from']) ? '[complex value]' : toString($val['from']);
                        $to = is_array($val['to']) ? '[complex value]' : toString($val['to']);
                        return "Property '{$currentKey}' was updated. From {$from} to {$to}";
                };
            },
            $currentValue
        );

        $removedEmptyLines = array_filter($lines, fn($line) => !empty($line));
        return implode("\n", $removedEmptyLines);
    };

    return $iter($value, '');
}
