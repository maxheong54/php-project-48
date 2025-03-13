<?php

namespace Php\Project\Tests;

use PHPUnit\Framework\TestCase;
use function Php\Project\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expected = 'tests/fixtures/result.txt';

        $result1 = genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json');
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff('tests/fixtures/file2.json', 'tests/fixtures/file1.json');
        $this->assertStringNotEqualsFile($expected, $result2);

        $result3 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml');
        $this->assertStringEqualsFile($expected, $result3);

        $result4 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml');
        $this->assertStringEqualsFile($expected, $result4);

        $result5 = genDiff('tests/fixtures/file1.yaml', 'tests/fixtures/file2.yaml');
        $this->assertStringEqualsFile($expected, $result5);

        $result6 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yaml');
        $this->assertStringEqualsFile($expected, $result6);

        $result7 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.json');
        $this->assertStringEqualsFile($expected, $result7);
    }
}