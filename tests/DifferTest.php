<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    public function testGenDiff(): void
    {
        $expectedStylish = 'tests/fixtures/expectedStylish.txt';

        $result1 = genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json');
        $this->assertStringEqualsFile($expectedStylish, $result1);

        $result2 = genDiff('tests/fixtures/file2.json', 'tests/fixtures/file1.json');
        $this->assertStringNotEqualsFile($expectedStylish, $result2);

        $result3 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml');
        $this->assertStringEqualsFile($expectedStylish, $result3);

        $result4 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yml');
        $this->assertStringEqualsFile($expectedStylish, $result4);

        $result5 = genDiff('tests/fixtures/file1.yaml', 'tests/fixtures/file2.yaml');
        $this->assertStringEqualsFile($expectedStylish, $result5);

        $result6 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yaml');
        $this->assertStringEqualsFile($expectedStylish, $result6);

        $result7 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.json');
        $this->assertStringEqualsFile($expectedStylish, $result7);

        $expectedPlain = 'tests/fixtures/expectedPlain.txt';

        $result8 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.json', 'plain');
        $this->assertStringEqualsFile($expectedPlain, $result8);

        $result9 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yaml', 'plain');
        $this->assertStringEqualsFile($expectedPlain, $result9);

        $result10 = genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json', 'plain');
        $this->assertStringEqualsFile($expectedPlain, $result10);

        $expectedJson = 'tests/fixtures/expectedJson.txt';

        $result11 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.json', 'json');
        $this->assertStringEqualsFile($expectedJson, $result11);

        $result12 = genDiff('tests/fixtures/file1.yml', 'tests/fixtures/file2.yaml', 'json');
        $this->assertStringEqualsFile($expectedJson, $result12);

        $result13 = genDiff('tests/fixtures/file1.json', 'tests/fixtures/file2.json', 'json');
        $this->assertStringEqualsFile($expectedJson, $result13);

        $result14 = genDiff('tests/fixtures/file11.json', 'tests/fixtures/file22.json', 'plain');
        $this->assertStringEqualsFile('tests/fixtures/diff.plain', $result14);

        $result15 = genDiff('tests/fixtures/file11.yaml', 'tests/fixtures/file22.yaml', 'plain');
        $this->assertStringEqualsFile('tests/fixtures/diff.plain', $result15);

        $result16 = genDiff('tests/fixtures/file22.json', 'tests/fixtures/file11.yaml', 'plain');
        $this->assertStringNotEqualsFile('tests/fixtures/diff.plain', $result16);

        $result17 = genDiff('tests/fixtures/file11.json', 'tests/fixtures/file22.yaml');
        $this->assertStringEqualsFile('tests/fixtures/diff.stylish', $result17);

        $result18 = genDiff('tests/fixtures/file11.yaml', 'tests/fixtures/file22.json');
        $this->assertStringEqualsFile('tests/fixtures/diff.stylish', $result18);
    }
}