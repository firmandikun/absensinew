<?php
require_once'../../sw-library/sw-config.php';
require_once'../../sw-library/sw-function.php';
require_once'../../sw-library/csrf.php';
require_once'../../module/oauth/user.php';
switch (@$_GET['action']){
/** Load details datatable  */
case'load-jam-kerja':
$id       = anti_injection($_GET['id']);
$query = "SELECT * FROM jam_kerja WHERE active='Y' AND jam_kerja_master_id='$id'";
$result = $connection->query($query);
if($result->num_rows > 0){
echo'
<div class="table-responsive">
<table class="table align-items-center">
  <thead class="thead-light">
    <tr>
      <th>Hari</th>
      <th>Masuk</th>
      <th>Toleransi</th>
      <th>Pulang</th>
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
</table>
</div>';
}else{
  echo'<div class="text-center">Jam kerja tidak ditemukan!</div>';
}


/** Load Data Jam kerja pegawai */
break;
case 'load-data':
echo'
<div class="accordion">';
$query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,user_jam_kerja.user_jam_kerja_id,user_jam_kerja.active,jam_kerja_master.jam_kerja_master_id,jam_kerja_master.nama FROM user_jam_kerja
LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id WHERE user_jam_kerja.user_id='$data_user[user_id]'";
$result_jam_kerja = $connection->query($query_jam_kerja);
if($result_jam_kerja->num_rows > 0){
  while($data_jam_kerja = $result_jam_kerja->fetch_assoc()){

  $query_hari ="SELECT hari,jam_masuk,jam_telat,jam_pulang FROM jam_kerja WHERE jam_kerja_master_id='$data_jam_kerja[jam_kerja_master_id]' AND active='Y'";
  $result_hari = $connection->query($query_hari);
  if($result_hari->num_rows > 0){
      $response = array();
      $response["data"] = array();
      while ($data_hari = $result_hari->fetch_assoc()){
        $data_row['hari'] = $data_hari['hari'];
        $data_row['jam_masuk'] = $data_hari['jam_masuk'];
        $data_row['jam_telat'] = $data_hari['jam_telat'];
        $data_row['jam_pulang'] = $data_hari['jam_pulang'];
        array_push($response['data'], $data_row);
      }
      $response = json_encode($response);
      $data = json_decode($response, true);
  }else{
    $response["message"]="tidak ada data";
  }

  if($data_jam_kerja['active'] =='Y'){
    $active = '
    <input type="checkbox" class="btn-active-jam custom-control-input switch-primary active'.$data_jam_kerja['user_jam_kerja_id'].'"  data-id="'.$data_jam_kerja['user_jam_kerja_id'].'" data-active="Y"  id="customSwitch'.$data_jam_kerja['user_jam_kerja_id'].'" checked="">';
    $title = 'text-primary';
    $collapse='show';
}else{
     $active = '<input type="checkbox" class="btn-active-jam custom-control-input switch-primary active'.$data_jam_kerja['user_jam_kerja_id'].'" data-id="'.$data_jam_kerja['user_jam_kerja_id'].'" data-active="N"  id="customSwitch'.$data_jam_kerja['user_jam_kerja_id'].'">';
     $title = 'text-muted';
     $collapse='';
}

echo'
<div class="card border-0 mb-3">
        <div class="card-body">
            <div class="row align-items-center">
                <div class="col" data-toggle="collapse" data-target="#collapse'.$data_jam_kerja['user_jam_kerja_id'].'" aria-expanded="true" aria-controls="collapse'.$data_jam_kerja['user_jam_kerja_id'].'">
                    <h6 class="mb-1 title '.$title.'">'.strip_tags($data_jam_kerja['nama']).'</h6>
                    <p class="small text-secondary">';
                    foreach ($data['data'] as $day) {
                      echo''.$day['hari'].', ';
                    }
                    echo'</p>
                </div>
                <div class="col-auto pl-0">
                  <button type="button" class="close text-danger btn-delete" data-id="'.epm_encode($data_jam_kerja['user_jam_kerja_id']).'" data-name="'.strip_tags($data_jam_kerja['nama']).'">
                    <span class="material-icons">delete</span>
                  </button>
                  <div class="custom-control custom-switch mr-4">
                      '.$active.'
                      <label class="custom-control-label" for="customSwitch'.$data_jam_kerja['user_jam_kerja_id'].'"></label>
                  </div>
                </div>

            </div>
        </div>
        <!-- Open collapse -->
          <div id="collapse'.$data_jam_kerja['user_jam_kerja_id'].'" class="collapse '.$collapse.'" aria-labelledby="heading'.$data_jam_kerja['user_jam_kerja_id'].'" >
              <div class="card-body">
                <div class="table-responsive">
                  <table class="table align-items-center">
                    <thead>
                      <tr>
                        <th>Hari</th>
                        <th>Masuk</th>
                        <th>Toleransi</th>
                        <th>Pulang</th>
                      </tr>
                    </thead>
                    <tbody>';
              foreach ($data['data'] as $day) {
                echo'
                    <tr>
                      <td>'.strip_tags($day['hari']).'</td>
                      <td>'.strip_tags($day['jam_masuk']).'</td>
                      <td>'.strip_tags($day['jam_telat']).'</td>
                      <td>'.strip_tags($day['jam_pulang']).'</td>
                    </tr>';    
                }
              echo' </tbody>
                </table>
                </div>
              </div>
          </div>
    </div>';
  }
}else{
  echo'
  <div class="alert alert-info text-center" role="alert">
      Saat ini Bapak/Ibu <b>'.$data_user['nama_lengkap'].'</b> belum memiliki jam kerja
  </div>';
}
echo'</div>';
    
/** Tambah Master jam kerja  */
break;
case 'add':
$error = array();

  if (empty($_POST['jam_kerja_master_id'])) {
    $error[] = 'Silahkan pilih sift kerja';
  } else {
    $jam_kerja_master_id = anti_injection($_POST['jam_kerja_master_id']);
  }
  
    if (empty($error)){
        $query_jam_kerja ="SELECT user_jam_kerja_id  FROM user_jam_kerja WHERE jam_kerja_master_id='$jam_kerja_master_id' AND user_id='$data_user[user_id]'";
        $result_jam_kerja = $connection->query($query_jam_kerja);
        if(!$result_jam_kerja->num_rows > 0){
          $add ="INSERT INTO user_jam_kerja(user_id,
                          jam_kerja_master_id,
                          active) values('$data_user[user_id]',
                          '$jam_kerja_master_id',
                          'N')";
          if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
          } else{
              echo'success';
          }
        }else{
          echo'Shift kerja yang Anda pilih sudah ada!';
        }
      }else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }


    /** Set Aktifkan & Nonaktifkan Jam Kerja */
break;
case 'active':
  $id = anti_injection($_POST['id']);
  $active = anti_injection($_POST['active']);
  $update="UPDATE user_jam_kerja SET active='$active' WHERE user_jam_kerja_id='$id'";
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
    $query_master ="SELECT user_jam_kerja_id FROM user_jam_kerja WHERE active='Y' AND user_jam_kerja_id='$id'";
    $result_master = $connection->query($query_master);
      if(!$result_master->num_rows > 0){
        /* Script Delete Data ------------*/
          $deleted = "DELETE FROM user_jam_kerja WHERE active='N' AND user_jam_kerja_id='$id'";
          if($connection->query($deleted) === true){
            echo'success';
          } else { 
            //tidak berhasil
            echo'Data tidak berhasil dihapus.!';
            die($connection->error.__LINE__);
          }
      }else{
        echo 'Data Jam kerja ini aktif atau digunakan!';
      }

break;
}