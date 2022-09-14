<?php
session_start();
$link = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['password'], $_SESSION['db_name']);

// Функция для генерации случайной строки
function generateCode($length = 6) {
  $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPRQSTUVWXYZ0123456789 ";
  $code = "";
  $clen = strlen($chars) - 1;
  while (strlen($code) < $length) {
    $code .= $chars[mt_rand(0, $clen)];
  }
  return $code;
}

if (isset($_POST['submit'])) {
  // Вытаскиваем из БД запись, у которой логин равняеться введенному
  $query = mysqli_query($link, "SELECT * FROM users WHERE login = '".mysqli_real_escape_string($link, $_POST['login'])."' LIMIT 1");
  $data = mysqli_fetch_assoc($query);

  // Сравниваем пароли
  if ($data['password'] === md5(md5($_POST['password']))) {
    $hash = md5(generateCode(10));

    // Записываем в БД новый хеш авторизации и IP
    mysqli_query($link, "UPDATE users SET hash = '".$hash."' WHERE user_id='".$data['user_id']."'");

    // Ставим куки
    setcookie("id", $data['user_id'], time() + 60 * 60 * 24 * 30, "/");
    setcookie("hash", $hash, time() + 60 * 60 * 24 * 30, "/", null, null, true);

    $_SESSION['auth'] = true;
    $_SESSION['id'] = $data['user_id'];
    $_SESSION['login'] = $data['login'];
    $_SESSION['right'] = $data['right'];

    // Переадресовываем браузер на страницу проверки нашего скрипта
    header("Location: check.php");
    exit();
  } else {
    $_SESSION['auth'] = false;
    echo "<script>alert(\"Вы ввели неправильный логин/пароль!\");</script>";
    header("Refresh:0; url=/index.php");
  }
}
?>
