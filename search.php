<?php
session_start();
$link = mysqli_connect($_SESSION['host'], $_SESSION['user'], $_SESSION['password'], $_SESSION['db_name']);

$query = $_POST['query'];

if (isset($query)) {
  $query = trim($query);
  $query = mysqli_real_escape_string($link, $query);
  $query = htmlspecialchars($query);

  if (!empty($query)) {
    if (strlen($query) < 128) {

      $q = "SELECT * FROM `films` WHERE
        `film_id` LIKE '%$query%' OR
        `name` LIKE '%$query%' OR
        `year` LIKE '%$query%' OR
        `genre` LIKE '%$query%' OR
        `country` LIKE '%$query%' OR
        `director` LIKE '%$query%' OR
        `budget` LIKE '%$query%' OR
        `role` LIKE '%$query%' OR
        `time` LIKE '%$query%' OR
        `age` LIKE '%$query%' OR
        `synopsis` LIKE '%$query%'";

        function role($data) {
          $role = explode(",", $data['role']);
          for ($i = 0; $i < count($role); $i++) {
            echo $role[$i]."<br/>";
          };
        };

      $result = mysqli_query($link, $q);

      if (mysqli_affected_rows($link) > 0) {

        $num = mysqli_num_rows($result);
        switch ($num) {
          case 0:
            break;
          case 1:
            $num = "одно совпадение";
            break;
          case 2:
            $num = "два совпадения";
            break;
          case 3:
            $num = "три совпадения";
            break;
          case 4:
            $num = "четыре совпадения";
            break;
          case 5:
            $num = "пять совпадений";
            break;
          default:
            $num += " совпадений";
            break;
        }

        $sql = mysqli_query($link, $q);
        ?>
        <b class="section_search_header">По вашему запросу найдено <?= $num ?></b>
        <div class="search_divider"></div>
        <?php
        while ($data = mysqli_fetch_assoc($sql)) {
          $date = date_create($data['year']);
          $date_ = date_format($date, 'Y');
          $photo = explode(",", $data['photo']);
          ?>
          <section class="section_search pb-1">
            <div class="d-flex justify-content-around">
             <div class="d-flex align-items-center">
               <img class="section_carousel_photo" src="<?= $photo[0] ?>">
             </div>
             <div class="d-flex align-items-center">
               <table class="section_search_table_text" cellspacing="5" cellpadding="5">
                 <tr>
                  <td colspan="3"><h2 class="section_search_table_header"><?= $data['name']." (".$date_.")" ?></h2></td>
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
                 <tr>
                  <td colspan="3">
                    <div class="section_search_table_text_synopsis pr-05">
                      <?= $data['synopsis'] ?>
                    </div>
                  </td>
                 </tr>
               </table>
             </div>
            </div>
          </section>
          <?php
        }
        ?>
        <div class="gradient_dark_red_to_black"></div>
        <?php
      } else echo "<script>var notification_text = \"По вашему запросу ничего не найдено\"; notification(notification_text);</script>";
    } else echo "<script>var notification_text = \"Слишком длинный поисковый запрос\"; notification(notification_text);</script>";
  } else echo "<script>var notification_text = \"Задан пустой поисковый запрос\"; notification(notification_text);</script>";
}
?>
