<?php

interface iConfig {
}

class Config implements iConfig {
  public function __construct(
    public $sides,
    public $amount
  ) {
  }
}
