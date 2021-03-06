<?php

namespace KirschbaumDevelopment\NovaComments;

use Laravel\Nova\ResourceTool;

class Commenter extends ResourceTool
{
    /**
     * The number of resources to show per page via relationships.
     *
     * @var int
     */
    public static $perPageViaRelationship = 25;

    /**
     * Create a new Commenter resource tool instance.
     *
     * @param string $name
     *
     * @return void
     */
    public function __construct($name = 'Comments')
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
        return 'commenter';
    }
}