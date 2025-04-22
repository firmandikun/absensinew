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
/** Load details datatable  */
case'load-data':
$id       = anti_injection(epm_decode($_GET['id']));
$query = "SELECT * FROM jam_kerja WHERE active='Y' AND jam_kerja_master_id='$id'";
$result = $connection->query($query);
if($result->num_rows > 0){
echo'
<table class="table align-items-center table-striped">
  <thead class="thead-light">
    <tr>
      <th>Hari</th>
      <th>Jam Masuk</th>
      <th>Batas Telat</th>
      <th>Jam Pulang</th>
    </tr>
  </thead>
  <tbody>';
while ($data = $result->fetch_assoc()){
  echo'
  <tr>
      <td>'.strip_tags($data['hari']).'</td>
      <td>'.strip_tags($data['jam_masuk']).'</td>
      <td>'.strip_tags($data['jam_telat']).'</td>
      <td>'.strip_tags($data['jam_pulang']).'</td>
    </tr>';
}
echo'
</tbody>
</table>';
}else{
  echo'<div class="text-center">Jam kerja tidak ditemukan!</div>';
}

/* ---------- ADD  ---------- */
break;
case 'add':
    $error = array();

    if (empty($_POST['nama'])) {
      $error[] = 'Nama tidak boleh kosong';
    } else {
      $nama = strip_tags($_POST['nama']);
    }

  $item = $_POST['item'];

if (empty($error)) {
  $query="SELECT jam_kerja_master_id FROM jam_kerja_master WHERE nama='$nama'";
  $result= $connection->query($query);
  if(!$result ->num_rows >0){

  $query_master   = "SELECT MAX(jam_kerja_master_id) AS max_kerja_master_id FROM jam_kerja_master";
  $result_master  = $connection->query($query_master);
  $data_master    = $result_master->fetch_assoc();
  $jam_kerja_master_id = $data_master['max_kerja_master_id'] + 1;

    for($i = 0; $i < sizeof($item); $i++){
      $hari         = htmlentities($item[$i]);
      $jam_masuk    = $_POST["jam_masuk"][$i];
      $jam_telat    = $_POST["jam_telat"][$i];
      $jam_pulang   = $_POST["jam_pulang"][$i];
      $active       = $_POST["active"][$i];

      $add ="INSERT INTO  jam_kerja (jam_kerja_master_id,
                          hari,
                          jam_masuk,
                          jam_telat,
                          jam_pulang,
                          active) values('$jam_kerja_master_id',
                          '$hari',
                          '$jam_masuk',
                          '$jam_telat',
                          '$jam_pulang',
                          '$active')"; 
      $connection->query($add); 
    }
   
      $add_master ="INSERT INTO  jam_kerja_master (jam_kerja_master_id,
                          user_id,
                          nama) values('$jam_kerja_master_id',
                          '0',
                          '$nama')"; 
      if($connection->query($add_master) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
      } else{
        echo'success'; 
      }  
  }
  else{
    echo'Sepertinya  Waktu "'.$nama.'" sudah ada!';
  }}
  else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
  }


/* -------------- Update ----------*/
break;
case 'update':
$error = array();

if (empty($_POST['master_id'])) {
  $error[] = 'ID tidak boleh kosong';
} else {
  $master_id = epm_decode($_POST['master_id']); 
}

if (empty($_POST['nama'])) {
  $error[] = 'Nama tidak boleh kosong';
} else {
  $nama = strip_tags($_POST['nama']);
}

$item = $_POST['item'];
if (empty($error)) {
for($i = 0; $i < sizeof($item); $i++){
  $id           = strip_tags($_POST["id"][$i]);
  $jam_masuk    = strip_tags($_POST["jam_masuk"][$i]);
  $jam_telat    = strip_tags($_POST["jam_telat"][$i]);
  $jam_pulang   = strip_tags($_POST["jam_pulang"][$i]);
  $active       = strip_tags($_POST["active"][$i]);

  $update_jam_kerja ="UPDATE jam_kerja SET jam_masuk='$jam_masuk',
                      jam_telat='$jam_telat',
                      jam_pulang='$jam_pulang',
                      active='$active' WHERE jam_kerja_id='$id'";
  $connection->query($update_jam_kerja); 
}
// echo'success';
  $update_master ="UPDATE jam_kerja_master SET nama='$nama' WHERE jam_kerja_master_id='$master_id'";  
  if($connection->query($update_master) === false) { 
    die($connection->error.__LINE__); 
    echo'Data tidak berhasil disimpan!';
  } else{
    echo'success'; 
  }  
}
else{           
  foreach ($error as $key => $values) {            
    echo"$values\n";
  }
}

/** --------- Set Active Lokasi ------- */

break;
case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE lokasi SET lokasi_status='$active' WHERE lokasi_id='$id'";
  if($connection->query($update) === false) { 
    echo 'error';
    die($connection->error.__LINE__); 
  }
  else{
    echo'success';
  }
  
/* --------------- Delete ------------*/
break;
case 'delete':
    $id       = anti_injection(epm_decode($_POST['id']));
    /* Script Delete Data ------------*/
      $deleted_master  = "DELETE FROM jam_kerja_master WHERE jam_kerja_master_id='$id'";
      $deleted = "DELETE FROM jam_kerja WHERE jam_kerja_master_id='$id'";
      if($connection->query($deleted_master) === true) {
        echo'success';
        $connection->query($deleted);
      } else { 
        //tidak berhasil
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
      }

break;
}}