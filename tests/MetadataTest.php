<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

class MetadataTest extends TestCase
{
    public function test_composer_targets_the_supported_stable_platform(): void
    {
        $composer = json_decode((string) file_get_contents(__DIR__ . '/../composer.json'), true, flags: JSON_THROW_ON_ERROR);

        $this->assertSame('^8.4', $composer['require']['php']);
        $this->assertSame('^13.0', $composer['require']['laravel/framework']);
        $this->assertSame('^5.0', $composer['require']['laravel/nova']);
        $this->assertSame('stable', $composer['minimum-stability']);
        $this->assertTrue($composer['prefer-stable']);
    }
}
