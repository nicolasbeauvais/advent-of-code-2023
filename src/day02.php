<?php

function main(): void
{
    $games = [];

    foreach (file('../inputs/day02.txt') as $index => $line) {
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

    echo "Part 1: " . part1($games) . "\n";
    echo "Part 2: " . part2($games) . "\n";
}

function part1(array $games): int
{
    $possible = array_filter($games, static function ($game) {
        foreach ($game as $round) {
            if ($round['red'] > 12 || $round['green'] > 13 || $round['blue'] > 14) {
                return false;
            }
        }

        return true;
    });

    return array_sum(array_keys($possible));
}

function part2(array $games): int
{
    return array_reduce($games, static function ($power, $game) {
        return $power + array_product(array_reduce($game, function ($max, $round) {
                return [
                    'red' => max($max['red'], $round['red']),
                    'green' => max($max['green'], $round['green']),
                    'blue' => max($max['blue'], $round['blue']),
                ];
            }, ['red' => 0, 'green' => 0, 'blue' => 0]));
    });
}

main();
