<?php

interface iConfig {
}

class Config implements iConfig {
  public function __construct(
    public int $sides,
    public int $amount,
    public int $rolls,
    public int $players,
    public int $rounds,
    public bool $forced,
  ) {
  }
}
