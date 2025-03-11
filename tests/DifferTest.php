<?php

namespace Php\Project\Tests;

use PHPUnit\Framework\TestCase;
use function Php\Project\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $result = genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json');
        $this->assertStringEqualsFile('tests/fixtures/result.txt', $result);
    }
}