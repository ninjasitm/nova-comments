<?php

return [
    // Sets whether or not Comments should show up in sidebar navigation.
    'available-for-navigation' => true,

    // Optionally override the Nova resource used for the configured auth provider model.
    'commenter' => [
        'nova-resource' => null,
    ],

    // Configs for the comments panel
    'comments-panel' => [
        'name' => 'Comments',
    ],

    // Maximum length of comment in comments panel
    'limit' => 100,

    'date-format' => 'MMM DD, YYYY',
];
