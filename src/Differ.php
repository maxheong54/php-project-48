<?php

namespace Php\Project\Differ;

function parseJson(string $path): array
{
    $jsonValue = file_get_contents($path);
    return json_decode($jsonValue, true);
}
