<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/10
 */
class Day10 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [23, 6778], // Test Part 1, Real Part 1
            [10, 433], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $map = [];
        $start = [0,0];

        foreach (file($this->input) as $y => $line) {
            foreach (str_split(trim($line)) as $x => $char) {
                if ($char === 'S') {
                    $start = [$x,$y];
                }

                $map["$x,$y"] = $char;
            }
        }

        return [$map, $start];
    }

    private function nextPipeTile(array $map, int $x, int $y, string $previous = null): string
    {
        $tile = $map["$x,$y"];

        $explore = match ($tile) {
            'S' => [
                "$x," . ($y - 1) => ['|', 'F', '7', 'S'],
                ($x + 1) . ",$y" => ['-', 'J', '7', 'S'],
                "$x," . ($y + 1) => ['|', 'L', 'J', 'S'],
                ($x - 1) . ",$y" => ['-', 'L', 'F', 'S'],
            ],
            'F' => [
                ($x + 1) . ",$y" => ['-', 'J', '7', 'S'],
                "$x," . ($y + 1) => ['|', 'L', 'J', 'S'],
            ],
            '7' => [
                "$x," . ($y + 1) => ['|', 'L', 'J', 'S'],
                ($x - 1) . ",$y" => ['-', 'L', 'F', 'S'],
            ],
            'J' => [
                "$x," . ($y - 1) => ['|', 'F', '7', 'S'],
                ($x - 1) . ",$y" => ['-', 'L', 'F', 'S'],
            ],
            'L' => [
                "$x," . ($y - 1) => ['|', 'F', '7', 'S'],
                ($x + 1) . ",$y" => ['-', 'J', '7', 'S'],
            ],
            '|' => [
                "$x," . ($y - 1) => ['|', 'F', '7', 'S'],
                "$x," . ($y + 1) => ['|', 'L', 'J', 'S'],
            ],
            '-' => [
                ($x + 1) . ",$y" => ['-', 'J', '7', 'S'],
                ($x - 1) . ",$y" => ['-', 'L', 'F', 'S'],
            ]
        };

        foreach ($explore as $coordinates => $available) {
            if ($coordinates === $previous) {
                continue;
            }

            if (!isset($map[$coordinates]) || !in_array($map[$coordinates], $available, true)) {
                continue;
            }

            return $coordinates;
        }

        throw new \Exception('No pipe found');
    }

    private function flood(array $cursor, array $xBoundaries, array $yBoundaries, array &$expandedPipes): void
    {
        [$cx, $cy] = $cursor;

        $coordinates = [
            [$cx, $cy - 1],
            [$cx - 1, $cy],
            [$cx, $cy + 1],
            [$cx + 1, $cy],
        ];

        foreach ($coordinates as [$x, $y]) {
            if ($x < $xBoundaries[0] || $x > $xBoundaries[1]) {
                continue;
            }

            if ($y < $yBoundaries[0] || $y > $yBoundaries[1]) {
                continue;
            }

            if (array_key_exists("$x,$y", $expandedPipes)) {
                continue;
            }

            $expandedPipes["$x,$y"] = 'O';

            $this->flood([$x, $y], $xBoundaries, $yBoundaries, $expandedPipes);
        }
    }

    public function part1(): int
    {
        [$map, $cursor] = $this->parse();

        $previous = null;
        $steps = 0;

        while (true) {
            [$x, $y] = $cursor;

            $next = $this->nextPipeTile($map, $x, $y, $previous);

            $previous = implode(',', $cursor);
            $cursor = explode(',', $next);
            $steps++;

            if ($map[$next] === 'S') {
                return ($steps + 1) / 2;
            }
        }
    }

    public function part2(): int
    {
        [$map, $cursor] = $this->parse();

        $pipes = [];
        $previous = $cursor;

        while (true) {
            [$cx, $cy] = $cursor;

            $next = $this->nextPipeTile($map, $cx, $cy, implode(',', $previous));

            [$nx, $ny] = explode(',', $next);

            $direction = match (($nx - $cx) . ',' . ($ny - $cy)) {
                '0,-1' => 'top',
                '1,0' => 'right',
                '0,1' => 'bottom',
                '-1,0' => 'left',
                '0,0' => 'start',
            };

            $ex = $nx * 2;
            $ey = $ny * 2;

            $pipes["$ex,$ey"] = 'x';
            $pipes[match ($direction) {
                'top' => "$ex," . ($ey + 1),
                'right' => ($ex - 1) . ",$ey",
                'bottom' => "$ex," . ($ey - 1),
                'left' => ($ex + 1) . ",$ey",
                'start' => "$ex,$ey",
            }] = 'x';

            $previous = $cursor;
            $cursor = explode(',', $next);

            if ($map[$next] === 'S') {
                break;
            }
        }

        $this->flood([0, 0], [0, 279 + 1], [0, 279 + 1], $pipes);

        // Count empty coordinate that are even
        $total = 0;
        foreach (range(0, 279) as $y) {
            foreach (range(0, 279) as $x) {
                if ($x % 2 === 0 && $y % 2 === 0 && !array_key_exists("$x,$y", $pipes)) {
                    ++$total;
                }
            }
        }

        return $total;
    }
}
