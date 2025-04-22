<?php
require_once'../../sw-library/sw-config.php';
require_once'../../sw-library/sw-function.php';
require_once'../../module/oauth/user.php';
require_once'../../sw-library/csrf.php';
switch (@$_GET['action']){

  case 'update':
  $error = array();
      if (empty($_POST['nip'])) {
        $error[] = 'NIP tidak boleh kosong';
      } else {
        $nip = anti_injection($_POST['nip']);
      }
  
      if (empty($_POST['nama_lengkap'])) {
          $error[] = 'Nama Lengkap tidak boleh kosong';
        } else {
          $nama_lengkap = anti_injection($_POST['nama_lengkap']);
      }
  
      if (empty($_POST['tempat_lahir'])) {
        $error[] = 'Tempat Lahir tidak boleh kosong';
      } else {
        $tempat_lahir = anti_injection($_POST['tempat_lahir']);
      }
  
      if (empty($_POST['tanggal_lahir'])) {
          $error[] = 'Tanggal Lahir tidak boleh kosong';
        } else {
          $tanggal_lahir = date('Y-m-d', strtotime($_POST['tanggal_lahir']));
      }
  
      if (empty($_POST['jenis_kelamin'])) {
        $error[] = 'Jenis Kelamin tidak boleh kosong';
      } else {
        $jenis_kelamin = anti_injection($_POST['jenis_kelamin']);
      }
  
      if (empty($_POST['telp'])) {
        $error[] = 'No. Telp tidak boleh kosong';
      } else {
        if (preg_match("/^\+(?:[0-9] ?){6,14}[0-9]$/", $_POST['telp'])) {
          $error[] = 'No. Telp yang Anda masukkan tidak diizinkan';
        }else{
          $telp = anti_injection($_POST['telp']);
        }
      }
  
      if (empty($_POST['alamat'])) {
        $error[] = 'Alamat Lengkap tidak boleh kosong';
      } else {
        $alamat = anti_injection($_POST['alamat']);
      }
  
      if (empty($_POST['lokasi'])) {
        $error[] = 'Lokasi Kerja tidak boleh kosong';
      } else {
        $lokasi = anti_injection($_POST['lokasi']);
      }
  
      if (empty($_POST['posisi'])) {
        $error[] = 'Posisi tidak boleh kosong';
      } else {
        $posisi = anti_injection($_POST['posisi']);
      }
  
      
    if (empty($error)) {
      $update="UPDATE user SET nip='$nip',
              email='$email',
              nama_lengkap='$nama_lengkap',
              tempat_lahir='$tempat_lahir',
              tanggal_lahir='$tanggal_lahir',
              jenis_kelamin='$jenis_kelamin',
              telp='$telp',
              alamat='$alamat',
              lokasi_id='$lokasi',
              posisi_id='$posisi' WHERE user_id='$data_user[user_id]'"; 
      if($connection->query($update) === false) { 
          die($connection->error.__LINE__); 
          echo'Data tidak berhasil disimpan!';
      } else{
          echo'success';
      }}
    else{
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }
    
break;
}