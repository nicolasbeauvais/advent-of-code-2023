import gleam/io
import gleam/string
import gleam/result
import gleam/int
import gleam/list
import gleam/bool
import simplifile

pub fn main() {
  io.println("# Day 02:\n")

  let games = simplifile.read("./inputs/day02.txt")
    |> result.unwrap("")
    |> string.split("\n")
    |> list.map(fn (line) {
      let assert Ok(line) = 
        line
        |> string.drop_left(5)
        |> string.split_once(": ")

      let assert Ok(id) = int.base_parse(line.0, 10)

      let rounds = 
        line.1
        |> string.split("; ")
        |> list.map(fn(line) {
          line
          |> string.split(", ")
          |> list.fold(Round(0, 0, 0), fn (round, line) {
            let assert Ok(pair) = string.split_once(line, " ")
            let assert Ok(value) = int.base_parse(pair.0, 10)

            case pair.1 {
              "red" -> Round(..round, red: round.red + value)
              "green" -> Round(..round, green: round.green + value)
              "blue" -> Round(..round, blue: round.blue + value)
            }
          })
        })
      
      Game(id: id, rounds: rounds)
    })

  io.println("Part 1: " <> int.to_string(part1(games)))
  io.println("Part 2: " <> int.to_string(part2(games)))
}

pub type Game {
  Game(id: Int, rounds: List(Round))
}

pub type Round {
  Round(red: Int, green: Int, blue: Int)
}

pub fn part1(games: List(Game)) -> Int {
  let max = Round(12, 13, 14)

  games
  |> list.filter(fn (game) {
    game.rounds 
    |> list.any(fn (round) {
      let Round(red: red, green: green, blue: blue) = round

      red > max.red || green > max.green || blue > max.blue
    })
    |> bool.negate
  })
  |> list.fold(0, fn (count, game) {
    count + game.id
  })
}

pub fn part2(games: List(Game)) -> Int {
  games
  |> list.fold(0, fn (power, game) {
    let assert Ok(max) = 
      game.rounds
      |> list.reduce(fn (max, round) {
        Round(
          red: int.max(round.red, max.red), 
          green: int.max(round.green, max.green), 
          blue: int.max(round.blue, max.blue)
        )
      })   

    power + max.red * max.green * max.blue
  })
}
