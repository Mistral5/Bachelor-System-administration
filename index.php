<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Реестр кинофильмов</title>
  <meta name='viewport' content='width=device-width, initial-scale=1.0'>
  <link rel="stylesheet" type="text/css" href="style.css" />
  <link rel="stylesheet" type="text/css" href="./slick/slick.css" />
  <link rel="stylesheet" type="text/css" href="./slick/slick-theme.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.css" />
  <link rel="shortcut icon" type="image/png" href="../img/favicon.svg" >
</head>
<body>
  <header class="d-flex justify-content-around align-items-center">
    <div class="d-flex align-items-center">
      <img src="../img/favicon.svg" class="logo_img mr-05">
      <a class="logo_text" href="index.php">Film</br>Registry</a>
    </div>
    <div></div>
    <div></div>
    <form method="post" id="ajax_form" class="form_search">
      <input id="query" type="text" class="search" name="query" autocomplete="off" />
      <button type="submit"></button>
    </form>
    <a href="#test-popup" class="show-popup" data-effect="mfp-zoom-out">
      <button class="login_button" type="button">
        <svg class="login_button_logo" viewBox="0 0 10 10" xmlns="http://www.w3.org/2000/svg" role="presentation">
          <path d="M5 5.295c-1.296 0-2.385-1.176-2.385-2.678C2.61 1.152 3.716 0 5 0c1.29 0 2.39 1.128 2.39 2.611C7.39 4.12 6.297 5.295 5 5.295zM1.314 11C.337 11 0 10.698 0 10.144c0-1.55 1.929-3.685 5-3.685 3.065 0 5 2.135 5 3.685 0 .554-.337.856-1.314.856z"></path>
        </svg>
        Войти
      </button>
    </a>
  </header>

  <section class="section_empty d-flex"></section>

  <div id="search_result"></div>

<section class="section_carousel">
  <b class="section_carousel_header">Лучшее за все время</b>
<?php
session_start();
$_SESSION['host'] = 'cursed';
$_SESSION['user'] = 'root';
$_SESSION['password'] = '';
$_SESSION['db_name'] = 'film_registry';
$link = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['password'], $_SESSION['db_name']);

if (!$link) {
  echo 'Не могу соединиться с БД. Код ошибки: ' . mysqli_connect_errno() . ', ошибка: ' . mysqli_connect_error();
  exit;
}

function role($data) {
  $role = explode(",", $data['role']);
  for ($i = 0; $i < count($role); $i++)
    echo $role[$i]."<br/>";
}

$info = mysqli_query($link, "SELECT * FROM films LIMIT 2");
while ($data = mysqli_fetch_assoc($info)) {
  $date = date_create($data['year']);
  $date_ = date_format($date, 'Y');
  $photo = explode(",", $data['photo']);
  ?>
    <div class="divider"></div>
    <div class="lazy slider">
      <div class="d-flex slide-position align-items-center">
        <div class="d-flex justify-content-around size">
          <div class="d-flex align-items-center">
            <img class="section_carousel_photo" src="<?= $photo[0] ?>">
          </div>
          <div class="d-flex align-items-center">
            <table class="section_carousel_table_text" cellspacing="5" cellpadding="5">
              <tr>
               <td colspan="3"><h2 class="section_carousel_table_header"><?= $data['name']." (".$date_.")" ?></h2></td>
              </tr>
              <tr>
               <td colspan="3">
                 <div class="section_carousel_table_text_synopsis pr-05">
                   <?= $data['synopsis'] ?>
                 </div>
               </td>
              </tr>
              <tr>
               <td><b>Страна:</b></td>
               <td><?= $data['country'] ?></td>
               <td><b>В главных ролях:</b></td>
              </tr>
              <tr>
               <td><b>Жанр:</b></td>
               <td><?= $data['genre'] ?></td>
               <td rowspan="5" class="vertical-align-text-top"><?= role($data) ?></td>
              </tr>
              <tr>
               <td><b>Режиссёр:</b></td>
               <td><?= $data['director'] ?></td>
              </tr>
              <tr>
               <td><b>Бюджет:</b></td>
               <td>$<?= $data['budget'] ?></td>
              </tr>
              <tr>
               <td><b>Время:</b></td>
               <td><?= $data['time'] ?> минут</td>
              </tr>
              <tr>
               <td><b>Возраст:</b></td>
               <td><?= $data['age'] ?>+</td>
              </tr>
            </table>
          </div>
        </div>
      </div>

      <div class="section_carousel_photo1 size_" style="background-image: url(<?= $photo[1] ?>);"></div>

      <div class="d-flex align-items-center">
        <img class="section_carousel_photo2 size_" src="<?= $photo[2] ?>">
      </div>
    </div>
  <?php
}
?>
</section>

  <div id="notification"></div>

