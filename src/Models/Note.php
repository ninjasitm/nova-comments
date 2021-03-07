<?php

namespace KirschbaumDevelopment\NovaComments\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    // Comment flags
    const FLAG_DEFAULT = 'note';
    const FLAG_INAPPROPRIATE = 'inappropriate';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nova_comment_notes';

    protected $fillable = ['user_id', 'comment_id', 'content', 'type'];

    /**
     * The "booting" method of the model.
     */
    public static function boot()
    {
        parent::boot();

        static::creating(
            function ($note) {
                if (auth()->check()) {
                    $note->user_id = $note->user_id ?? auth()->id();
                }
            }
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(config('auth.providers.users.model'), 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comment()
    {
        return $this->hasOne(config('nova-comments.comments-model'), 'comment_id');
    }

    /**
     * Get Types
     *
     * @return array
     */
    public function getTypes(): array
    {
        return [
            static::FLAG_DEFAULT => Str::title(static::FLAG_DEFAULT),
            static::FLAG_INAPPROPRIATE => Str::title(static::FLAG_INAPPROPRIATE),
        ];
    }
}