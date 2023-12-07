<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/15
 */
class Day15 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [0, 0], // Test Part 1, Real Part 1
            [0, 0], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        return file($this->input);
    }

    public function part1(): int
    {
        $this->parse();

        return self::TODO;
    }

    public function part2(): int
    {
        $this->parse();

        return self::TODO;
    }
}
