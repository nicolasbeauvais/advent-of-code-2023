<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/12
 */
class Day12 extends AbstractDay
{
    private array $memo = [];

    public static function answers(): array
    {
        return [
            [21, 7025], // Test Part 1, Real Part 1
            [525152, 11461095383315], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        return array_map(function ($line) {
            $parts = explode(' ', $line);

            return [
                $parts[0],
                array_map('intval', explode(',', $parts[1]))
            ];
        }, file($this->input, FILE_IGNORE_NEW_LINES));
    }

    private function countPermutations(string $line, array $masks): int
    {
        if (empty($line)) {
            return empty($masks) ? 1 : 0;
        }

        if (empty($masks)) {
            return !str_contains($line, '#') ? 1 : 0;
        }

        $key = $line . implode(',', $masks);

        if (isset($this->memo[$key])) {
            return $this->memo[$key];
        }

        $permutations = 0;

        if (in_array($line[0], ['.', '?'], true)) {
            $permutations += $this->countPermutations(substr($line, 1), $masks);
        }

        if (
            in_array($line[0], ['#', '?'], true)
            && $masks[0] <= strlen($line)
            && !str_contains(substr($line, 0, $masks[0]), '.')
            && ($masks[0] === strlen($line) || $line[$masks[0]] !== '#')
        ) {
            $permutations += $this->countPermutations(substr($line, $masks[0] + 1), array_slice($masks, 1));
        }

        return $this->memo[$key] = $permutations;
    }

    public function part1(): int
    {
        return array_reduce($this->parse(), function ($total, $input) {
            [$line, $masks] = $input;

            return $total + $this->countPermutations(trim($line, '.'), $masks);
        }, 0);
    }

    public function part2(): int
    {
        return array_reduce($this->parse(), function ($total, $input) {
            [$line, $masks] = $input;

            $line = implode('?', array_fill(0, 5, $line));
            $masks = array_merge(...array_fill(0, 5, $masks));

            return $total + $this->countPermutations(trim($line, '.'), $masks);
        }, 0);
    }
}
