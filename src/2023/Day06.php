<?php

namespace AOC\_2023;

class Day06 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [288, 2449062], // Test Part 1, Real Part 1
            [71503, 33149631], // Test Part 2, Real Part 2
        ];
    }

    private function race(int $time, int $distance): int
    {
        $even = $time % 2 === 0;

        $backward = $even ? ($time / 2) - 1 : (int)floor($time / 2);
        $forward = $even ? $time / 2 : (int)ceil($time / 2);

        while ($backward * ($time - $backward) > $distance) {
            --$backward;
        }

        while ($forward * ($time - $forward) > $distance) {
            ++$forward;
        }

        return $forward - $backward - 1;
    }

    public function part1(): int
    {
        $races = array_reduce(file($this->input), function ($races, $line) {
            $values = array_values(array_map('intval', array_filter(explode(' ', explode(': ', $line)[1]))));

            foreach ($values as $index => $value) {
                $races[$index][] = $value;
            }

            return $races;
        }, []);

        return array_reduce($races, fn ($total, $race) => $total *= $this->race(...$race), 1);
    }

    public function part2(): int
    {
        $input = file($this->input);

        return $this->race(
            (int)explode(':', str_replace(' ', '', $input[0]))[1],
            (int)explode(':', str_replace(' ', '', $input[1]))[1]
        );
    }
}

