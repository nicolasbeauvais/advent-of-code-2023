<?php

function main(): void
{
    $mapToLocations = $mapToSeeds = [];
    $input = file_get_contents('../inputs/day05.txt');

    $seeds = array_map('intval', explode(' ', substr(strtok($input, "\n"), 7)));

    preg_match_all('/map:\s((?:[0-9]+\s[0-9]+\s[0-9]+\s)+)/m', $input, $matches, PREG_SET_ORDER);

    foreach ($matches as $index => $match) {
        $lines = explode("\n", trim($match[1]));

        foreach ($lines as $line) {
            [$destination, $source, $range] = array_map('intval', explode(' ', $line));

            $mapToLocations[$index][$source] = [$source, $source + $range - 1, $destination - $source];
            $mapToSeeds[$index][$destination] = [$destination, $destination + $range - 1, $source - $destination];
        }

        ksort($mapToLocations[$index]);
        ksort($mapToSeeds[$index]);
    }

    echo "Part 1: " . part1($seeds, $mapToLocations) . "\n";
    echo "Part 2: " . part2($seeds, array_reverse($mapToSeeds)) . "\n";
}

function find(int $seed, array $map): int
{
    foreach ($map as $ranges) {
        foreach ($ranges as $range) {
            if ($seed > $range[1]) {
                continue;
            }

            if ($seed < $range[0]) {
                break;
            }

            $seed += $range[2];
            break;
        }
    }

    return $seed;
}

function part1(array $seeds, array $map): int
{
    return array_reduce($seeds, static fn ($min, $seed) => min($min, find($seed, $map)), PHP_INT_MAX);
}

function part2(array $seeds, array $map): int
{
    $seedRanges = [];

    foreach (array_chunk($seeds, 2) as $range) {
        $seedRanges[$range[0]] = [$range[0], $range[0] + $range[1] - 1];
    }

    krsort($seedRanges);

    $location = 0;

    while (++$location) {
        $seed = find($location, $map);

        foreach ($seedRanges as $range) {
            if ($seed < $range[0]) {
                continue;
            }

            if ($seed > $range[1]) {
                break;
            }

            return $location;
        }
    }

    return -1;
}

main();
