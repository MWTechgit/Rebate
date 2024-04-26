<?php

namespace Bwp\ClaimComments;

use Laravel\Nova\ResourceTool;

class ClaimComments extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Claim Comments';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'claim-comments';
    }
}
