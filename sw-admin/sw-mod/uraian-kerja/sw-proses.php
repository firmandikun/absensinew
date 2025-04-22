<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:../login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/user.php';

switch (@$_GET['action']){
case 'dropdown':
  if (empty($_POST['lokasi'])) {
    $lokasi = '-';
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

    
break;
case 'delete':
  $id       = anti_injection(epm_decode($_POST['id']));

  $query ="SELECT files FROM uraian_kerja WHERE uraian_kerja_id='$id'";
  $result = $connection->query($query);
  if($result->num_rows > 0){
    $data = $result->fetch_assoc();
    if(file_exists("../../../sw-content/laporan-kerja/".$data['files']."")){
      unlink ('../../../sw-content/laporan-kerja/'.$data['files'].'');
    }
  }

  $deleted  = "DELETE FROM uraian_kerja WHERE uraian_kerja_id='$id'";
  if($connection->query($deleted) === true) {
      echo'success';
    } else { 
      //tidak berhasil
      echo'Data tidak berhasil dihapus.!';
      die($connection->error.__LINE__);
  }

break;
}}