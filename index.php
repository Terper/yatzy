<?php

require_once "./Game.php";
require_once "./Config.php";



function showGame() {
  foreach ($_SESSION["game"]->getDice() as $key => $value) {
    echo "{$value}: <input type='checkbox' name='{$key} 'value='{$value}'><br>";
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
  var_dump($_POST);
  do {
    if (isset($_POST["config"])) {
      $_SESSION["config"] = new Config((int)$_POST["sides"], (int)$_POST["amount"]);
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      showGame();
      break;
    }
  } while (0);
  var_dump($_SESSION);
} else {
  unset($_SESSION);
  showConfig();
}
