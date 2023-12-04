<?php

function main(): void
{
    $deck = [];
    $input = file('../inputs/day04.txt');

    foreach ($input as $id => $line) {
        [$winning, $available] = explode('|', substr($line, strpos($line, ':') + 2, -1));

        $deck[$id] = count(array_filter(array_intersect(
            explode(' ', $winning),
            explode(' ', $available)
        )));
    }

    echo "Part 1: " . part1($deck) . "\n";
    echo "Part 2: " . part2($deck) . "\n";
}

function part1(array $deck): int
{
    return array_reduce($deck, fn ($total, $count) => $total + ($count ? 2 ** ($count - 1) : 0), 0);
}

function part2(array $deck): int
{
    $copies = [];

    foreach ($deck as $id => $score) {
        $increment = ($copies[$id] ?? 0) + 1;

        for ($i = $id + 1; $i <= $id + $score; $i++) {
            isset($copies[$i]) ? $copies[$i] += $increment : $copies[$i] = $increment;
        }
    }

    return array_sum($copies) + count($deck);
}

main();
