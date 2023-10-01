<?php

namespace App\Formatter;

class RegexReplaceFormatter
{
    public function __construct(
        private readonly string $regex,
        private readonly string $replacement,
    ) {

    }

    public function __invoke(string $line): iterable
    {
        yield preg_replace($this->regex, $this->replacement, preg_quote($line));
    }
}