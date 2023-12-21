<?php

namespace AOC\_2023;

/**
 * @see https://adventofcode.com/2023/day/19
 */
class Day19 extends AbstractDay
{
    public static function answers(): array
    {
        return [
            [19114, 333263], // Test Part 1, Real Part 1

            [167409079868000, 130745440937650], // Test Part 2, Real Part 2
        ];
    }

    private function parse(): array
    {
        $workflows = $parts = [];

        [$workflowInput, $partsInput] = explode("\n\n", trim(file_get_contents($this->input)));

        foreach (explode("\n", $workflowInput) as $line) {
            $name = strtok($line, '{');
            $workflows[$name] = [];

            foreach (explode(',', substr($line, strlen($name) + 1, -1)) as $step) {
                if (!str_contains($step, ':')) {
                    $workflows[$name][] = $step;
                    continue;
                }

                [$condition, $destination] = explode(':', $step);

                $operator = str_contains($condition, '>') ? '>' : '<';

                [$test, $value] = explode($operator, $condition);

                $workflows[$name][] = [$test, $operator, (int)$value, $destination];
            }
        }

        foreach (explode("\n", $partsInput) as $index => $line) {
            $parts[$index] = [];

            foreach (explode(',', substr($line, 1, -1)) as $part) {
                [$name, $value] = explode('=', $part);

                $parts[$index][$name] = (int)$value;
            }
        }

        return [$workflows, $parts];
    }

    private function workflowAccepted(array $part, array $workflows): bool
    {
        $current = 'in';

        while (true) {
            $workflow = $workflows[$current];
            $next = null;

            foreach ($workflow as $step) {
                if (!is_array($step)) {
                    continue;
                }

                [$test, $operator, $value, $destination] = $step;

                if ($operator === '>' ? $part[$test] > $value : $part[$test] < $value) {
                    $next = $destination;
                    break;
                }
            }

            if (!$next) {
                $next = $workflow[count($workflow) - 1];
            }

            if ($next === 'R') {
                return false;
            }

            if ($next === 'A') {
                return true;
            }

            $current = $next;
        }
    }

    public function part1(): int
    {
        [$workflows, $parts] = $this->parse();

        return array_reduce(
            $parts,
            fn ($total, $part) => $total + ($this->workflowAccepted($part, $workflows) ? array_sum($part) : 0),
            0
        );
    }

    private function walkWorkflows(string $current, array $part, array $workflows): int
    {
        if ($current === 'R') {
            return 0;
        }

        if ($current === 'A') {
            return array_product(array_map(fn ($value) => $value[1] - $value[0] + 1, $part));
        }

        $total = 0;
        $workflow = $workflows[$current];

        foreach ($workflow as $step) {
            $newPart = $part;

            if (is_array($step)) {
                [$test, $operator, $value, $destination] = $step;

                if ($operator === '>') {
                    $newPart[$test][0] = max($newPart[$test][0], $value + 1);
                    $part[$test][1] = min($part[$test][1], $value);
                }

                if ($operator === '<') {
                    $newPart[$test][1] = min($newPart[$test][1], $value - 1);
                    $part[$test][0] = max($part[$test][0], $value);
                }

                $step = $destination;
            }

            $total += $this->walkWorkflows($step, $newPart, $workflows);
        }

        return $total;
    }

    public function part2(): int
    {
        [$workflows] = $this->parse();
        $part = [
            'x' => [1, 4000],
            'm' => [1, 4000],
            'a' => [1, 4000],
            's' => [1, 4000],
        ];

        return $this->walkWorkflows('in', $part, $workflows);
    }
}
