<?php

require_once "./Game.php";
require_once "./Config.php";

session_start();

function showGame() {
  echo "<form method='post'>";
  foreach ($_SESSION["game"]->getDice() as $key => $value) {
    echo "<input type='checkbox' name='{$key} 'value='{$key}'> {$value}<br>";
  }
  echo "<input type='submit'>";
  echo "</form>";
}

function showConfig() {
  echo "<form method='post'>";
  echo "Dice sides: <input type='number' min='1' value='6' name='sides'><br>";
  echo "Dice amount: <input type='number' min='1' value='5' name='amount'><br>";
  echo "<input type='submit' name='config'>";
  echo "</form>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  do {
    if (isset($_POST["config"])) {
      $_SESSION["config"] = new Config((int)$_POST["sides"], (int)$_POST["amount"]);
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      showGame();
      break;
    }
    foreach ($_POST as $key => $value) {
      $_SESSION["game"]->roll($value);
    }
    showGame();
  } while (0);
} else {
  unset($_SESSION);
  showConfig();
}
