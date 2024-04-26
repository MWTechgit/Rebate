<?php

namespace App;

use App\Cacheable;

/**
 * This is just a value object to hold denial information
 * for code clarity and to avoid passing string arguments
 * to other object constructors.
 */
class Denial
{
    use Cacheable;

    protected $reason;

    protected $moreInfo;

    public function __construct(string $reason = '', string $moreInfo = '')
    {
        $this->reason = $reason;
        $this->moreInfo = $moreInfo;
    }

    public function reason(): string
    {
        return $this->reason;
    }

    public function moreInfo(): string
    {
        return $this->moreInfo;
    }
}
