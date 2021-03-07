<?php

namespace KirschbaumDevelopment\NovaComments\Contracts;

use Illuminate\Database\Eloquent\Relations\HasMany;

interface Comment
{
    /**
     * Notes relation
     *
     * @return HasMany
     */
    public function notes();
}