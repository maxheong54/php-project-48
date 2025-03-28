<?php

namespace Differ\Tests;

use PHPUnit\Framework\TestCase;

use function Differ\Differ\genDiff;

class DifferTest extends TestCase
{
    private string $file1Json;
    private string $file2Json;
    private string $file1Yaml;
    private string $file2Yaml;
    public function setUp(): void
    {
        $this->file1Json = $this->getFixtureFullPath('file1.json');
        $this->file2Json = $this->getFixtureFullPath('file2.json');
        $this->file1Yaml = $this->getFixtureFullPath('file1.yaml');
        $this->file2Yaml = $this->getFixtureFullPath('file2.yaml');
    }

    public function getFixtureFullPath($fixtureName)
    {
        $parts = [__DIR__, 'fixtures', $fixtureName];
        return realpath(implode('/', $parts));
    }

    public function testGenDiffStylish(): void
    {
        $expected = $this->getFixtureFullPath('diff.stylish');

        $result1 = genDiff($this->file1Json, $this->file2Json);
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff($this->file1Yaml, $this->file2Yaml);
        $this->assertStringEqualsFile($expected, $result2);

        $result3 = genDiff($this->file1Yaml, $this->file2Json);
        $this->assertStringEqualsFile($expected, $result3);

        $result4 = genDiff($this->file2Json, $this->file2Json);
        $this->assertStringNotEqualsFile($expected, $result4);
    }

    public function testGenDiffPlain(): void
    {
        $expected = $this->getFixtureFullPath('diff.plain');

        $result1 = genDiff($this->file1Json, $this->file2Json, 'plain');
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff($this->file1Yaml, $this->file2Yaml, 'plain');
        $this->assertStringEqualsFile($expected, $result2);

        $result3 = genDiff($this->file1Yaml, $this->file2Json, 'plain');
        $this->assertStringEqualsFile($expected, $result3);

        $result4 = genDiff($this->file2Json, $this->file2Json, 'plain');
        $this->assertStringNotEqualsFile($expected, $result4);
    }

    public function testGenDiffJson(): void
    {
        $expected = $this->getFixtureFullPath('diff.json');

        $result1 = genDiff($this->file1Json, $this->file2Json, 'json');
        $this->assertStringEqualsFile($expected, $result1);

        $result2 = genDiff($this->file1Yaml, $this->file2Yaml, 'json');
        $this->assertStringEqualsFile($expected, $result2);

        $result3 = genDiff($this->file1Yaml, $this->file2Json, 'json');
        $this->assertStringEqualsFile($expected, $result3);

        $result4 = genDiff($this->file2Json, $this->file2Json, 'json');
        $this->assertStringNotEqualsFile($expected, $result4);
    }
}
