<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

use KirschbaumDevelopment\NovaComments\CommentsPanel;
use Laravel\Nova\Panel;

class CompatibilityTest extends TestCase
{
    public function test_comments_panel_uses_the_public_panel_constructor_api(): void
    {
        $source = file_get_contents(__DIR__ . '/../src/CommentsPanel.php');

        $this->assertStringNotContainsString('$this->prepareFields(', $source);
        $this->assertInstanceOf(Panel::class, new CommentsPanel());
    }

    public function test_historical_migration_shape_is_unchanged(): void
    {
        $migration = file_get_contents(__DIR__ . '/../migrations/2019_04_09_211908_create_comments_table.php');

        $this->assertStringContainsString("\$table->increments('id');", $migration);
        $this->assertStringContainsString("\$table->morphs('commentable');", $migration);
        $this->assertStringContainsString("\$table->unsignedInteger('commenter_id')->nullable();", $migration);
        $this->assertStringContainsString("\$table->text('comment');", $migration);
    }

    public function test_usage_examples_import_the_component_they_instantiate(): void
    {
        $readme = file_get_contents(__DIR__ . '/../README.md');

        [$commenter, $panel] = explode('### Comments Panel', $readme, 2);
        $this->assertStringContainsString('use KirschbaumDevelopment\\NovaComments\\Commenter;', $commenter);
        $this->assertStringContainsString('new Commenter(),', $commenter);
        $this->assertStringContainsString('use KirschbaumDevelopment\\NovaComments\\CommentsPanel;', $panel);
        $this->assertStringContainsString('new CommentsPanel(),', $panel);
    }

    public function test_production_bundle_has_no_external_license_companion_reference(): void
    {
        $bundle = file_get_contents(__DIR__ . '/../dist/js/tool.js');

        $this->assertStringNotContainsString('.LICENSE.txt', $bundle);
        $this->assertFileDoesNotExist(__DIR__ . '/../dist/js/tool.js.LICENSE.txt');
    }
}
