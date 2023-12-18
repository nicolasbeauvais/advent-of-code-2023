<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/18
 */
class Day18 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [62, 26857], // Test Part 1, Real Part 1
            [952408144115, 129373230496292], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $plan = [];

        foreach (file($this->input, FILE_IGNORE_NEW_LINES) as $line) {
            $plan[] = explode(' ', $line);
        }

        return $plan;
    }

    public function shoelace(array $coordinates): int
    {
        $area = 0;
        $previous = $coordinates[count($coordinates) - 1];

        foreach ($coordinates as $current) {
            $area += $previous[0] * $current[1] - $current[0] * $previous[1];
            $previous = $current;
        }

        return 0.5 * abs($area);
    }

    public function part1(): int
    {
        $area = $x = $y = 0;
        $coordinates = [];

        foreach ($this->parse() as $line) {
            [$direction, $length] = $line;

            match ($direction) {
                'U' => $y -= $length,
                'D' => $y += $length,
                'L' => $x -= $length,
                'R' => $x += $length,
            };

            $coordinates[] = [$x, $y];
            $area += $length;
        }

        return $this->shoelace($coordinates) + 1 - ($area / 2) + $area;
    }

    public function part2(): int
    {
        $area = $x = $y = 0;
        $coordinates = [];

        foreach ($this->parse() as $line) {
            [$direction, $length, $hexa] = $line;

            $length = hexdec(substr($hexa, 2, -2));
            match ((int)$hexa[-2]) {
                0 => $x += $length,
                1 => $y += $length,
                2 => $x -= $length,
                3 => $y -= $length,
            };

            $coordinates[] = [$x, $y];
            $area += $length;
        }

        return $this->shoelace($coordinates) + 1 - ($area / 2) + $area;
    }
}
