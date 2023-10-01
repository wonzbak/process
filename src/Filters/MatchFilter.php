<?php

namespace App\Filters;

class MatchFilter
{

    public function __construct(
        private readonly string $match,
        private readonly bool $strict = false,
    ) {
    }

    public function __invoke(string $line): bool
    {
        return match ($this->strict) {
            true =>  $line === $this->match,
            false => str_contains($line, $this->match)
        };
    }
}