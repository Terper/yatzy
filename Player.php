<?php

interface iPlayer {
  public function getScore();
  public function getScoreType($scoreType);
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
  public function getScore(): int {
    return $this->score;
  }
  public function getName(): string {
    return $this->name;
  }
  public function getScoreType($scoreType): array {
    return $this->savedScores[$scoreType];
  }
  public function addScore(string $scoreType, int $score): void {
    $this->savedScores[$scoreType] = $score;
    $this->score += $score;
  }
  public function doesScoreTypeExist(string $key): bool {
    return !empty($this->savedScores[$key]);
  }
}
