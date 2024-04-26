<?php

namespace App\Scopes;

use Illuminate\Support\Str;

trait HasStatus
{
    /**
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  mixed $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHasStatus($query, $status)
    {
        $hasMultipleStatuses = false !== Str::contains($status, '|');

        if (false === $hasMultipleStatuses) {
            $this->assertValidStatus($status);
        }

        return $query->when($hasMultipleStatuses, function($query) use ($status) {
            $statuses = explode('|', $status);
            $first = array_shift($statuses);
            $query->where( $this->getTable() . '.status', $first);
            foreach ($statuses as $value) {
                $this->assertValidStatus($value);
                $query->orWhere( $this->getTable() . '.status', $value);
            }
            return $query;
        }, function($query) use ($status) {
            // Default
            return $query->where( $this->getTable() . '.status', $status);
        });
    }

    protected function assertValidStatus(string $status): void
    {
        $isValid = in_array($status, $this->getValidStatuses());
        if (false === $isValid) {
            throw new \InvalidArgumentException("Status ($status) is not a valid status column value.");
        }
    }

    abstract protected function getValidStatuses();
}