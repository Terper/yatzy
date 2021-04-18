<?php

require_once "./Game.php";
require_once "./Config.php";
require_once "./Player.php";

session_start();
function showGame(): void {
  echo "<form method='post'>";
  if ($_SESSION["config"]->players > 1) {
    echo $_SESSION['players'][currentPlayer()]->getName() . "'s turn<br>";
  }
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
function showOptions(): void {
  echo "<form method='post'>";
  if ($_SESSION["config"]->players > 1) {
    echo $_SESSION['players'][currentPlayer()]->getName() . "'s turn<br>";
  }
  echo "Dice: ";
  foreach ($_SESSION["game"]->getDice() as $key => $value) {
    echo "{$value}";
  }
  echo "<br>";
  foreach ($_SESSION["game"]->getOptions() as $key => $value) {
    if (!$_SESSION["players"][currentPlayer()]->doesScoreTypeExist($value->scoreType)) {
      $scoreType = $value->scoreType;
      $score = $value->score;
      echo "{$scoreType} for {$score}<input type='radio' name='option' value='{$scoreType}&{$score}' required><br>";
    }
  }
  if (!$_SESSION["players"][currentPlayer()]->doesScoreTypeExist("Chance")) {
    echo "As chance? <input type='checkbox' name='chance'><br>";
  }
  echo "<input type='submit'>";
  echo "</form>";
}
function currentPlayer() {
  return (($_SESSION["gameNum"] - 1) % $_SESSION["config"]->players);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  do {
    var_dump($_POST);
    if (!empty($_POST["config"])) {
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
    if (!empty($_POST["player0"])) {
      foreach ($_POST as $key => $value) {
        $_SESSION["players"][] = new Player($value);
      }
      break;
    }
    if (!empty($_POST["option"])) {
      $values = explode("&", $_POST["option"]);
      if (!empty($_POST["chance"])) {
        $_SESSION["players"][currentPlayer()]->addScore("Chance", (int)$values[1]);
      } else {
        $_SESSION["players"][currentPlayer()]->addScore($values[0], (int)$values[1]);
      }
      $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
      $_SESSION["rolls"] = 1;
      $_SESSION["gameNum"]++;
      break;
    }
    $_SESSION["rolls"]++;
    if (empty($_POST)) {
      $_SESSION["rolls"] = $_SESSION["config"]->rolls;
    }
    if ($_SESSION["rolls"] >= $_SESSION["config"]->rolls) {
      showOptions();
      exit();
    }
    foreach ($_POST as $key => $value) {
      $_SESSION["game"]->roll($value);
    }
  } while (0);
  var_dump($_SESSION["players"]);  // remove
  showGame();
} else {
  session_unset();
  showConfig();
}
