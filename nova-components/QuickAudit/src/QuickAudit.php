<?php

namespace Bwp\QuickAudit;

use Laravel\Nova\ResourceTool;

class QuickAudit extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Quick Audit';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'quick-audit';
    }

    # Nova BUG
    # https://github.com/laravel/nova-issues/issues/1399
    public function jsonSerialize()
    {
        return array_merge([
            'component' => 'panel',
            'name' => $this->name,
            'showToolbar' => $this->showToolbar,
        ], $this->meta());
    }
}
