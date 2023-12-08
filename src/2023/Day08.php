<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/8
 */
class Day08 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [2, 19637], // Test Part 1, Real Part 1
            [6, 8811050362409], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $map = [];
        $input = file_get_contents($this->input);

        $instructions = strtok($input, "\n");

        preg_match_all('/(\w{3})\s=\s\((\w{3}),\s(\w{3})\)/', $input, $matches, PREG_SET_ORDER);

        foreach ($matches as $match) {
            $map[$match[1]] = [
                'L' => $match[2],
                'R' => $match[3],
            ];
        }

        return [$instructions, $map];
    }

    private function steps(string $instructions, array $map, string $cursor, bool $zzz = true): int
    {
        $steps = 0;

        do {
            $cursor = $map[$cursor][$instructions[$steps % strlen($instructions)]];
            $steps++;
        } while ($zzz ? $cursor !== 'ZZZ' : $cursor[2] !== 'Z');

        return $steps;
    }

    public function part1(): int
    {
        [$instructions, $map] = $this->parse();

        return $this->steps($instructions, $map, 'AAA');
    }

    public function part2(): int
    {
        [$instructions, $map] = $this->parse();

        return (int)array_reduce(
            array_filter(array_keys($map), fn ($cursor) => $cursor[2] === 'A'),
            fn ($lcm, $cursor) => gmp_lcm($this->steps($instructions, $map, $cursor, false), $lcm),
            1
        );
    }
}
