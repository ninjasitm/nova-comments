<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\User;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;
use KirschbaumDevelopment\NovaComments\Nova\Comment;

class NovaCommentTest extends TestCase
{
    public function test_it_is_a_nova_resource_with_required_string_comment_validation(): void
    {
        config()->set('auth.providers.users.model', TestUser::class);
        Nova::resources([TestUserResource::class]);

        $resource = new Comment();

        $this->assertInstanceOf(Resource::class, $resource);

        $comment = collect($resource->fields(Request::create('/')))
            ->first(fn ($field) => $field instanceof Textarea && $field->attribute === 'comment');

        $this->assertContains('required', $comment->rules);
        $this->assertContains('string', $comment->rules);
    }

    public function test_it_uses_the_registered_auth_provider_resource_unless_overridden(): void
    {
        config()->set('auth.providers.users.model', TestUser::class);
        config()->set('nova-comments.commenter.nova-resource', null);
        Nova::resources([TestUserResource::class, OverrideUserResource::class]);

        $default = collect((new Comment())->fields(Request::create('/')))
            ->first(fn ($field) => $field instanceof BelongsTo);

        $this->assertSame(TestUserResource::class, $default->resourceClass);

        config()->set('nova-comments.commenter.nova-resource', OverrideUserResource::class);

        $override = collect((new Comment())->fields(Request::create('/')))
            ->first(fn ($field) => $field instanceof BelongsTo);

        $this->assertSame(OverrideUserResource::class, $override->resourceClass);
    }
}

class TestUser extends User
{
}

class TestUserResource extends Resource
{
    public static $model = TestUser::class;

    public function fields(Request $request): array
    {
        return [ID::make()];
    }
}

class OverrideUserResource extends TestUserResource
{
}
