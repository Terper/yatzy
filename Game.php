<?php

require_once "./Dice.php";
require_once "./Option.php";

interface iGame {
  public function roll($index);
  public function getDice();
  public function getOptions();
  public function getOption($optionIndex);
}

class Game implements iGame {
  private int $sides;
  private int $amount;
  private array $dice;
  private array $options;

  public function __construct(int $sides = 6, int $amount = 5) {
    $this->sides = $sides;
    $this->amount = $amount;
    $this->dice = [];
    $this->fill();
  }
  public function roll($index): void {
    $this->dice[$index]->roll();
  }
  public function getDice(): array {
    $arr = [];
    foreach ($this->dice as $dice) {
      $arr[] = $dice->getValue();
    }
    return $arr;
  }
  public function getOptions(): array {
    if (empty($this->options)) {
      $dice = $this->getDice();
      sort($dice);
      if ($dice == [1, 2, 3, 4, 5]) {
        $this->options[] = new Option(array_sum($dice), "Small Straight");
      }
      if ($dice == [2, 3, 4, 5, 6]) {
        $this->options[] = new Option(array_sum($dice), "Large Straight");
      }
      $diceValues = array_count_values($dice);
      $search5 = array_keys($diceValues, 5);
      $search4 = array_keys($diceValues, 4);
      $search3 = array_keys($diceValues, 3);
      $search2 = array_keys($diceValues, 2);

      if (!empty($search5)) {
        $this->options[] = new Option(50, "Yatzy");
      }

      if (!empty($search4)) {
        $this->options[] = new Option(4 * $search4[0], "Four of a Kind");
      }
      if (!empty($search3)) {
        if (!empty($search2[0])) {
          $this->options[] = new Option(3 * $search3[0] + 2 * $search2[0], "Full House");
        } else {
          $this->options[] = new Option(3 * $search3[0], "Three of a Kind");
        }
      }
      if (!empty($search2)) {
        if (!empty($search2[1])) {
          $this->options[] = new Option(2 * $search2[0] + 2 * $search2[1], "Two Pairs");
        } else {
          $this->options[] = new Option(2 * $search2[0], "One Pair");
        }
      }
      foreach ($diceValues as $key => $value) {
        $this->options[] = new Option($key * $value, $this->getName($key));
      }
      $this->options[] = new Option(array_sum($dice), "Chance");
    }
    return $this->options;
  }
  public function getOption($optionIndex): array {
    return $this->options[$optionIndex];
  }
  private function fill(): void {
    for ($i = 0; $i < $this->amount; $i++) {
      $this->dice[] = new Dice($this->sides);
    }
  }
  private function getName($key): string {
    $arr = [
      1 => "Ones",
      2 => "Twos",
      3 => "Threes",
      4 => "Fours",
      5 => "Fives",
      6 => "Sixes"
    ];
    return $arr[$key];
  }
}
