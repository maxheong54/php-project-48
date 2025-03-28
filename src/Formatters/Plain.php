<?php

namespace Differ\Formatters\Plain;

use RuntimeException;

function toString(mixed $value): string
{
    if (is_array($value)) {
        return '[complex value]';
    }

    $encoded = json_encode($value);
    $result = $encoded !== false ? $encoded : '';
    return str_replace('"', "'", $result);
}

function formatToPlain(array $value): string
{
    $iter = function ($currentValue, $depth) use (&$iter) {

        $lines = array_map(
            function ($val) use (&$iter, $depth,) {
                $currentKey = $depth === '' ? $val['key'] : "{$depth}.{$val['key']}";

                switch ($val['status']) {
                    case 'added':
                        $value = toString($val['value']);
                        return "Property '{$currentKey}' was added with value: {$value}";
                    case 'removed':
                        $value = toString($val['value']);
                        return "Property '{$currentKey}' was removed";
                    case 'updated':
                        $from = toString($val['oldValue']);
                        $to = toString($val['newValue']);
                        return "Property '{$currentKey}' was updated. From {$from} to {$to}";
                    case 'parent':
                        return $iter($val['value'], $currentKey);
                    case 'unchanged':
                        break;
                    default:
                        throw new RuntimeException("Unexpected node status: {$val['status']}");
                };
            },
            $currentValue
        );

        $removedEmptyLines = array_filter($lines, fn($line) => $line !== null);
        return implode("\n", $removedEmptyLines);
    };

    return $iter($value, '');
}
