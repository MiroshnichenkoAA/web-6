<?php

require('connect.php');
$pass_hash=array();
try{
  $get=$db->prepare("select password from admins where admin_name=?");
  $get->execute(array('admin'));
  $pass_hash=$get->fetchAll()[0][0];
}
catch(PDOException $e){
  print('Error: '.$e->getMessage());
}
if (empty($_SERVER['PHP_AUTH_USER']) ||
      empty($_SERVER['PHP_AUTH_PW']) ||
      $_SERVER['PHP_AUTH_USER'] != 'admin' ||
      md5($_SERVER['PHP_AUTH_PW']) != $pass_hash) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}
if(empty($_GET['edit_id'])){
  header('Location: admin.php');
}
header('Content-Type: text/html; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'GET') {
  $messages = array();
  if (!empty($_COOKIE['save'])) {
    setcookie('save', '', 100000);
    $messages[] = 'Спасибо, результаты сохранены.';
    setcookie('name_value', '', 100000);
    setcookie('email_value', '', 100000);
    setcookie('year_value', '', 100000);
    setcookie('sex_value', '', 100000);
    setcookie('limbs_value', '', 100000);
    setcookie('bio_value', '', 100000);
    setcookie('immortal_value', '', 100000);
    setcookie('megabrain_value', '', 100000);
    setcookie('teleport_value', '', 100000);
    setcookie('checkbox_value', '', 100000);
  }
  //Ошибки
  
  $errors_ar = array();
  $error=FALSE;
  
  $errors_ar['name'] = !empty($_COOKIE['name_error']);
  $errors_ar['email'] = !empty($_COOKIE['email_error']);
  $errors_ar['year'] = !empty($_COOKIE['year_error']);
  $errors_ar['sex'] = !empty($_COOKIE['sex_error']);
  $errors_ar['limbs'] = !empty($_COOKIE['limbs_error']);
  $errors_ar['power'] = !empty($_COOKIE['power_error']);
  $errors_ar['checkbox'] = !empty($_COOKIE['checkbox_error']);
  if (!empty($errors_ar['name'])) {
    setcookie('name_error', '', 100000);
    $messages[] = '<div class="error">Заполните имя.</div>';
    $error=TRUE;
  }
  if ($errors_ar['email']) {
    setcookie('email_error', '', 100000);
    $messages[] = '<div class="error">Заполните или исправьте почту.</div>';
    $error=TRUE;
  }
  if ($errors_ar['year']) {
    setcookie('year_error', '', 100000);
    $messages[] = '<div class="error">Выберите год рождения.</div>';
    $error=TRUE;
  }
  if ($errors_ar['sex']) {
    setcookie('sex_error', '', 100000);
    $messages[] = '<div class="error">Выберите пол.</div>';
    $error=TRUE;
  }
  if ($errors_ar['limbs']) {
    setcookie('limbs_error', '', 100000);
    $messages[] = '<div class="error">Выберите сколько у вас конечностей.</div>';
    $error=TRUE;
  }
  if ($errors_ar['power']) {
    setcookie('power_error', '', 100000);
    $messages[] = '<div class="error">Выберите хотя бы одну суперспособность.</div>';
    $error=TRUE;
  }
  $values = array();
  $values['immortal']=0;
  $values['megabrain']=0;
  $values['teleport']=0;
 
  include('connect.php');
  try{
      $id=$_GET['edit_id'];
      $get=$db->prepare("select * from form where id=?");
      $get->bindParam(1,$id);
      $get->execute();
      $inf=$get->fetchALL();
      $values['name']=$inf[0]['name'];
      $values['email']=$inf[0]['email'];
      $values['year']=$inf[0]['year'];
      $values['sex']=$inf[0]['sex'];
      $values['limbs']=$inf[0]['limbs'];
      $values['bio']=$inf[0]['bio'];
      $get2=$db->prepare("select p_name from power where p_id=?");
      $get2->bindParam(1,$id);
      $get2->execute();
      $inf2=$get2->fetchALL();
      for($i=0;$i<count($inf2);$i++){
        if($inf2[$i]['p_name']=='immortal'){
          $values['immortal']=1;
        }
        if($inf2[$i]['p_name']=='megabrain'){
          $values['megabrain']=1;
        }
        if($inf2[$i]['p_name']=='teleport'){
          $values['teleport']=1;
        }
      }
  }
  catch(PDOException $e){
      print('Error: '.$e->getMessage());
      exit();
  }
  include('form.php');
}
else {
  if(!empty($_POST['edit'])){
    $id=$_POST['dd'];
    $name=$_POST['name'];
    $mail=$_POST['email'];
    $year=$_POST['year'];
    $sex=$_POST['sex'];
    $limb=$_POST['limbs'];
    $pwrs=$_POST['power'];
    $bio=$_POST['bio'];
    $errors = FALSE;
    if (empty($name)) {
        setcookie('name_error', '1', time() + 24*60 * 60);
        setcookie('name_value', '', 100000);
        $errors = TRUE;
    }
    //проверка почты
    if (empty($mail) or !filter_var($mail,FILTER_VALIDATE_EMAIL)) {
        setcookie('email_error', '1', time() + 24*60 * 60);
        setcookie('email_value', '', 100000);
        $errors = TRUE;
    }
    //проверка года
    if ($year=='Выбрать') {
        setcookie('year_error', '1', time() + 24 * 60 * 60);
        setcookie('year_value', '', 100000);
        $errors = TRUE;
    }
    //проверка пола
    if (!isset($sex)) {
        setcookie('sex_error', '1', time() + 24 * 60 * 60);
        setcookie('sex_value', '', 100000);
        $errors = TRUE;
    }
    //проверка конечностей
    if (!isset($limb)) {
        setcookie('limbs_error', '1', time() + 24 * 60 * 60);
        setcookie('limbs_value', '', 100000);
        $errors = TRUE;
    }
    //проверка суперспособностей
    if (!isset($pwrs)) {
        setcookie('power_error', '1', time() + 24 * 60 * 60);
        $errors = TRUE;
    }
    if ($errors) {
        setcookie('save','',100000);
        header('Location: edit.php?edit_id='.$id);
    }
    else {
        setcookie('name_error', '', 100000);
        setcookie('email_error', '', 100000);
        setcookie('year_error', '', 100000);
        setcookie('sex_error', '', 100000);
        setcookie('limbs_error', '', 100000);
        setcookie('power_error', '', 100000);
        setcookie('bio_error', '', 100000);
        setcookie('checkbox_error', '', 100000);
    }
    include('connect.php');
    if(!$errors){
        $upd=$db->prepare("update form set name=:name,email=:mail,year=:date,sex=:sex,limbs=:limb,bio=:bio where id=:id");
        $cols=array(
        ':name'=>$name,
        ':mail'=>$mail,
        ':date'=>$year,
        ':sex'=>$sex,
        ':limb'=>$limb,
        ':bio'=>$bio
        );
        foreach($cols as $k=>&$v){
        $upd->bindParam($k,$v);
        }
        $upd->bindParam(':id',$id);
        $upd->execute();
        $del=$db->prepare("delete from power where p_id=?");
        $del->execute(array($id));
        $upd1=$db->prepare("insert into power set p_name=:power,p_id=:id");
        $upd1->bindParam(':id',$id);
        foreach($pwrs as $pwr){
        $upd1->bindParam(':power',$pwr);
        $upd1->execute();
        }
    }
    
    if(!$errors){
      setcookie('save', '1');
    }
    header('Location: edit.php?edit_id='.$id);
  }
  else {
    $id=$_POST['dd'];
    include('connect.php');
    try {
      $del=$db->prepare("delete from power where p_id=?");
      $del->execute(array($id));
      $stmt = $db->prepare("delete from form where id=?");
      $stmt -> execute(array($id));
    }
    catch(PDOException $e){
      print('Error : ' . $e->getMessage());
    exit();
    }
    setcookie('del','1');
    setcookie('del_user',$id);
    header('Location: admin.php');
  }

}