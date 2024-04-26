<?php

namespace Bwp\ApplicationActions;

use Laravel\Nova\ResourceTool;

class ApplicationActions extends ResourceTool
{
    public function __construct()
    {
        parent::__construct();
        $this->withMeta(['cameFromLens'=>session('last-application-lens')]);
    }

    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Application Actions';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'application-actions';
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
