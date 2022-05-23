<style>
  .form1{
    max-width: 960px;
    text-align: left;
    margin: 0 auto;
  }
  .error {
    border: 4px solid red;
  }
  
 
</style>
<body>
<?php
if (!empty($messages)) {
  print('<div id="messages">');
  // Выводим все сообщения.
  foreach ($messages as $message) {
    print($message);
  }
  print('</div>');
}
?>
  <div class="form1">
  <form action="edit.php" method="POST">
    <label> ФИО </label> <br>
    <input name="name" <?php if ($errors_ar['name']) {print 'class="error"';} ?> value="<?php print $values['name']; ?>" /> <br>
    <label> Почта </label> <br>
    <input name="email" type="email" <?php if ($errors_ar['email']) {print 'class="error"';} ?> value="<?php print $values['email']; ?>"/> <br>
    <label> Год рождения </label> <br>
    <select name="year" <?php if ($errors_ar['year']) {print 'class="error"';} ?>>
      <option value="Выбрать">Выбрать</option>
    <?php
        for($i=1890;$i<=2022;$i++){
          if($values['year']==$i){
            printf("<option value=%d selected>%d год</option>",$i,$i);
          }
          else{
            printf("<option value=%d>%d год</option>",$i,$i);
          }
        }
    ?>
    </select> <br>
    <!--<input name="year" type="date" /> <br>-->
    <label> Ваш пол </label> <br>
    <div <?php //if ($errors_ar['sex']) {print 'class="error"';} ?>>
      <input name="sex" type="radio" value="M" <?php if($values['sex']=="M") {print 'checked';} ?>/> Мужчина
      <input name="sex" type="radio" value="W" <?php if($values['sex']=="W") {print 'checked';} ?>/> Женщина
    </div>
    <label> Сколько у вас конечностей </label> <br>
    <div <?php //if ($errors_ar['limbs']) {print 'class="error"';} ?>>
      <input name="limbs" type="radio" value="1" <?php if($values['limbs']=="1") {print 'checked';} ?>/> 1 
      <input name="limbs" type="radio" value="2" <?php if($values['limbs']=="2") {print 'checked';} ?>/> 2 
      <input name="limbs" type="radio" value="3" <?php if($values['limbs']=="3") {print 'checked';} ?>/> 3 
      <input name="limbs" type="radio" value="4" <?php if($values['limbs']=="4") {print 'checked';} ?>/> 4 
    </div>
    <label> Выберите суперспособности </label> <br>
    <select name="power[]" size="3" multiple <?php //if ($errors_ar['powers']) {print 'class="error"';} ?>>
      <option value="immortal" <?php if($values['immortal']==1){print 'selected';} ?>>Бессмертие</option>
      <option value="megabrain" <?php if($values['megabrain']==1){print 'selected';} ?>>Мегамозг</option>
      <option value="teleport" <?php if($values['teleport']==1){print 'selected';} ?>>Телепорт</option>
    </select> <br>
    <label> Краткая биография </label> <br>
    <textarea name="bio" rows="10" cols="15"><?php print $values['bio']; ?></textarea> <br>
    <input name='dd' hidden value=<?php print($_GET['edit_id']);?>>
    <input type="submit" name='edit' value="Edit"/>
    <input type="submit" name='del' value="Delete"/>
  </form>
    <p>
    
    <a href='admin.php' class="button">Назад</a>

    </p>
  </div>
</body>