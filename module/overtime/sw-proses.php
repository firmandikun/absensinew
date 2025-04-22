<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../sw-library/csrf.php';
  require_once'../../module/oauth/user.php';
  $overtime = '';
  if(!empty($_COOKIE['overtime'])){ $overtime = htmlentities($_COOKIE['overtime']);}

switch (@$_GET['action']){
case 'data-overtime':

if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND MONTH(tanggal_in) ='$month' AND YEAR(tanggal_in) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND tanggal_in BETWEEN '$mulai' AND '$selesai'";
}

$query_histori ="SELECT overtime_id,tanggal_in,tanggal_out,absen_in,absen_out,keterangan,status FROM overtime WHERE user_id='$data_user[user_id]' $filter ORDER BY overtime_id DESC LIMIT 10";
$result_histori = $connection->query($query_histori);
if($result_histori->num_rows > 0){
  while ($data_histori= $result_histori->fetch_assoc()){
    $overtime_id = anti_injection($data_histori['overtime_id']);
    
    if($data_histori['status'] ==1){
      $status_overtime='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_histori['status'] =='Y'){
      $status_overtime='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_histori['status'] =='N'){
      $status_overtime='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status_overtime='';
    }
    
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_histori['tanggal_in']).' s/d '.tanggal_ind($data_histori['tanggal_out']).' | </span>'.$status_overtime.'</p>
            </div>
            
        </div>

        <div class="row align-items-center">
            <div class="col-5 align-self-center">
                <small class="mb-1">CHECK IN</small>
                <p class="text-info">'.$data_histori['absen_in'].'</p>
            </div>
            <div class="col align-self-center">
                <small class="mb-1">CHECK OUT</small>';
                if($data_histori['absen_out']=='00:00:00'){
                  echo'<p>-</p>';
                }else{
                  echo'<p class="text-success">'.$data_histori['absen_out'].'</p>';
                }
              echo'
            </div>

            <div class="col-auto align-self-center">
                <a href="#" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_histori['overtime_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>

                <a href="#" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['overtime_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['overtime_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_histori['overtime_id'].'">
          <p class="text-secondary">'.strip_tags($data_histori['keterangan']).'</p>
        </div>

    </div>
</div>';
}
  echo'
  <div class="text-center show_more_main'.$overtime_id.' mt-4">
      <button data-id="'.$overtime_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';


}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Overtime masih kosong</div>';
}

/** Moad More Overtime */
break;
case 'data-overtime-load':

if (empty($_POST['mulai']) OR empty($_POST['selesai'])){ 
  $filter = "AND MONTH(tanggal_in) ='$month' AND YEAR(tanggal_in) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_POST['mulai']));
  $selesai = date('Y-m-d', strtotime($_POST['selesai']));
  $filter = "AND tanggal_in BETWEEN '$mulai' AND '$selesai'";
}

$id = anti_injection($_POST['id']);
$query_count    ="SELECT COUNT(overtime_id) AS total FROM overtime WHERE overtime_id < $id $filter ORDER BY overtime_id DESC";
$result_count   = $connection->query($query_count);
$data_count     = $result_count->fetch_assoc();
$totalRowCount  = $data_count['total'];

$showLimit = 10;
$query_histori ="SELECT overtime_id,tanggal_in,tanggal_out,absen_in,absen_out,keterangan,status FROM overtime WHERE user_id='$data_user[user_id]' AND overtime_id < $id $filter ORDER BY overtime_id DESC LIMIT $showLimit";
$result_histori = $connection->query($query_histori);

if($result_histori->num_rows > 0){
  while ($data_histori= $result_histori->fetch_assoc()){
    $overtime_id = anti_injection($data_histori['overtime_id']);
    
    if($data_histori['status'] ==1){
      $status_overtime='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_histori['status'] =='Y'){
      $status_overtime='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_histori['status'] =='N'){
      $status_overtime='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status_overtime='';
    }
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_histori['tanggal_in']).' s/d '.tanggal_ind($data_histori['tanggal_out']).' | </span>'.$status_overtime.'</p>
            </div>
            
        </div>

        <div class="row align-items-center">
          <div class="col-5 align-self-center">
            <small class="mb-1">CHECK IN</small>
                <p class="text-info">'.$data_histori['absen_in'].'</p>
            </div>
            <div class="col align-self-center">
                <small class="mb-1">CHECK OUT</small>';
                if($data_histori['absen_out']=='00:00:00'){
                  echo'<p>-</p>';
                }else{
                  echo'<p class="text-success">'.$data_histori['absen_out'].'</p>';
                }
              echo'
            </div>

            <div class="col-auto align-self-center">
                <a href="#" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_histori['overtime_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>

                <a href="#" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['overtime_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['overtime_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_histori['overtime_id'].'">
          <p class="text-secondary">'.strip_tags($data_histori['keterangan']).'</p>
        </div>

    </div>
</div>';
}

if($totalRowCount > $showLimit){
  echo'
  <div class="text-center show_more_main'.$overtime_id.' mt-4">
      <button data-id="'.$overtime_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';
  }

}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Overtime sudah tidak ada!</div>';
}


