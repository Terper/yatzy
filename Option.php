<?php

interface iOption {
}

class Option implements iOption {
  public function __construct(
    public int $score,
    public string $scoreType
  ) {
  }
}
