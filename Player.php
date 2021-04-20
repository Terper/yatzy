<?php

interface iPlayer {
  public function checkBonus();
  public function getScore();
  public function getScoreType(string $scoreType);
  public function countSavedScores();
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
    $valid = ["Ones", "Twos", "Threes", "Fours", "Fives", "Sixes"];
    $counter = 0;
    foreach ($this->savedScores as $key => $value) {
      if ($key == "Bonus") {
        return;
      }
      if (in_array($key, $valid)) {
        $counter++;
      }
    }
    if ($counter != count($valid)) {
      return;
    }
    $total = 0;
    foreach ($this->savedScores as $key => $value) {
      if (in_array($key, $valid)) {
        $total += $value;
      }
    }
    if ($total >= 63) {
      $this->addScore("Bonus", 35);
    } else {
      $this->addScore("Bonus", 0);
    }
  }
  public function getScore(): int {
    return $this->score;
  }
  public function countSavedScores() {
    return count($this->savedScores);
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
    $this->checkBonus();
  }
  public function doesScoreTypeExist(string $key): bool {
    return isset($this->savedScores[$key]);
  }
}
