<?php

namespace Differ\Formatters\Json;

function formatToJson(array $data): string
{
    $result = json_encode($data, JSON_PRETTY_PRINT);
    return  $result !== false ? $result : '';
}
