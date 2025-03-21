<?php

namespace Differ\Formatters\Plain;

function toString(mixed $value): string
{
    $encoded = json_encode($value);
    $encoded = $encoded !== false ? $encoded : '';
    return str_replace('"', "'", $encoded);
}

function formatToPlain(array $value): string
{
    $iter = function ($currentValue, $depth) use (&$iter) {

        $lines = array_map(
            function ($val) use (&$iter, $depth,) {
                $currentKey = empty($depth) ? $val['key'] : "{$depth}.{$val['key']}";

                switch ($val['status']) {
                    case 'added':
                        $value = is_array($val['value']) ? '[complex value]' : toString($val['value']);
                        return "Property '{$currentKey}' was added with value: {$value}";
                    case 'removed':
                        $value = is_array($val['value']) ? '[complex value]' : toString($val['value']);
                        return "Property '{$currentKey}' was removed";
                    case 'updated':
                        $from = is_array($val['value']['from']) ? '[complex value]' : toString($val['value']['from']);
                        $to = is_array($val['value']['to']) ? '[complex value]' : toString($val['value']['to']);
                        return "Property '{$currentKey}' was updated. From {$from} to {$to}";
                    case 'compare':
                        return $iter($val['value'], $currentKey);
                };
            },
            $currentValue
        );

        $removedEmptyLines = array_filter($lines, fn($line) => !empty($line));
        return implode("\n", $removedEmptyLines);
    };

    return $iter($value, '');
}
