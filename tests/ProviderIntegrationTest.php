<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

use Illuminate\Http\Request;
use KirschbaumDevelopment\NovaComments\Nova\Comment;
use Laravel\Nova\Events\ServingNova;
use Laravel\Nova\Nova;

class ProviderIntegrationTest extends TestCase
{
    public function test_provider_registers_the_comment_resource_with_nova(): void
    {
        $this->assertContains(Comment::class, Nova::resourceCollection()->all());
    }

    public function test_serving_nova_registers_the_package_script_and_stylesheet(): void
    {
        ServingNova::dispatch($this->app, Request::create('/nova'));

        $script = collect(Nova::allScripts())->first(fn ($asset) => $asset->name() === 'commentable');
        $style = collect(Nova::allStyles())->first(fn ($asset) => $asset->name() === 'commentable');

        $this->assertNotNull($script);
        $this->assertNotNull($style);
        $this->assertSame(realpath(__DIR__.'/../dist/js/tool.js'), realpath($script->path()));
        $this->assertSame(realpath(__DIR__.'/../dist/css/tool.css'), realpath($style->path()));
    }
}
