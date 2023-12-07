<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/3
 */
class Day03 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [4361, 535235],
            [467835, 79844424],
        ];
    }

    private function parse(): array
    {
        $symbols = $numbers = [];
        $y = 0;

        foreach (file($this->input) as $line) {
            preg_match_all('/\d+|[^\d\s.]/', $line, $matches, PREG_OFFSET_CAPTURE);
            $y++;

            foreach ($matches[0] as $match) {
                $x = ($match[1] + 1);

                !is_numeric($match[0])
                    ? $symbols["$x,$y"] = $match[0]
                    : $numbers[] = [
                    'value' => (int)$match[0],
                    'coordinates' => array_map(fn ($x) => [$x,$y], range($x, $x + strlen($match[0]) - 1)),
                ];
            }
        }

        return [$symbols, $numbers];
    }

    private function neighbors(array $coordinates): array
    {
        [$x, $y] = $coordinates;

        return [
            "$x," . ($y - 1),
            "$x," . ($y + 1),
            ($x - 1) . ",$y",
            ($x + 1) . ",$y",
            ($x - 1) . "," . ($y - 1),
            ($x - 1) . "," . ($y + 1),
            ($x + 1) . "," . ($y - 1),
            ($x + 1) . "," . ($y + 1),
        ];
    }

    public function part1(): int
    {
        $result = 0;

        [$symbols, $numbers] = $this->parse();

        foreach ($numbers as $number) {
            foreach ($number['coordinates'] as $coordinate) {
                foreach ($this->neighbors($coordinate) as $neighbor) {
                    if (!isset($symbols[$neighbor])) {
                        continue;
                    }

                    $result += $number['value'];
                    break 2;
                }
            }
        }

        return $result;
    }

    public function part2(): int
    {
        $result = 0;
        $gears = [];

        [$symbols, $numbers] = $this->parse();

        foreach ($numbers as $number) {
            foreach ($number['coordinates'] as $coordinate) {
                foreach ($this->neighbors($coordinate) as $neighbor) {
                    if (!isset($symbols[$neighbor]) || $symbols[$neighbor] !== '*') {
                        continue;
                    }

                    isset($gears[$neighbor])
                        ? $gears[$neighbor][] = $number['value']
                        : $gears[$neighbor] = [$number['value']];

                    break 2;
                }
            }
        }

        foreach ($gears as $gear) {
            $result += count($gear) > 1 ? array_product($gear) : 0;
        }

        return $result;
    }
}
