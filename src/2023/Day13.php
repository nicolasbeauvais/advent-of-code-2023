<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/13
 */
class Day13 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [405, 31956], // Test Part 1, Real Part 1
            [400, 37617], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $maps = [];
        $index = 0;

        foreach (file($this->input, FILE_IGNORE_NEW_LINES) as $line) {
            if (empty($line)) {
                $index++;
                continue;
            }

            if (!isset($maps[$index])) {
                $maps[$index] = [
                    'cols' => array_fill(0, strlen($line), ''),
                    'rows' => []
                ];
            }

            $maps[$index]['rows'][] = $line;

            foreach (str_split($line) as $position => $char) {
                $maps[$index]['cols'][$position] .= $char;
            }
        }

        return $maps;
    }


    private function findReflectionLines(array $lines): int
    {
        $count = 0;
        $linesSize = strlen($lines[0]);

        for ($i = 1; $i < $linesSize; $i++) {
            foreach ($lines as $line) {
                $width = min($i, strlen($line) - $i);

                $before = substr($line, $i - $width, $width);
                $after = substr($line, $i, $width);

                if ($before !== strrev($after)) {
                    continue 2;
                }
            }

            $count += $i;
        }

        return $count;
    }

    private function findReflectionLinesWithSmudge(array $lines): int
    {
        $count = 0;
        $linesSize = strlen($lines[0]);

        for ($i = 1; $i < $linesSize; $i++) {
            $smudges = [];

            foreach ($lines as $line) {
                $width = min($i, strlen($line) - $i);

                $before = substr($line, $i - $width, $width);
                $after = substr($line, $i, $width);

                if ($before !== strrev($after)) {
                    $errors = array_diff_assoc(str_split($before), array_reverse(str_split($after)));

                    if (count($errors) > 1 || count($smudges) > 0) {
                        continue 2;
                    }

                    $smudges[] = $i;
                }
            }

            if (count($smudges) === 1) {
                $count += $smudges[0];
            }
        }

        return $count;
    }

    public function part1(): int
    {
        $maps = $this->parse();

        $total = 0;

        foreach ($maps as $map) {
            $total += $this->findReflectionLines($map['rows']);
            $total += 100 * $this->findReflectionLines($map['cols']);
        }

        return $total;
    }

    public function part2(): int
    {
        $maps = $this->parse();

        $total = 0;

        foreach ($maps as $map) {
            $total += $this->findReflectionLinesWithSmudge($map['rows']);
            $total += 100 * $this->findReflectionLinesWithSmudge($map['cols']);
        }

        return $total;
    }
}
