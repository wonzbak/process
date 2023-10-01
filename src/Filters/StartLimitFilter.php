<?php

namespace App\Filters;

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
        //dump("filter: $line");
        $this->current++;
        //echo "current: " . $this->current . "\n";
        if (isset($this->start) && $this->current < $this->start) {
            return false;
        }

        if (isset($this->limit) && $this->current > $this->limit) {
            return false;
        }

        return true;
    }
}