<?php
session_start();
$link = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['password'], $_SESSION['db_name']);

if (isset($_COOKIE['id']) and isset($_COOKIE['hash'])) {
  $query = mysqli_query($link, "SELECT * FROM users WHERE user_id = '".intval($_COOKIE['id'])."' LIMIT 1");
  $userdata = mysqli_fetch_assoc($query);

  if (($userdata['hash'] !== $_COOKIE['hash']) or ($userdata['user_id'] !== $_COOKIE['id'])) {
      setcookie("id", "", time() - 3600 * 24 * 30 * 12, "/");
      setcookie("hash", "", time() - 3600 * 24 * 30 * 12, "/", null, null, true);
  } else {
      header("Location: ../index.php");
      exit;
  }
} else {
    echo "Включите куки";
}
?>
