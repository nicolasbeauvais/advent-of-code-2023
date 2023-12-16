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
            [1320, 520500], // Test Part 1, Real Part 1
            [145, 213097], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        return explode(',', trim(file_get_contents($this->input)));
    }

    private function hash(string $string): int
    {
        return array_reduce(
            str_split($string),
            fn ($hash, $char) => ($hash + ord($char)) * 17 % 256,
            0
        );
    }

    public function part1(): int
    {
        return array_reduce($this->parse(), fn ($total, $item) => $total + $this->hash($item), 0);
    }

    public function part2(): int
    {
        $boxes = array_fill(0, 255, []);

        foreach ($this->parse() as $item) {
            if ($item[-1] === '-') {
                $label = substr($item, 0, -1);

                unset($boxes[$this->hash($label)][$label]);
            } else {
                [$label, $focalLength] = explode('=', $item);

                $boxes[$this->hash($label)][$label] = $focalLength;
            }
        }

        $total = 0;

        foreach ($boxes as $index => $box) {
            $position = 1;

            foreach ($box as $focalLength) {
                $total += ($index + 1) * $position * $focalLength;
                $position++;
            }
        }

        return $total;
    }
}
