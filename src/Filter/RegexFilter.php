<?php

namespace App\Filter;

class RegexFilter
{

    public function __construct(
        private readonly string $regex
    ) {

    }

    public function __invoke(string $line): bool
    {
        return preg_match($this->regex, $line);
    }
}