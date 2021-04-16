<?php

require_once "./Game.php";
require_once "./Config.php";
require_once "./Player.php";

session_start();

function showGame(): void {
  echo "<form method='post'>";
  foreach ($_SESSION["game"]->getDice() as $key => $value) {
    echo "<input type='checkbox' name='{$key} 'value='{$key}'> {$value}<br>";
  }
  $rollsLeft = $_SESSION["config"]->rolls - $_SESSION["rolls"];
  echo "{$rollsLeft} rolls left<br>";
  echo "<input type='submit'>";
  echo "</form>";
}

function showConfig(): void {
  echo "<form method='post'>";
  echo "Dice sides: <input type='number' min='1' value='6' name='sides'><br>";
  echo "Dice amount: <input type='number' min='1' value='5' name='amount'><br>";
  echo "Rolls: <input type='number' min='1' value='3' name='rolls'><br>";
  echo "Players: <input type='number' min='1' value='1' name='players'><br>";
  echo "<input type='submit' name='config'>";
  echo "</form>";
}

function showPlayerConfig(): void {
  echo "<form method='post'>";
  for ($i = 0; $i < $_SESSION["config"]->players; $i++) {
    $playerNr = $i + 1;
    echo "Player {$playerNr} <input name='player{$i}' required><br>";
  }
  echo "<input type='submit'>";
  echo "</form>";
}
/* Old
function currentUser(){
  return $_SESSION["users"][$_SESSION["gameNr"] % count($_SESSION["users"])];
}
*/
function currentPlayer(): string {
  return $_SESSION["players"][($_SESSION["gameNum"] % $_SESSION["config"]->players)]->getName();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  var_dump($_POST);
  do {
    if (isset($_POST["config"])) {
      $_SESSION["config"] = new Config(
        (int)$_POST["sides"],
        (int)$_POST["amount"],
        (int)$_POST["rolls"],
        (int)$_POST["players"]
      );
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      $_SESSION["gameNum"] = 1;
      $_SESSION["rolls"] = 1;
      $_SESSION["players"] = [];
      showPlayerConfig();
      exit();
    }
    if (isset($_POST["player0"])) {
      foreach ($_POST as $key => $value) {
        $_SESSION["players"][] = new Player($value);
      }
      break;
    }
    if (empty($_POST)) {
      $_SESSION["rolls"] = $_SESSION["config"]->rolls;
    }
    echo currentPlayer();
    $_SESSION["rolls"]++;
    if ($_SESSION["rolls"] >= $_SESSION["config"]->rolls) {
      var_dump($_SESSION["game"]->getOptions());
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      $_SESSION["rolls"] = 1;
      $_SESSION["gameNum"]++;
    }
    foreach ($_POST as $key => $value) {
      $_SESSION["game"]->roll($value);
    }
  } while (0);
  showGame();
} else {
  session_unset();
  showConfig();
}
