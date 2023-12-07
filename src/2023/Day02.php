<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/2
 */
class Day02 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [8, 2512],
            [2286, 67335],
        ];
    }

    private function parse(): array
    {
        $games = [];

        foreach (file($this->input) as $index => $line) {
            $games[$index + 1] = array_map(function ($round) {
                $result = ['red' => 0, 'green' => 0, 'blue' => 0];
                $cubes = explode(', ', $round);

                foreach ($cubes as $cube) {
                    [$value, $color] = explode(' ', $cube);
                    $result[$color] = $value;
                }

                return $result;
            }, explode('; ', explode(': ', trim($line))[1]));
        }

        return $games;
    }

    public function part1(): int
    {
        $possible = array_filter($this->parse(), static function ($game) {
            foreach ($game as $round) {
                if ($round['red'] > 12 || $round['green'] > 13 || $round['blue'] > 14) {
                    return false;
                }
            }

            return true;
        });

        return array_sum(array_keys($possible));
    }

    public function part2(): int
    {
        return array_reduce($this->parse(), static function ($power, $game) {
            return $power + array_product(array_reduce($game, function ($max, $round) {
                    return [
                        'red' => max($max['red'], $round['red']),
                        'green' => max($max['green'], $round['green']),
                        'blue' => max($max['blue'], $round['blue']),
                    ];
                }, ['red' => 0, 'green' => 0, 'blue' => 0]));
        });
    }
}
