<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/14
 */
class Day14 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [136, 109596], // Test Part 1, Real Part 1
            [64, 96105], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $balls = $rocks = [];

        $input = file($this->input, FILE_IGNORE_NEW_LINES);

        foreach ($input as $y => $line) {
            foreach (str_split($line) as $x => $char) {
                if ($char === 'O') {
                    $balls[] = [$x, $y];
                }

                if ($char === '#') {
                    $rocks["$x,$y"] = '';
                }
            }
        }

        return [$balls, $rocks, [0, 0, count($input) - 1, strlen($input[0]) - 1]];
    }

    public function move(array $balls, array $rocks, array $boundaries, int $direction = 0): array
    {
        $positions = [];

        $balls = in_array($direction, [2, 3], true) ? array_reverse($balls) : $balls;

        foreach ($balls as [$x, $y]) {
            if ($direction === 0) {
                do {--$y; } while ($y >= $boundaries[0] && !array_key_exists("$x,$y", $rocks));
                $y++;
            } elseif ($direction === 1) {
                do {--$x; } while ($x >= $boundaries[1] && !array_key_exists("$x,$y", $rocks));
                $x++;
            } elseif ($direction === 2) {
                do {++$y; } while ($y <= $boundaries[2] && !array_key_exists("$x,$y", $rocks));
                $y--;
            } elseif ($direction === 3) {
                do {++$x; } while ($x <= $boundaries[3] && !array_key_exists("$x,$y", $rocks));
                $x--;
            }

            $rocks["$x,$y"] = '';
            $positions[$x + ($boundaries[3] + 1) * $y] = [$x, $y];
        }

        ksort($positions);

        return $positions;
    }

    public function part1(): int
    {
        [$balls, $rocks, $boundaries] = $this->parse();

        $balls = $this->move($balls, $rocks, $boundaries, 0);

        return array_reduce($balls, fn($total, $ball) => $total + ($boundaries[2] + 1 - $ball[1]), 0);
    }

    public function part2(): int
    {
        [$balls, $rocks, $boundaries] = $this->parse();

        $memo = [];
        $i = 0;

        while ($i < 1_000_000_000) {
            $balls = $this->move($balls, $rocks, $boundaries, 0);
            $balls = $this->move($balls, $rocks, $boundaries, 1);
            $balls = $this->move($balls, $rocks, $boundaries, 2);
            $balls = $this->move($balls, $rocks, $boundaries, 3);

            $key = json_encode($balls);

            if (isset($memo[$key]) && $i < 900_000_000) {
                $cycle = $i - $memo[$key];

                $i = ($cycle * (int)floor((1_000_000_000 - $i) / $cycle)) + $i;
            }

            $memo[$key] = $i++;
        }

        return array_reduce($balls, fn($total, $ball) => $total + ($boundaries[2] + 1 - $ball[1]), 0);
    }
}
