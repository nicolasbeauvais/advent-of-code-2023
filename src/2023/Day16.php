<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/16
 */
class Day16 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [46, 7498], // Test Part 1, Real Part 1
            [51, 7846], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $map = [];

        foreach (file($this->input, FILE_IGNORE_NEW_LINES) as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                if ($char !== '.') {
                    $map["$x,$y"] = $char;
                }
            }
        }

        return [$map, [0, $x, $y, 0]];
    }

    private function energize(array $cursor, array $map, array $boundaries): int
    {
        $memo = [];
        $energized = [];
        $cursors = [$cursor];

        while (!empty($cursors)) {
            $cursor = array_shift($cursors);

            [$x, $y, $direction] = $cursor;

            match ($direction) {
                0 => $y--,
                1 => $x++,
                2 => $y++,
                3 => $x--,
            };

            if ($x < $boundaries[3] || $x > $boundaries[1] || $y < $boundaries[0] || $y > $boundaries[2]) {
                continue;
            }

            if (isset($memo["$x,$y,$direction"])) {
                continue;
            }

            $memo["$x,$y,$direction"] = '';
            $energized["$x,$y"] = '';
            $mirror = $map["$x,$y"] ?? '.';

            $directions = match ($direction) {
                0 => match ($mirror) {
                    '-' => [3, 1],
                    '\\' => [3],
                    '/' => [1],
                    '|', '.' => [0],
                },
                1 => match ($mirror) {
                    '|' => [0, 2],
                    '\\' => [2],
                    '/' => [0],
                    '-', '.' => [1],
                },
                2 => match ($mirror) {
                    '-' => [3, 1],
                    '\\' => [1],
                    '/' => [3],
                    '|', '.' => [2],
                },
                3 => match ($mirror) {
                    '|' => [0, 2],
                    '\\' => [0],
                    '/' => [2],
                    '-', '.' => [3],
                },
            };

            foreach ($directions as $newDirection) {
                $cursors[] = [$x, $y, $newDirection];
            }
        }

        return count($energized);
    }

    public function part1(): int
    {
        [$map, $boundaries] = $this->parse();

        return $this->energize([-1, 0, 1], $map, $boundaries);
    }

    public function part2(): int
    {
        [$map, $boundaries] = $this->parse();

        $max = array_reduce(range($boundaries[3], $boundaries[1]), fn ($max, $x) => max(
            $max,
            $this->energize([$x, $boundaries[0] - 1, 2], $map, $boundaries),
            $this->energize([$x, $boundaries[2] + 1, 0], $map, $boundaries)
        ), 0);

        return array_reduce(range($boundaries[0], $boundaries[2]), fn ($max, $y) => max(
            $max,
            $this->energize([$boundaries[3] - 1, $y, 1], $map, $boundaries),
            $this->energize([$boundaries[1] + 1, $y, 3], $map, $boundaries)
        ), $max);
    }
}
