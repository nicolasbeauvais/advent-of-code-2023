<?php

namespace AOC\_2023;

class Day07 extends AbstractDay
{
    private const FIVE_OF_A_KIND = 7;
    private const FOUR_OF_A_KIND = 6;
    private const FULL_HOUSE = 5;
    private const THREE_OF_A_KIND = 4;
    private const TWO_PAIR = 3;
    private const ONE_PAIR = 2;
    private const HIGH_CARD = 1;

    public static function answers(): array
    {
        return [
            [6440, 248812215], // Test Part 1, Real Part 1
            [5905, 250057090], // Test Part 2, Real Part 2
        ];
    }

    private function parse(bool $useJokers = false): array
    {
        return array_map(function ($line) use ($useJokers) {
            [$hand, $bid] = explode(' ', $line);

            $hex = str_replace(['A', 'K', 'Q', 'J', 'T'], ['e', 'd', 'c', $useJokers ? '1' : 'b', 'a'], $hand);
            $hand =  str_split($hand);

            return [
                'hand' => $hand,
                'hex' => $hex,
                'type' => $this->type($hand, $useJokers),
                'bid' => (int)$bid,
            ];
        }, file($this->input));
    }

    private function type(array $hand, bool $useJokers = false): string
    {
        $jokers = 0;

        if ($useJokers) {
            $hand = array_filter($hand, fn($card) => $card !== 'J');
            $jokers = 5 - count($hand);
        }

        $counted = array_count_values($hand);

        if ($jokers >= 4) {
            return self::FIVE_OF_A_KIND;
        }

        if ($jokers === 3) {
            return count($counted) === 1 ? self::FIVE_OF_A_KIND : self::FOUR_OF_A_KIND;
        }

        if ($jokers === 2) {
            return match(count($counted)) {
                1 => self::FIVE_OF_A_KIND,
                2 => self::FOUR_OF_A_KIND,
                default => self::THREE_OF_A_KIND
            };
        }

        if ($jokers === 1) {
            return match(count($counted)) {
                1 => self::FIVE_OF_A_KIND,
                2 => in_array(3, $counted, true) ? self::FOUR_OF_A_KIND : self::FULL_HOUSE,
                3 => self::THREE_OF_A_KIND,
                default => self::ONE_PAIR,
            };
        }

        return match(count($counted)) {
            1 => self::FIVE_OF_A_KIND,
            2 => in_array(4, $counted, true) ? self::FOUR_OF_A_KIND : self::FULL_HOUSE,
            3 => in_array(3, $counted, true) ? self::THREE_OF_A_KIND : self::TWO_PAIR,
            4 => self::ONE_PAIR,
            default => self::HIGH_CARD,
        };
    }

    public function rank(array $hands): int
    {
        $groups = array_reduce($hands, function ($groups, $hand) {
            $groups[$hand['type']][] = $hand;

            return $groups;
        }, []);

        ksort($groups);

        $groups = array_map(function ($group) {
            usort($group, fn ($a, $b) => hexdec($a['hex']) <=> hexdec($b['hex']));

            return $group;
        }, $groups);

        $rank =  0;
        $result = 0;

        foreach ($groups as $group) {
            foreach ($group as $hand) {
                $result += $hand['bid'] * ++$rank;
            }
        }

        return $result;
    }

    public function part1(): int
    {
        return $this->rank($this->parse());
    }

    public function part2(): int
    {
        return $this->rank($this->parse(true));
    }
}

