<?php

namespace App\Support;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class TableSort
{
    /**
     * @param  array<string, string|Closure(Builder, string): void>  $allowedSorts
     * @return array{0: string, 1: string}
     */
    public static function apply(
        Builder $query,
        ?string $sort,
        ?string $direction,
        array $allowedSorts,
        string $defaultSort,
        string $defaultDirection = 'desc',
    ): array {
        $resolvedSort = array_key_exists((string) $sort, $allowedSorts) ? (string) $sort : $defaultSort;
        $resolvedDirection = in_array($direction, ['asc', 'desc'], true) ? $direction : $defaultDirection;

        $sortDefinition = $allowedSorts[$resolvedSort];

        if ($sortDefinition instanceof Closure) {
            $sortDefinition($query, $resolvedDirection);
        } else {
            $query->orderBy($sortDefinition, $resolvedDirection);
        }

        return [$resolvedSort, $resolvedDirection];
    }
}