/** Add Overtime IN*/
break;
case 'check-in':
    $error = array();
      if (empty($_POST['latitude'])) { 
          $error[] = 'Lokasi tidak boleh kosong';
        } else {
          $latitude = htmlentities(strip_tags($_POST['latitude']));
      }

      if (empty($_POST['keterangan'])) { 
        $error[] = 'Keterangan tidak boleh kosong';
      } else {
        $keterangan = htmlentities(strip_tags($_POST['keterangan']));
      }

    if (empty($error)){

        /** Cek Overtime Hari ini berdasarkan tanggal sekarang */
        $query_absensi ="SELECT overtime_id,absen_in FROM overtime WHERE overtime='$overtime' AND user_id='$data_user[user_id]' LIMIT 1";
        $result_absensi = $connection->query($query_absensi);
              if(!$result_absensi->num_rows > 0){
                /** Jika belum ada makan tambah absen baru */
                $overtime = ''.$data_user['user_id'].''.time().'';
                $add ="INSERT INTO overtime (overtime,
                        user_id,
                        lokasi_id,
                        tanggal_in,
                        tanggal_out,
                        absen_in,
                        absen_out,
                        latitude_in,
                        latitude_out,
                        keterangan,
                        status) values('$overtime',
                        '$data_user[user_id]',
                        '$data_user[lokasi_id]',
                        '$date',
                        '0000-00-00', /** Tanggal out kosong */
                        '$time_sekarang', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '$latitude',
                        '', /** Latitude out */
                        '$keterangan',
                        '1')";
                  if($connection->query($add) === false) { 
                    echo'Sepertinya Sistem Kami sedang error!';
                    die($connection->error.__LINE__); 
                  } else{
                    setcookie('overtime', $overtime, strtotime('+ 3 day'), '/');
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Baru saja Anda Absen Overtime pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                  }
              }else{
                /** Berikan notifikasi Absen masuk jika sudah terinput */
                $data_absen = $result_absensi->fetch_assoc();
                echo'Hallo, "'.$data_user['nama_lengkap'].'", Anda sudah pernah Absen Masuk Overtime pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absen['absen_in'].'!';
              }
          }else{       
            foreach ($error as $key => $values) {            
                echo"$values\n";
            }
        }
 


  /** Overtime Out */
        break;
        case 'check-out':
            $error = array();
              if (empty($_POST['latitude'])) { 
                  $error[] = 'Lokasi tidak boleh kosong';
                } else {
                  $latitude = htmlentities(strip_tags($_POST['latitude']));
              }
      
        
            if (empty($error)){
                /** Cek Overtime Hari ini berdasarkan overtime */
                $query_absensi ="SELECT overtime_id,absen_out FROM overtime WHERE overtime='$overtime' AND user_id='$data_user[user_id]' LIMIT 1";
                $result_absensi = $connection->query($query_absensi);
                      if($result_absensi->num_rows > 0){
                          $update ="UPDATE overtime SET tanggal_out='$date',
                              absen_out='$time_sekarang',
                              latitude_out='$latitude'WHERE overtime='$overtime' AND user_id='$data_user[user_id]'";
                          if($connection->query($update) === false) { 
                            echo'Sepertinya Sistem Kami sedang error!';
                            die($connection->error.__LINE__); 
                          } else{
                            setcookie('overtime', '', strtotime('- 3 day'), '/');
                            echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Baru saja Anda CHECK OUT Overtime pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                          }
                      }else{
                        /** Berikan notifikasi jika sudah terinput */
                        $data_absen = $result_absensi->fetch_assoc();
                        echo'Hallo, "'.$data_user['nama_lengkap'].'", Baru saja Anda CHECK OUT Overtime pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absen['absen_out'].'!';
                      }
                }else{       
                  foreach ($error as $key => $values) {            
                      echo"$values\n";
                  }
              }

  
  /** Delete Overtime */
  break;
  case 'delete':
  $id       = anti_injection(convert("decrypt",$_POST['id']));
  $query_overtime ="SELECT overtime_id FROM overtime WHERE status='2' AND overtime_id='$id'";
  $result_overtime = $connection->query($query_overtime);
  if(!$result_overtime->num_rows > 0){
      $deleted = "DELETE FROM overtime WHERE overtime_id='$id'";
      if($connection->query($deleted) === true) {
        echo'success';
      } else { 
        //tidak berhasil
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
      }
    }else{
      echo'Data ini disetujui dan tidak dapat dihapus!';
    }

    break;
  }
}