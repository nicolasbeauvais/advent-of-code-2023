<?php

function main()
{
    $symbols = $numbers = [];
    $y = 0;

    foreach (file('../inputs/day03.txt') as $line) {
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

    echo "Part 1: " . part1($symbols, $numbers) . "\n";
    echo "Part 2: " . part2($symbols, $numbers) . "\n";
}

function part1(array $symbols, array $numbers)
{
    $result = 0;

    foreach ($numbers as $number) {
        foreach ($number['coordinates'] as $coordinate) {
            foreach (neighbors($coordinate) as $neighbor) {
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

function part2(array $symbols, array $numbers)
{
    $result = 0;
    $gears = [];

    foreach ($numbers as $number) {
        foreach ($number['coordinates'] as $coordinate) {
            foreach (neighbors($coordinate) as $neighbor) {
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

function neighbors(array $coordinates): array
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

main();
