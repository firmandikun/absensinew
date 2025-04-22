<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/user.php';

switch (@$_GET['action']){
  case 'dropdown':
  
    if (empty($_POST['lokasi'])) {
      $lokasi = '';
    }else {
      $lokasi = anti_injection($_POST['lokasi']);
    }
  
  $query_pegawai = "SELECT user_id,nama_lengkap FROM user WHERE lokasi_id='$lokasi'";
  $result_pegawai = $connection->query($query_pegawai);
  if($result_pegawai->num_rows > 0) {
    while($data_pegawai = $result_pegawai->fetch_assoc()){
      echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
    }
  }else{
    echo'<option value="">Data tidak ditemukan</option>';
  }
  

  /** Set Active */
break;
case 'setactive':
  $id = htmlentities($_POST['id']);
  $status = htmlentities($_POST['status']);
  $update="UPDATE overtime SET status='$status' WHERE overtime_id='$id'";
  if($connection->query($update) === false) { 
    echo 'error';
    die($connection->error.__LINE__); 
  }
  else{
    echo'success';
  }

break;
}}