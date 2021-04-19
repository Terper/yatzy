<?php

interface iPlayer {
  public function checkBonus();
  public function getScore();
  public function getScoreType(string $scoreType);
  public function getName();
  public function addScore(string $scoreType, int $score);
  public function doesScoreTypeExist(string $scoreType);
}

class Player implements iPlayer {
  private string $name;
  private array $savedScores;
  private int $score;

  public function __construct($name) {
    $this->name = $name;
    $this->savedScores = [];
    $this->score = 0;
  }
  public function checkBonus() {
    foreach ($this->savedScores as $key => $value) {
      if ($key == "Bonus") {
        return;
      }
    }
    $total = 0;
    $valid = ["Ones", "Twos", "Threes", "Four", "Fives", "Sixes"];
    foreach ($this->savedScores as $key => $value) {
      if (in_array($key, $valid)) {
        $total += $value;
      }
    }
    if ($total >= 63) {
      $this->addScore("Bonus", 35);
    }
  }
  public function getScore(): int {
    return $this->score;
  }
  public function getName(): string {
    return $this->name;
  }
  public function getScoreType(string $scoreType): string {
    return $this->savedScores[$scoreType];
  }
  public function addScore(string $scoreType, int $score): void {
    $this->savedScores[$scoreType] = $score;
    $this->score += $score;
  }
  public function doesScoreTypeExist(string $key): bool {
    return isset($this->savedScores[$key]);
  }
}
