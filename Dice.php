<?php

interface iDice {
  public function roll();
  public function getValue();
}

class Dice implements iDice {
  private int $value;
  private int $sides;
  public function __construct(int $sides = 6) {
    $this->sides = $sides;
    $this->roll();
  }
  public function roll(): void {
    $this->value = rand(1, $this->sides);
  }
  public function getValue(): int {
    return $this->value;
  }
}
