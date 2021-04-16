<?php

interface iConfig {
}

class Config implements iConfig {
  public function __construct(
    public int $sides,
    public int $amount,
    public int $rolls,
  ) {
  }
}
