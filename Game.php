<?php

require_once "./Dice.php";

interface iGame {
  public function roll($index);
  public function getDice();
}

class Game implements iGame {
  private int $sides;
  private int $amount;
  private array $dice;

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
  private function fill(): void {
    for ($i = 0; $i < $this->amount; $i++) {
      $this->dice[] = new Dice($this->sides);
    }
  }
}
