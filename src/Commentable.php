<?php

namespace KirschbaumDevelopment\NovaComments;

trait Commentable
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function comments()
    {
        return $this->morphMany(config('nova-comments.comments-model'), 'commentable');
    }
    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function flaggedComments()
    {
        return $this->comments()->whereHas('notesFlaggedAsInappropriate');
    }
}