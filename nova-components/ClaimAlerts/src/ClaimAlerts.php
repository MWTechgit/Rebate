<?php

namespace Bwp\ClaimAlerts;

use Laravel\Nova\ResourceTool;

class ClaimAlerts extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Claim Alerts';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'claim-alerts';
    }
}
