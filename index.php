<?php

require_once "./Game.php";
require_once "./Config.php";

session_start();

function showGame() {
  echo "<form method='post'>";
  foreach ($_SESSION["game"]->getDice() as $key => $value) {
    echo "<input type='checkbox' name='{$key} 'value='{$key}'> {$value}<br>";
  }
  $rollsLeft = $_SESSION["config"]->rolls - $_SESSION["rolls"];
  echo "{$rollsLeft} rolls left<br>";
  echo "<input type='submit'>";
  echo "</form>";
}

function showConfig() {
  echo "<form method='post'>";
  echo "Dice sides: <input type='number' min='1' value='6' name='sides'><br>";
  echo "Dice amount: <input type='number' min='1' value='5' name='amount'><br>";
  echo "Rolls: <input type='number' min='1' value='3' name='rolls'><br>";
  echo "<input type='submit' name='config'>";
  echo "</form>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  do {
    if (isset($_POST["config"])) {
      $_SESSION["config"] = new Config((int)$_POST["sides"], (int)$_POST["amount"], (int)$_POST["rolls"]);
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      $_SESSION["rolls"] = 1;
      break;
    }
    if (empty($_POST)) {
      $_SESSION["rolls"] = $_SESSION["config"]->rolls;
    }
    $_SESSION["rolls"]++;
    if ($_SESSION["rolls"] >= $_SESSION["config"]->rolls) {
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      $_SESSION["rolls"] = 1;
      break;
    }
    foreach ($_POST as $key => $value) {
      $_SESSION["game"]->roll($value);
    }
  } while (0);
  showGame();
} else {
  unset($_SESSION);
  showConfig();
}
