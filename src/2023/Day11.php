<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/11
 */
class Day11 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [374, 9591768], // Test Part 1, Real Part 1
            [82000210, 746962097860], // Test Part 2, Real Part 2
        ];
    }

    private function parse(int $expansionSize): int
    {
        $map = [];
        $galaxies = [];
        $expand = [
            'x' => [],
            'y' => [],
        ];

        foreach (file($this->input, FILE_IGNORE_NEW_LINES) as $y => $line) {
            $line = str_split($line);

            $map[] = $line;

            if (count(array_unique($line)) === 1 && $line[0] === '.') {
                $expand['y'][] = $y;
            }

            foreach ($line as $x => $char) {
                if ($char === '#') {
                    $galaxies[] = [$x, $y];
                }
            }
        }

        foreach (array_map(fn() => array_reverse(func_get_args()), ...$map) as $x => $line) {
            if (count(array_unique($line)) === 1 && $line[0] === '.') {
                $expand['x'][] = $x;
            }
        }

        foreach ($galaxies as &$galaxy) {
            [$x, $y] = $galaxy;

            foreach ($expand['x'] as $expandX) {
                if ($galaxy[0] > $expandX) {
                    $x += $expansionSize - 1;
                }
            }

            foreach ($expand['y'] as $expandY) {
                if ($galaxy[1] > $expandY) {
                    $y += $expansionSize - 1;
                }
            }

            $galaxy = [$x, $y];
        } unset($galaxy);

        $total = 0;

        foreach ($galaxies as $id1 => $galaxy1) {
            [$x1, $y1] = $galaxy1;

            foreach ($galaxies as $id2 => $galaxy2) {
                if ($id1 === $id2) {
                    continue;
                }

                [$x2, $y2] = $galaxy2;

                $total += abs($x1 - $x2) + abs($y1 - $y2);
            }

            unset($galaxies[$id1]);
        }

        return $total;
    }

    public function part1(): int
    {
        return $this->parse(2);
    }

    public function part2(): int
    {
        return $this->parse(1_000_000);
    }
}
