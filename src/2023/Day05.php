<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/5
 */
class Day05 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [35, 265018614],
            [46, 63179500],
        ];
    }

    private function parse(bool $backward = false): array
    {
        $map = [];
        $input = file_get_contents($this->input);

        $seeds = array_map('intval', explode(' ', substr(strtok($input, "\n"), 7)));

        preg_match_all('/map:\s((?:[0-9]+\s[0-9]+\s[0-9]+\s)+)/m', $input, $matches, PREG_SET_ORDER);

        foreach ($matches as $index => $match) {
            $lines = explode("\n", trim($match[1]));

            foreach ($lines as $line) {
                [$destination, $source, $range] = array_map('intval', explode(' ', $line));

                if ($backward) {
                    $map[$index][$destination] = [$destination, $destination + $range - 1, $source - $destination];
                } else {
                    $map[$index][$source] = [$source, $source + $range - 1, $destination - $source];
                }
            }

            ksort($map[$index]);
        }

        if ($backward) {
            $map = array_reverse($map);
        }

        return [$seeds, $map];
    }

    private function find(int $seed, array $map): int
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

    public function part1(): int
    {
        [$seeds, $map] = $this->parse();

        return array_reduce($seeds, fn($min, $seed) => min($min, $this->find($seed, $map)), PHP_INT_MAX);
    }

    public function part2(): int
    {
        [$seeds, $map] = $this->parse(true);

        $seedRanges = [];

        foreach (array_chunk($seeds, 2) as $range) {
            $seedRanges[$range[0]] = [$range[0], $range[0] + $range[1] - 1];
        }

        krsort($seedRanges);

        $location = 0;

        while (++$location) {
            $seed = $this->find($location, $map);

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
}
