<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/1
 */
class Day01 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [142, 55538],
            [281, 54875],
        ];
    }

    private function parse(): array
    {
        return file($this->input);
    }

    private function calibrate($line): int
    {
        $line = preg_replace('/\D/', '', $line);

        return (int)($line[0].$line[-1]);
    }

    public function part1(): int
    {
        return array_reduce($this->parse(), fn ($total, $line) => $total + $this->calibrate($line), 0);
    }

    public function part2(): int
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

        return array_reduce($this->parse(), function ($total, $line) use ($regex, $convert) {
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

            return $total + $this->calibrate($line);
        }, 0);
    }
}
