<?php

namespace Bwp\ReviewApplication;

use Laravel\Nova\ResourceTool;

class ReviewApplication extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Review Application';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'review-application';
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
