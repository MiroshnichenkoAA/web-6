<?php

/**
 * Задача 6. Реализовать вход администратора с использованием
 * HTTP-авторизации для просмотра и удаления результатов.
 **/
include('connect.php');
// Пример HTTP-аутентификации.
// PHP хранит логин и пароль в суперглобальном массиве $_SERVER.
// Подробнее см. стр. 26 и 99 в учебном пособии Веб-программирование и веб-сервисы.
if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('123')) {
  header('HTTP/1.1 401 Unanthorized');
  header('WWW-Authenticate: Basic realm="My site"');
  print('<h1>401 Требуется авторизация</h1>');
  exit();
}

print('Вы успешно авторизовались и видите защищенные паролем данные.');

  $users=array();
  $powers=array();
  $power_def=array('immortal','teleport','megabrain');
  $powers_count=array();
  try{
    $get=$db->prepare("select * from form");
    $get->execute();
    $inf=$get->fetchALL();
    $get2=$db->prepare("select p_id,p_name from power");
    $get2->execute();
    $inf2=$get2->fetchALL();
    $count=$db->prepare("select count(*) from power where p_name=?");
    foreach($power_def as $pw){
      $count->execute(array($pw));
      $powers_count[]=$count->fetchAll()[0][0];
    }
  }
  catch(PDOException $e){
    print('Error: '.$e->getMessage());
    exit();
  }

  $users=$inf;
  $powers=$inf2;
?>
//Таблица
  <style>
    table{
    margin: 0 auto;
    text-align: center;
  }
  table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
 
  .error {
    border: 2px solid red;
  }
 
</style>
<body>
  <div class="table1">
    <table>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Year</th>
        <th>Sex</th>
        <th>Limb</th>
        <th>Superpowers</th>
        <th>Bio</th>
      </tr>
      <?php
      foreach($users as $user)
      { ?>
          
            <tr>
              <td> <?php print( $user['name'] );?></td>
              <td> <?php print($user['email']);?></td>
              <td> <?php print($user['year']);?></td>
              <td> <?php print($user['sex']);?></td>
              <td> <?php print($user['limbs']);?></td>
              <td>
              <?php  $user_pwrs=array(
                    "immortal"=>FALSE,
                    "megabrain"=>FALSE,
                    "teleport"=>FALSE
                );
      
                foreach($powers as $pwr){
                    if($pwr['p_id']==$user['id']){
                        if($pwr['p_name']=='immortal'){
                            $user_pwrs['immortal']=TRUE;
                        }
                        if($pwr['p_name']=='megabrain'){
                            $user_pwrs['megabrain']=TRUE;
                        }
                        if($pwr['p_name']=='teleport'){
                            $user_pwrs['teleport']=TRUE;
                        }
                    }
                }
                if($user_pwrs['immortal']){echo 'Бессмертие<br>';}
                if($user_pwrs['megabrain']){echo 'Мегамозг<br>';}
                if($user_pwrs['teleport']){echo 'Телепорт<br>';}
                ?>
              </td>
              <td><?php print($user['bio']);?></td>
              <td>
                <form method="get" action="edit.php">
                  <input name=edit_id value="<?php print( $user['id']);?>" hidden>
                  <input type="submit" value=Edit>
                </form>
              </td>
            </tr> 
            <?php
       }
      ?>
    </table>
    <?php
    printf('Кол-во пользователей с сверхспособностью "Бессмертие": %d <br>',$powers_count[0]);
    printf('Кол-во пользователей с сверхспособностью "Мегамозг": %d <br>',$powers_count[1]);
    printf('Кол-во пользователей с сверхспособностью "Телепорт": %d <br>',$powers_count[2]);
    ?>
  </div>
</body>
// *********
// Здесь нужно прочитать отправленные ранее пользователями данные и вывести в таблицу.
// Реализовать просмотр и удаление всех данных.
// *********
