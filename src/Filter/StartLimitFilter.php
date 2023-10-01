<?php

namespace App\Filter;

class StartLimitFilter
{
    private int $current = 0;

    public function __construct(
        private readonly ?int $start,
        private readonly ?int $limit
    ) {

    }

    public function __invoke(string $line): bool
    {
        $this->current++;

        if (isset($this->start) && $this->current < $this->start) {
            return false;
        }

        if (isset($this->limit) && $this->current > $this->limit) {
            return false;
        }

        return true;
    }
}