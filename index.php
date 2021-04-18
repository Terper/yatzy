<?php

require_once "./Game.php";
require_once "./Config.php";
require_once "./Player.php";

session_start();

echo "<link rel='stylesheet' href='style.css'>";




function showGame(): void {
  echo "<form method='post'>";
  if ($_SESSION["config"]->players > 1) {
    echo $_SESSION['players'][currentPlayer()]->getName() . "'s turn<br>";
  }
  if ($_SESSION["config"]->forced) {
    echo "Goal is to get " . currentType() . "<br>";
  }
  foreach ($_SESSION["game"]->getDice() as $key => $value) {
    echo "<input type='checkbox' name='{$key} 'value='{$key}'> {$value}<br>";
  }
  $rollsLeft = $_SESSION["config"]->rolls - $_SESSION["rolls"];
  echo "{$rollsLeft} rolls left<br>";
  echo "<input type='submit'>";
  echo "</form>";
}

function showScoreboard(): void {
  $scoreTypes = [
    "Ones", "Twos", "Threes",
    "Fours", "Fives", "Sixes",
    "One Pair", "Two Pairs",
    "Three of a Kind", "Four of a Kind",
    "Full House", "Small Straight",
    "Large Straight", "Yatzy", "Chance"
  ];
  echo "<table>";
  echo "<caption>Scoreboard<caption>";
  echo "<tr>";
  echo "<th></th>";
  foreach ($_SESSION["players"] as $playerValue) {
    echo "<th>";
    echo $playerValue->getName();
    echo "</th>";
  }
  echo "</tr>";
  foreach ($scoreTypes as $scoreTypesValue) {
    echo "<tr>";
    echo "<th>{$scoreTypesValue}</th>";
    foreach ($_SESSION["players"] as $playerValue) {
      echo "<td>";
      if ($playerValue->doesScoreTypeExist($scoreTypesValue)) {
        echo $playerValue->getScoreType($scoreTypesValue);
      }
      echo "</td>";
    }
    echo "</tr>";
  }
  echo "<tr><th>Total score</th>";
  foreach ($_SESSION["players"] as $playerValue) {
    echo "<td>";
    echo $playerValue->getScore();
    echo "</td>";
  }
  echo "</tr>";
  echo "</table>";
}

function showConfig(): void {
  echo "<form method='post'>";
  echo "Dice sides: <input type='number' min='1' value='6' name='sides' readonly><br>";
  echo "Dice amount: <input type='number' min='1' value='5' name='amount' readonly><br>";
  echo "Rolls: <input type='number' min='1' value='3' name='rolls'><br>";
  echo "Players: <input type='number' min='1' value='1' name='players'><br>";
  echo "Forced: <input type='checkbox' name='forced'><br>";
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
  echo "<input type='submit'>";
  echo "</form>";
}
function currentPlayer() {
  return (($_SESSION["gameNum"] - 1) % $_SESSION["config"]->players);
}

function currentType() {
  $scoreTypes = [
    "Ones", "Twos", "Threes",
    "Fours", "Fives", "Sixes",
    "One Pair", "Two Pairs",
    "Three of a Kind", "Four of a Kind",
    "Full House", "Small Straight",
    "Large Straight", "Yatzy", "Chance"
  ];
  return $scoreTypes[floor($_SESSION["gameNum"] / $_SESSION["config"]->players) - 1];
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  do {
    if (!empty($_POST["config"])) {
      $_SESSION["config"] = new Config(
        (int)$_POST["sides"],
        (int)$_POST["amount"],
        (int)$_POST["rolls"],
        (int)$_POST["players"],
        !empty($_POST["forced"]) ? true : false,
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
      $_SESSION["players"][currentPlayer()]->addScore($values[0], (int)$values[1]);
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
      if ($_SESSION["config"]->forced) {
        $_SESSION["players"][currentPlayer()]->addScore(currentType(), $_SESSION["game"]->getForcedScore(currentType()));
        $_SESSION["game"] = new Game($_SESSION["config"]->sides, $_SESSION["config"]->amount);
        $_SESSION["rolls"] = 1;
        $_SESSION["gameNum"]++;
      } else {
        showOptions();
        exit();
      }
    }
    foreach ($_POST as $key => $value) {
      $_SESSION["game"]->roll($value);
    }
  } while (0);
  showGame();
  showScoreboard();
} else {
  session_unset();
  showConfig();
}
