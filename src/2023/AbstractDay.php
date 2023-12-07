<?php

namespace AOC\_2023;

abstract class AbstractDay
{
    public const TODO = 0;

    protected string $input;

    public function __construct(string $input)
    {
        $this->input = $input;
    }

    abstract public static function answers(): array;

    abstract public function part1(): int;

    abstract public function part2(): int;
}
