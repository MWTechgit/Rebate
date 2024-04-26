<?php

namespace Bwp\ClaimActions;

use Laravel\Nova\ResourceTool;

class ClaimActions extends ResourceTool
{
    public function __construct()
    {
        parent::__construct();
        $this->withMeta(['cameFromLens'=>session('last-claim-lens')]);
    }
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Claim Actions';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'claim-actions';
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