<?php
if ($_SESSION['auth'] == true && $_SESSION['right'] < 2) {
  if ($_SESSION['auth'] == true && $_SESSION['right'] < 1) {
    if (isset($_GET['del'])) {
      $sql_del = mysqli_query($link, "DELETE FROM `films` WHERE `film_id` = {$_GET['del']}");
      if ($sql_del) {
        echo "<script>var notification_text = \"Данные успешно удалены из таблицы\"; notification(notification_text);</script>";
        header("Refresh:0; url=index.php");
      }
      else {
        echo "<script>var notification_text = \"Произошла ошибка: ". mysqli_error($link) ."\"; notification(notification_text);</script>";
        header("Refresh:0; url=index.php");
      }
    }
  }

  if (isset($_GET['edit'])) {
    $sql_edit = mysqli_query($link, "SELECT * FROM `films` WHERE `film_id` = {$_GET['edit']}");
    $product = mysqli_fetch_array($sql_edit);
  }
  if (!empty($_POST["film_id"]) and !empty($_POST["film_id"])) {
    if (isset($_POST["film_id"])) {
      if (isset($_GET['edit'])) {
          $sql = mysqli_query($link, "UPDATE `films` SET
            `film_id` = '{$_POST['film_id']}',
            `name` = '{$_POST['name']}',
            `year` = '{$_POST['year']}',
            `genre` = '{$_POST['genre']}',
            `country` = '{$_POST['country']}',
            `director` = '{$_POST['director']}'
            `budget` = '{$_POST['budget']}'
            `role` = '{$_POST['role']}'
            `time` = '{$_POST['time']}'
            `age` = '{$_POST['age']}'
            `synopsis` = '{$_POST['synopsis']}'
            WHERE `film_id` = {$_GET['edit']}");
      } else {
          $sql = mysqli_query($link, "INSERT INTO `films` (`film_id`, `name`, `year`, `genre`, `country`, `director`, `budget`, `role`, `time`, `age`, `synopsis`) VALUES ('{$_POST['film_id']}', '{$_POST['name']}', '{$_POST['year']}', '{$_POST['genre']}', '{$_POST['country']}', '{$_POST['director']}', '{$_POST['budget']}', '{$_POST['role']}', '{$_POST['time']}', '{$_POST['age']}', '{$_POST['synopsis']}')");
    }
  }

    if ($sql) {
      echo "<script>var notification_text = \"Данные успешно изменены\"; notification(notification_text);</script>";
      header("Refresh:0; url=index.php");
    }
    else {
      echo "<script>var notification_text = \"Произошла ошибка: ". mysqli_error($link) ."\"; notification(notification_text);</script>";
      header("Refresh:0; url=index.php");
    }
  }
  ?>
  <section class="section_edit">
  <div class='gradient_black_to_dark_red'></div>
  <?php
  if ($_SESSION['auth'] == true && $_SESSION['right'] < 1) {
    echo "<b class='section_search_header'>Добавление, редактирование и удаление строк</b>";
  } else {
    echo "<b class='section_search_header'>Добавление и редактирование строк</b>";
  }
  ?>
    <form action="" method="post" class="section_edit_form">
      <table class="section_edit_form_table">
        <tr>
          <td>film_id</td>
          <td><input type="number" name="film_id" value="<?= isset($_GET['edit']) ? $product['film_id'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>name</td>
          <td><input type="text" name="name" value="<?= isset($_GET['edit']) ? $product['name'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>year</td>
          <td><input type="date" name="year" value="<?= isset($_GET['edit']) ? $product['year'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>genre</td>
          <td><input type="text" name="genre" value="<?= isset($_GET['edit']) ? $product['genre'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>country</td>
          <td><input type="text" name="country" value="<?= isset($_GET['edit']) ? $product['country'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>director</td>
          <td><input type="text" name="director" value="<?= isset($_GET['edit']) ? $product['director'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>budget</td>
          <td><input type="number" name="budget" value="<?= isset($_GET['edit']) ? $product['budget'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>role</td>
          <td><input type="text" name="role" value="<?= isset($_GET['edit']) ? $product['role'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>time</td>
          <td><input type="number" name="time" value="<?= isset($_GET['edit']) ? $product['time'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>age</td>
          <td><input type="number" name="age" value="<?= isset($_GET['edit']) ? $product['age'] : ''; ?>"></td>
        </tr>
        <tr>
          <td>synopsis</td>
          <td><input type="text" name="synopsis" value="<?= isset($_GET['edit']) ? $product['synopsis'] : ''; ?>"></td>
        </tr>
        <tr>
          <td></td>
          <td><button type="submit" class="login_button">Продолжить</button></td>
        </tr>
      </table>
    </form>
</section>

    <section class="section_info">
      <div class="gradient_dark_red_to_black"></div>
      <b class="section_carousel_header">Все данные</b>
      <table class="section_info_table"><tr>
    <?php
    $result = mysqli_query($link, "SHOW COLUMNS FROM films");
    while($row = mysqli_fetch_array($result))
      if ($row['Field'] != 'budget' &&
          $row['Field'] != 'time' &&
          $row['Field'] != 'age' &&
          $row['Field'] != 'role' &&
          $row['Field'] != 'synopsis' &&
          $row['Field'] != 'photo')
        echo "<td>". $row['Field'] ."</td>";

    if ($_SESSION['auth'] == true && $_SESSION['right'] < 2)
      echo "<td>Действия</td></tr>";

    $sql = mysqli_query($link, 'SELECT * FROM `films`');
    while ($result = mysqli_fetch_array($sql)) {

      echo '<tr>';
      for ($i = 0; $i < mysqli_field_count($link) - 6; $i++)
        echo "<td>". $result[$i] ."</td>";

      if ($_SESSION['auth'] == true && $_SESSION['right'] == 0) {
        echo "<td><a href='?del={$result['film_id']}'><button class='section_info_table_button'>Удалить</button></a></br></br>" .
                "<a href='?edit={$result['film_id']}'><button class='section_info_table_button'>Изменить</button></a></td></tr>";
      } else if ($_SESSION['auth'] == true && $_SESSION['right'] == 1)
        echo "<td><a href='?edit={$result['film_id']}'><button class='section_info_table_button'>Изменить</button></a></td></tr>";

    }
    echo '</table>';
    }
    ?>
        <div id="test-popup" class="white-popup mfp-with-anim mfp-hide auth_modal">
          <div class="auth_modal_header">Вход в личный аккаунт</div>
          <div class="auth_modal_body">
            <form method="POST" id="auth_form" action="../admin/auth.php">
              <input class="auth_form_input" type="text" id="login" name="login" placeholder="Имя пользователя" size="20" /><br>
              <input class="auth_form_input" type="password" id="password" name="password" placeholder="Пароль" size="20" /><br>
              <input type="submit" name="submit" value="Войти" class="auth_form_button">
            </form>
          <div class="auth_modal_footer"></div>
          </div>
        </div>

    <footer class="d-flex justify-content-around align-items-center">
      <div class="footer_prod">makara prod.</div>
      <img id="toTop" src="../img/favicon.svg" class="footer_logo_img">
    </footer>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-2.2.0.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
    <script type="text/javascript" src="ajax.js"></script>
    <script type="text/javascript" src="./slick/slick.js" ></script>

  </body>
</html>
<!-- Clamp -->
