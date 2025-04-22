<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';

switch (@$_GET['action']){
/** Baca notifikasi */
case'notifikasi':
  $error = array();
  if (empty($_POST['id'])) {
    $error[] = 'Nama tidak boleh kosong';
  } else {
    $id = anti_injection($_POST['id']);
  }
  if (empty($error)) {
    $update="UPDATE notifikasi SET status='Y' WHERE notifikasi_id='$id'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo'success';
        }
      }else{           
          foreach ($error as $key => $values) {            
            echo"$values\n";
          }
      }
break;
}}