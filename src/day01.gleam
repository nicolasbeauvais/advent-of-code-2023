import gleam/io
import gleam/string
import gleam/result
import gleam/regex
import gleam/int
import gleam/list
import gleam/dict
import simplifile

pub fn main() {
  io.println("# Day 01:\n")

  let input = simplifile.read("./inputs/day01.txt")
    |> result.unwrap("")
    |> string.split("\n")

  io.println("Part 1: " <> int.to_string(part1(input)))
  io.println("Part 2: " <> int.to_string(part2(input)))
}

pub fn part1(input: List(String)) -> Int {
  let artefacts = ["1", "2", "3", "4", "5", "6", "7", "8", "9"]

  input
    |> list.fold(0, fn(count, line) {
      let graphemes = line
        |> string.to_graphemes()

      let assert Ok(first) = graphemes  
        |> list.find(fn(graphene) {
          artefacts
          |> list.contains(graphene)
        })

      let assert Ok(last) = graphemes
        |> list.reverse
        |> list.find(fn(graphene) {
          artefacts
          |> list.contains(graphene)
        })

      let assert Ok(value) = int.base_parse(string.concat([first, last]), 10)

      count + value
    })
}

pub fn part2(input: List(String)) -> Int {
  let replace = dict.from_list([
    #("1", "1"),
    #("2", "2"),
    #("3", "3"),
    #("4", "4"),
    #("5", "5"),
    #("6", "6"),
    #("7", "7"),
    #("8", "8"),
    #("9", "9"),
    #("one", "1"),
    #("two", "2"),
    #("three", "3"),
    #("four", "4"),
    #("five", "5"),
    #("six", "6"),
    #("seven", "7"),
    #("eight", "8"),
    #("nine", "9")
  ])

  let assert Ok(pattern) = 
    replace
    |> dict.keys()
    |> list.reduce(fn (string, key) {
      string <> "|" <> key
    })

  input
  |> list.map(fn(line) { 
    let first = substitute(replace, pattern, line)
    |> result.unwrap(line)

    let last = substitute_reverse(replace, pattern, first)
    |> result.unwrap(line)

    last
  })
  |> part1()
}

fn substitute(replace, pattern, line) {
  let assert Ok(re) = regex.from_string(pattern)
  let matches = regex.scan(re, line)

  use match <- result.then(list.first(matches))
  use value <- result.then(dict.get(replace, match.content))

  Ok(string.replace(line, match.content, value))
}

fn substitute_reverse(replace, pattern, line) {
  let assert Ok(re) = 
    pattern
    |> string.reverse
    |> regex.from_string

  let line = string.reverse(line)
  
  let matches = regex.scan(re, line)

  use match <- result.then(list.first(matches))
  use value <- result.then(dict.get(replace, string.reverse(match.content)))

  string.replace(line, match.content, value)
  |> string.reverse
  |> Ok()
}