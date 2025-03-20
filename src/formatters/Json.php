<?php

namespace Php\Project\Formatters\Json;

function formatToJson(array $data): string
{
    return json_encode($data, JSON_PRETTY_PRINT) ?: '';
}
