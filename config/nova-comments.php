<?php

return [
    // Supports approval. Enable to support approval functionality on backend
    'supports-approval' => false,

    // Custom class to use for package
    'comment-class' => \KirschbaumDevelopment\NovaComments\Models\Comment::class,
    'note-class' => \KirschbaumDevelopment\NovaComments\Models\Note::class,

    // Sets whether or not Comments should show up in sidebar navigation.
    'available-for-navigation' => true,

    // The resource to use as a commenter. Typically the User resource.
    'commenter' => [
        'nova-resource' => \App\Nova\User::class,
    ],

    // Configs for the comments panel
    'comments-panel' => [
        'name' => 'Comments',
    ],

    // Maximum length of comment in comments panel
    'limit' => 100,

    'date-format' => 'MMM DD, YYYY',
];