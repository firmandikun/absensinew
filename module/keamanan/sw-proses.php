<?php
require_once'../../sw-library/sw-config.php';
require_once'../../sw-library/sw-function.php';
require_once'../../module/oauth/user.php';

$iB 			       = getBrowser();
$browser 		     = $iB['name'].' '.$iB['version'];
$ip 		         = $_SERVER['REMOTE_ADDR'];
$time_online     = time();

switch (@$_GET['action']){

  case 'update':
  $error = array();
    if (empty($_POST['email'])) {
      $error[] = 'Email tidak boleh kosong';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $error[] = "Email yang Anda masukan tidak valid"; 
        }else{
          $email = htmlentities(strip_tags($_POST['email']));
        }
    }

    if (empty($_POST['password_lama'])) {
      $error[] = 'Password lama tidak boleh kosong';
    } else {
      $password_lama = htmlentities(strip_tags($_POST['password_lama']));
      //$password_lama = password_hash($password_lama,PASSWORD_DEFAULT);
    }

    if (empty($_POST['password_baru'])) {
      $error[] = 'Password baru tidak boleh kosong';
    } else {
      $password_baru = htmlentities(strip_tags($_POST['password_baru']));
      $password_baru = password_hash($password_baru,PASSWORD_DEFAULT);
    }
  
      
    if (empty($error)) {
      if(password_verify($password_lama,$data_user['password'])) {

        $update="UPDATE user SET email='$email',
                password='$password_baru' WHERE user_id='$data_user[user_id]'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo'success';
        }
    }else{
      echo'Password lama yang Anda masukkan tidak sesuai!';
    }
  }else{
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }

break;
}