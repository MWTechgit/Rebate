<?php

namespace Bwp\ApplicationComments;

use Laravel\Nova\ResourceTool;

class ApplicationComments extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Application Comments';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'application-comments';
    }
}
