<?php

namespace KirschbaumDevelopment\NovaNotes;

use Laravel\Nova\ResourceTool;

class CommentNotes extends ResourceTool
{
    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 25;

    /**
     * Create a new Noteer resource tool instance.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct($name = 'Notes')
    {
        $this->name = $name;

        parent::__construct();
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'comment-notes';
    }
}