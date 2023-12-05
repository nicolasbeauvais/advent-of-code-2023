<?php

function main(): void
{
    $input = file('../inputs/day01.txt');

    echo "Part 1: " . part1($input) . "\n";
    echo "Part 2: " . part2($input) . "\n";
}

function calibrate($line): int
{
    $line = preg_replace('/\D/', '', $line);

    return (int)($line[0].$line[-1]);
}

function part1(array $input): int
{
    return array_reduce($input, static fn ($total, $line) => $total + calibrate($line), 0);
}

function part2(array $input): int
{
    $convert = [
        '1' => '1', '2' => '2', '3' => '3',
        '4' => '4', '5' => '5', '6' => '6',
        '7' => '7', '8' => '8', '9' => '9',
        'one' => '1', 'two' => '2', 'three' => '3',
        'four' => '4', 'five' => '5', 'six' => '6',
        'seven' => '7', 'eight' => '8', 'nine' => '9',
    ];

    $regex = '/'. implode('|', array_keys($convert)).'/';

    return array_reduce($input, static function ($total, $line) use ($regex, $convert) {
        $line = preg_replace_callback(
            $regex,
            static fn ($match) => $convert[$match[0]],
            $line, 1
        );

        $line = strrev(preg_replace_callback(
            strrev($regex),
            static fn ($match) => $convert[strrev($match[0])],
            strrev($line), 1
        ));

        return $total + calibrate($line);
    }, 0);
}

main();
