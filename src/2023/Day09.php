<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/9
 */
class Day09 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [114, 1696140818], // Test Part 1, Real Part 1
            [2, 1152], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        return array_map(function ($line) {
            $lines = [['sequence' => array_map('intval', explode(' ', $line)), 'hold' => 0]];

            while (true) {
                $sequence = end($lines)['sequence'];
                $newSequence = [];

                foreach ($sequence as $i => $number) {
                    if ($i === 0) {
                        continue;
                    }

                    $newSequence[] = $number - $sequence[$i-1];
                }

                $lines[] = ['sequence' => $newSequence, 'hold' => 0];

                if (count(array_unique($newSequence)) === 1 && $newSequence[0] === 0) {
                    break;
                }
            }

            return array_reverse($lines);
        }, file($this->input));
    }

    public function part1(): int
    {
        return array_reduce($this->parse(), function ($total, $sequences) {
            foreach ($sequences as $index => $line) {
                if ($index === 0) {
                    continue;
                }

                $sequences[$index]['hold'] = end($line['sequence']) + $sequences[$index - 1]['hold'];
            }

            return $total + end($sequences)['hold'];
        }, 0);
    }

    public function part2(): int
    {
        return array_reduce($this->parse(), function ($total, $sequences) {
            foreach ($sequences as $index => $line) {
                if ($index === 0) {
                    continue;
                }

                $sequences[$index]['hold'] = $line['sequence'][0] - $sequences[$index - 1]['hold'];
            }

            return $total + end($sequences)['hold'];
        }, 0);
    }
}
