<?php

namespace Differ\Formatters\Json;

function formatToJson(array $data): string
{
    $result = json_encode($data, JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR);
    return  $result;
}
