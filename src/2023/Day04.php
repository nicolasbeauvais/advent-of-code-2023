<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/4
 */
class Day04 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [13, 22193],
            [30, 5625994],
        ];
    }

    private function parse(): array
    {
        $deck = [];

        foreach (file($this->input) as $id => $line) {
            [$winning, $available] = explode('|', substr($line, strpos($line, ':') + 2, -1));

            $deck[$id] = count(array_filter(array_intersect(
                explode(' ', $winning),
                explode(' ', $available)
            )));
        }

        return $deck;
    }

    public function part1(): int
    {
        return array_reduce(
            $this->parse(),
            fn($total, $count) => $total + ($count ? 2 ** ($count - 1) : 0),
            0
        );
    }

    public function part2(): int
    {
        $copies = [];
        $deck = $this->parse();

        foreach ($deck as $id => $score) {
            $increment = ($copies[$id] ?? 0) + 1;

            for ($i = $id + 1; $i <= $id + $score; $i++) {
                isset($copies[$i]) ? $copies[$i] += $increment : $copies[$i] = $increment;
            }
        }

        return array_sum($copies) + count($deck);
    }
}
