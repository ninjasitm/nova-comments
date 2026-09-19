<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use KirschbaumDevelopment\NovaComments\Commentable;
use KirschbaumDevelopment\NovaComments\Models\Comment as CommentModel;
use KirschbaumDevelopment\NovaComments\Nova\Comment;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Nova;
use Laravel\Nova\Resource;

class NovaRequestAuthorizationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth.providers.users.model', IntegrationUser::class);
        config()->set('nova-comments.commenter.nova-resource', IntegrationUserResource::class);

        Nova::auth(fn () => true);
        Nova::resources([IntegrationUserResource::class, IntegrationPostResource::class]);
        Nova::withoutActionEvents();
    }

    public function test_relationship_creation_rejects_missing_and_non_string_comments(): void
    {
        $user = $this->user();
        $post = $this->postModel();

        $this->actingAs($user)
            ->postJson('/nova-api/comments', $this->relationshipPayload($post))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('comment');

        $this->actingAs($user)
            ->postJson('/nova-api/comments', $this->relationshipPayload($post, ['comment' => ['invalid']]))
            ->assertUnprocessable()
            ->assertJsonValidationErrors('comment');

        $this->assertDatabaseCount('nova_comments', 0);
    }

    public function test_relationship_creation_persists_a_valid_comment(): void
    {
        $user = $this->user();
        $post = $this->postModel();

        $this->actingAs($user)
            ->postJson('/nova-api/comments', $this->relationshipPayload($post, ['comment' => 'A valid comment']))
            ->assertCreated();

        $comment = $post->comments()->sole();

        $this->assertSame('A valid comment', $comment->comment);
        $this->assertSame($user->getKey(), $comment->commenter_id);
        $this->assertTrue($comment->commentable->is($post));
    }

    public function test_comment_policy_denies_create_through_the_nova_endpoint(): void
    {
        Gate::policy(CommentModel::class, DenyingCommentPolicy::class);

        $this->actingAs($this->user())
            ->postJson('/nova-api/comments', ['comment' => 'Denied'])
            ->assertForbidden();

        $this->assertDatabaseCount('nova_comments', 0);
    }

    public function test_comment_policy_denies_view_through_the_nova_endpoint(): void
    {
        $comment = $this->postModel()->comments()->create(['comment' => 'Hidden']);
        Gate::policy(CommentModel::class, DenyingCommentPolicy::class);

        $this->actingAs($this->user())
            ->getJson('/nova-api/comments/'.$comment->getKey())
            ->assertForbidden();
    }

    public function test_parent_policy_denies_adding_a_comment_through_the_relationship_endpoint(): void
    {
        Gate::policy(IntegrationPost::class, DenyingPostPolicy::class);
        $post = $this->postModel();

        $this->actingAs($this->user())
            ->postJson('/nova-api/comments', $this->relationshipPayload($post, ['comment' => 'Denied']))
            ->assertUnauthorized();

        $this->assertDatabaseCount('nova_comments', 0);
    }

    private function user(): IntegrationUser
    {
        return IntegrationUser::query()->create([
            'name' => 'Nova User',
            'email' => 'nova@example.test',
            'password' => 'secret',
        ]);
    }

    private function postModel(): IntegrationPost
    {
        return IntegrationPost::query()->create(['title' => 'Post']);
    }

    private function relationshipPayload(IntegrationPost $post, array $attributes = []): array
    {
        return array_merge([
            'viaResource' => IntegrationPostResource::uriKey(),
            'viaResourceId' => $post->getKey(),
            'viaRelationship' => 'comments',
            'relationshipType' => 'hasMany',
        ], $attributes);
    }
}

class IntegrationUser extends User
{
    protected $table = 'test_users';

    protected $guarded = [];
}

class IntegrationPost extends Model
{
    use Commentable;

    protected $table = 'test_posts';

    protected $guarded = [];
}

class IntegrationUserResource extends Resource
{
    public static $model = IntegrationUser::class;

    public function fields(Request $request): array
    {
        return [ID::make()];
    }
}

class IntegrationPostResource extends Resource
{
    public static $model = IntegrationPost::class;

    public function fields(Request $request): array
    {
        return [
            ID::make(),
            HasMany::make('Comments', 'comments', Comment::class),
        ];
    }
}

class DenyingCommentPolicy
{
    public function create(IntegrationUser $user): bool
    {
        return false;
    }

    public function view(IntegrationUser $user, CommentModel $comment): bool
    {
        return false;
    }
}

class DenyingPostPolicy
{
    public function addComment(IntegrationUser $user, IntegrationPost $post, CommentModel $comment): bool
    {
        return false;
    }
}
