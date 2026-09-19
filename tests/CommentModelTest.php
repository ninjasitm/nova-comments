<?php

namespace KirschbaumDevelopment\NovaComments\Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User;
use KirschbaumDevelopment\NovaComments\Models\Comment;

class CommentModelTest extends TestCase
{
    public function test_authenticated_comment_creation_assigns_commenter_and_relations_resolve(): void
    {
        config()->set('auth.providers.users.model', CommenterModel::class);
        $user = CommenterModel::query()->create(['name' => 'Ada', 'email' => 'ada@example.test', 'password' => 'secret']);
        $post = CommentableModel::query()->create(['title' => 'Post']);
        $this->actingAs($user);

        $comment = $post->comments()->create(['comment' => 'Hello']);

        $this->assertSame($user->id, $comment->commenter_id);
        $this->assertTrue($comment->commentable->is($post));
        $this->assertTrue($comment->commenter->is($user));
    }

    public function test_guest_comment_creation_leaves_commenter_null(): void
    {
        config()->set('auth.providers.users.model', CommenterModel::class);
        $post = CommentableModel::query()->create(['title' => 'Post']);
        $comment = $post->comments()->create(['comment' => 'Hello']);

        $this->assertNull($comment->commenter_id);
        $this->assertNull($comment->commenter);
    }
}

class CommentableModel extends Model
{
    use \KirschbaumDevelopment\NovaComments\Commentable;
    protected $table = 'test_posts';
    protected $guarded = [];
}

class CommenterModel extends User
{
    protected $table = 'test_users';
    protected $guarded = [];
}
