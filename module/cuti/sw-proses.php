<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
  exit();
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../sw-library/csrf.php';
  require_once'../../module/oauth/user.php';
  $max_size = 20000000; //2MB
  $allowed_ext = array('jpg','jpeg','JPG','gif', 'png','PNG');
  $uploadPath       = '../../sw-content/cuti/';
  function resizeImage($resourceType,$image_width,$image_height){
    $resizeWidth = 500;
    $resizeHeight = ($image_height/$image_width)*$resizeWidth;
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
  }

switch (@$_GET['action']){
case 'data-cuti':
if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND MONTH(tanggal_mulai) ='$month' AND YEAR(tanggal_mulai) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND tanggal_mulai BETWEEN '$mulai' AND '$selesai'";
}


$query_histori ="SELECT cuti_id,jenis,tanggal_mulai,tanggal_selesai,jumlah,keterangan,status FROM cuti WHERE user_id='$data_user[user_id]' $filter ORDER BY cuti_id DESC LIMIT 10";
$result_histori = $connection->query($query_histori);
if($result_histori->num_rows > 0){
  while ($data_histori= $result_histori->fetch_assoc()){
    $cuti_id = anti_injection($data_histori['cuti_id']);

    if($data_histori['status'] == '-'){
      $status='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_histori['status'] == 'Y'){
      $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_histori['status'] == 'N'){
      $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status='';
    }
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_histori['tanggal_mulai']).' s/d '.tanggal_ind($data_histori['tanggal_selesai']).' | </span>'.$status.'</p>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col align-self-center">
              <p class="text-secondary">Jenis cuti : '.strip_tags($data_histori['jenis']).'</p>
            </div>
        
            <div class="col-auto align-self-center">';
                if($data_histori['status']=='-'){
                echo'
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_histori['cuti_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal_mulai']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_histori['cuti_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>';
                }
                echo'
                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['cuti_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['cuti_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_histori['cuti_id'].'">
          <p class="text-secondary">Jumlah : '.strip_tags($data_histori['jumlah']).' hari<br>
            Alasan : '.strip_tags($data_histori['keterangan']).'
          </p>
        </div>

    </div>
</div>';
}
  echo'
  <div class="text-center show_more_main'.$cuti_id.' mt-4">
      <button data-id="'.$cuti_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';


}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Cuti masih kosong</div>';
}

/** Moad More Cuti */
break;
case 'data-cuti-load':

if (empty($_POST['mulai']) OR empty($_POST['selesai'])){ 
  $filter = "AND MONTH(tanggal_mulai) ='$month' AND YEAR(tanggal_mulai) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_POST['mulai']));
  $selesai = date('Y-m-d', strtotime($_POST['selesai']));
  $filter = "AND tanggal_mulai BETWEEN '$mulai' AND '$selesai'";
}


$id = anti_injection($_POST['id']);

$query_count    ="SELECT COUNT(cuti_id) AS total FROM cuti WHERE cuti_id < $id $filter ORDER BY cuti_id DESC";
$result_count   = $connection->query($query_count);
$data_count     = $result_count->fetch_assoc();
$totalRowCount  = $data_count['total'];

$showLimit = 10;
$query_histori ="SELECT cuti_id,jenis,tanggal_mulai,tanggal_selesai,jumlah,keterangan,status FROM cuti WHERE user_id='$data_user[user_id]' AND cuti_id < $id $filter ORDER BY cuti_id DESC LIMIT $showLimit";
$result_histori = $connection->query($query_histori);

if($result_histori->num_rows > 0){
  while ($data_histori= $result_histori->fetch_assoc()){
    $cuti_id = anti_injection($data_histori['cuti_id']);
    if($data_histori['status'] == '-'){
      $status='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_histori['status'] == 'Y'){
      $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_histori['status'] == 'N'){
      $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status='';
    }
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_histori['tanggal_mulai']).' s/d '.tanggal_ind($data_histori['tanggal_selesai']).' | </span>'.$status.'</p>
            </div>
            
        </div>

        <div class="row align-items-center">
            <div class="col align-self-center">
                <p class="text-secondary">Jenis cuti: '.strip_tags($data_histori['jenis']).'</p>
            </div>
        

            <div class="col-auto align-self-center">';
            if($data_histori['status']=='-'){
              echo'
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_histori['cuti_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal_mulai']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_histori['cuti_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>';
            }
              echo'
                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['cuti_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['cuti_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_histori['cuti_id'].'">
          <p class="text-secondary">Jumlah : '.strip_tags($data_histori['jumlah']).' hari<br>
            Alasan : '.strip_tags($data_histori['keterangan']).'
          </p>
        </div>

    </div>
</div>';
}

if($totalRowCount > $showLimit){
  echo'
  <div class="text-center show_more_main'.$cuti_id.' mt-4">
      <button data-id="'.$cuti_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';
  }

}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Cuti sudah tidak ada!</div>';
}


/** Tambah baru dan update Data Cuti*/
break;
case 'add':
    $error = array();
    if (empty($_POST['id'])) { 
      $id ='';
    } else {
      $id = anti_injection($_POST['id']);
    }

    if (empty($_POST['jenis'])) { 
      $error[] = 'Jenis cuti tidak boleh kosong';
    } else {
      $jenis = htmlentities(strip_tags($_POST['jenis']));
    }

    if (empty($_POST['keterangan'])) { 
      $error[] = 'Alasan Cuti tidak boleh kosong';
    } else {
      $keterangan = anti_injection($_POST['keterangan']);
    }


    if (empty($_POST['tanggal_mulai'])) { 
        $error[] = 'Priode cuti mulai tidak boleh kosong';
      } else {
        $tanggal_mulai = date('Y-m-d', strtotime($_POST['tanggal_mulai']));
    }

    if (empty($_POST['tanggal_selesai'])) { 
      $error[] = 'Periode cuti tidak boleh kosong';
    } else {
      $tanggal_selesai = date('Y-m-d', strtotime($_POST['tanggal_selesai']));
      $jarak_hari = date_diff(date_create($tanggal_mulai), date_create($tanggal_selesai));
      $jumlah = $jarak_hari->days + 1;
    }


    if (empty($_POST['atasan'])) { 
      $error[] = 'Atasan tidak boleh kosong';
    } else {
      $atasan = anti_injection($_POST['atasan']);
    }

    if (empty($_FILES['files']['name'])){
      $error[]          = 'Foto belum di unggah.!';
    } else {
      $files            = strip_tags($_FILES['files']['name']);
      $fileExt          = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
      $file_size        = $_FILES['files']['size'];
      $file_tmp         = $_FILES['files']['tmp_name'];
    }


    if (empty($error)){
        /** Cek cuti Hari ini berdasarkan tanggal sekarang */
        
        $query_cuti ="SELECT cuti_id FROM cuti WHERE tanggal_mulai='$tanggal_mulai' AND user_id='$data_user[user_id]'";
        $result_cuti = $connection->query($query_cuti);
              if(!$result_cuti->num_rows > 0){

                $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_user[posisi_id]' AND active='Y'";
                $result_hak_cuti = $connection->query($query_hak_cuti);
                if($result_hak_cuti->num_rows > 0){
                    $data_hak_cuti = $result_hak_cuti->fetch_assoc();
                    $jumlah_hak_cuti = $data_hak_cuti['jumlah'];
            
                    $query_cuti ="SELECT SUM(jumlah) AS total FROM cuti WHERE user_id='$data_user[user_id]' AND status='Y'";
                    $result_cuti = $connection->query($query_cuti);
                      if($result_hak_cuti->num_rows > 0){
                        $data_cuti = $result_cuti->fetch_assoc();
                        $sisa_cuti = $jumlah_hak_cuti - $data_cuti['total'];
                      }else{
                        $sisa_cuti = $jumlah_hak_cuti;
                      }

                      
                
                if($sisa_cuti >= $jumlah){

                  
                if(in_array($fileExt, $allowed_ext) === true){
                  if ($file_size <= $max_size) {

                  $sourceProperties = getimagesize($file_tmp);
                  $uploadImageType  = $sourceProperties[2];
                  $sourceImageWidth = $sourceProperties[0];
                  $sourceImageHeight = $sourceProperties[1];
                  
                  $files_name  = ''.time().'-'.seo_title($data_user['nama_lengkap']).'';
                  $files       = ''.$files_name.'.'.$fileExt.'';
                  
                  $add ="INSERT INTO cuti (user_id,
                          nama_lengkap,
                          jenis,
                          tanggal_mulai,
                          tanggal_selesai,
                          jumlah,
                          keterangan,
                          atasan,
                          files,
                          date,
                          time,
                          status) values('$data_user[user_id]',
                          '$data_user[nama_lengkap]',
                          '$jenis',
                          '$tanggal_mulai',
                          '$tanggal_selesai',
                          '$jumlah',
                          '$keterangan',
                          '$atasan',
                          '$files',
                          '$date',
                          '$time_sekarang',
                          '-')";

                    $notifikasi ="INSERT INTO notifikasi (user_id,
                            nama,
                            keterangan,
                            link,
                            tanggal,
                            datetime,
                            tipe,
                            status) values('$data_user[user_id]',
                            '$data_user[nama_lengkap]',
                            'Baru saja mengajukan cuti',
                            'cuti',
                            '$tanggal_mulai',
                            '$timeNow',
                            '1',
                            'N')";

                    if($connection->query($add) === false) { 
                      echo'Sepertinya Sistem Kami sedang error!';
                      die($connection->error.__LINE__); 
                    } else{
                      echo'success';
                      $connection->query($notifikasi);
                      switch ($uploadImageType) {
                        case IMAGETYPE_JPEG:
                            $resourceType = imagecreatefromjpeg($file_tmp); 
                            $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                            imagejpeg($imageLayer,$uploadPath."".$files_name.'.'. $fileExt);
                            break;
                
                        case IMAGETYPE_GIF:
                            $resourceType = imagecreatefromgif($file_tmp); 
                            $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                            imagegif($imageLayer,$uploadPath."".$files_name.'.'. $fileExt);
                            break;
                
                        case IMAGETYPE_PNG:
                            $resourceType = imagecreatefrompng($file_tmp); 
                            $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                            imagepng($imageLayer,$uploadPath."".$files_name.'.'. $fileExt);
                            break;
                
                        default:
                            $imageProcess = 0;
                        break;
                      }
                    }

                  }else{
                    echo 'Foto yang Anda upload terlalu besar maksimal harus 2MB!';
                  }
                    
                  }else{
                    echo 'Foto yang dibolehkan harus format JPG, JPEG dan GIF!';
                  }


                  }else{
                    echo'Hak cuti Anda "'.strip_tags($data_user['nama_lengkap']).'" tidak cukup!';
                  }
                  }else{
                    echo'Data Hak cuti tidak ditemukan, Silahkan hubungi Admin!';
                  }

              }else{
                echo 'Cuti ditolak, pada tanggal '.tanggal_ind($tanggal_mulai).' sudah pernah cuti!';
              }

          }else{       
            foreach ($error as $key => $values) {            
                echo"$values\n";
            }
        }
 


/** Get Update Data Izin */
break;
case 'get-data-update':
$id       = anti_injection(convert("decrypt",$_POST['id']));
$query_cuti  = "SELECT * FROM cuti WHERE user_id='$data_user[user_id]' AND cuti_id='$id'";
$result_cuti = $connection->query($query_cuti);
if($result_cuti->num_rows > 0){
  while ($data_cuti = $result_cuti->fetch_assoc()) {
    $data['cuti_id']          = anti_injection($data_cuti["cuti_id"]);
    $data['jenis']            = htmlentities($data_cuti["jenis"]);
    $data['tanggal_mulai']    = tanggal_ind($data_cuti["tanggal_mulai"]);
    $data['tanggal_selesai']  = tanggal_ind($data_cuti["tanggal_selesai"]);
    $data['keterangan']       = strip_tags($data_cuti["keterangan"]);
    $data['atasan']           = strip_tags($data_cuti["atasan"]);
    $data['files']            = strip_tags($data_cuti["files"]);
  }
  echo json_encode($data);
}else{

}

/** Update */
break;
case 'update':
$error = array();
  if (empty($_POST['id'])) { 
    $id ='';
  } else {
    $id = anti_injection($_POST['id']);
  }

  if (empty($_POST['jenis'])) { 
    $error[] = 'Jenis cuti tidak boleh kosong';
  } else {
    $jenis = htmlentities(strip_tags($_POST['jenis']));
  }

  if (empty($_POST['keterangan'])) { 
    $error[] = 'Alasan Cuti tidak boleh kosong';
  } else {
    $keterangan = anti_injection($_POST['keterangan']);
  }


  if (empty($_POST['tanggal_mulai'])) { 
      $error[] = 'Priode cuti mulai tidak boleh kosong';
    } else {
      $tanggal_mulai = date('Y-m-d', strtotime($_POST['tanggal_mulai']));
  }

  if (empty($_POST['tanggal_selesai'])) { 
    $error[] = 'Periode cuti tidak boleh kosong';
  } else {
    $tanggal_selesai = date('Y-m-d', strtotime($_POST['tanggal_selesai']));

    $jarak_hari = date_diff(date_create($tanggal_mulai), date_create($tanggal_selesai));
    $jumlah = $jarak_hari->days + 1;
  }


  if (empty($_POST['atasan'])) { 
    $error[] = 'Atasan tidak boleh kosong';
  } else {
    $atasan = anti_injection($_POST['atasan']);
  }

  if (empty($_FILES['files']['name'])){
    $files            = '';
  } else {
    $files            = strip_tags($_FILES['files']['name']);
    $fileExt          = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
    $file_size        = $_FILES['files']['size'];
    $file_tmp         = $_FILES['files']['tmp_name'];

    $sourceProperties = getimagesize($file_tmp);
    $uploadImageType  = $sourceProperties[2];
    $sourceImageWidth = $sourceProperties[0];
    $sourceImageHeight = $sourceProperties[1];

    $files_name  = ''.time().'-'.seo_title($data_user['nama_lengkap']).'';
    $files       = ''.$files_name.'.'.$fileExt.'';
  }

  if($files==''){
      if (empty($error)){
        
        $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_user[posisi_id]' AND active='Y'";
        $result_hak_cuti = $connection->query($query_hak_cuti);
        if($result_hak_cuti->num_rows > 0){
            $data_hak_cuti = $result_hak_cuti->fetch_assoc();
            $jumlah_hak_cuti = $data_hak_cuti['jumlah'];

            $query_cuti ="SELECT SUM(jumlah) AS total FROM cuti WHERE user_id='$data_user[user_id]' AND status='Y'";
            $result_cuti = $connection->query($query_cuti);
              if($result_hak_cuti->num_rows > 0){
                $data_cuti = $result_cuti->fetch_assoc();
                $sisa_cuti = $jumlah_hak_cuti - $data_cuti['total'];
              }else{
                $sisa_cuti = $jumlah_hak_cuti;
              }
        
        if($sisa_cuti > $jumlah){

            $update="UPDATE cuti SET jenis='$jenis',
                tanggal_mulai='$tanggal_mulai',
                tanggal_selesai='$tanggal_selesai',
                keterangan='$keterangan',
                jumlah='$jumlah',
                atasan='$atasan',
                date='$date',
                time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND cuti_id='$id'"; 
            if($connection->query($update) === false) { 
              echo'Sepertinya Sistem Kami sedang error!';
              die($connection->error.__LINE__); 
            } else{
              echo'success';
            }
          }else{
            echo'Hak cuti Anda "'.strip_tags($data_user['nama_lengkap']).'" tidak cukup!';
          }
          }else{
            echo'Data Hak cuti tidak ditemukan, Silahkan hubungi Admin!';
          }
        }else{       
          foreach ($error as $key => $values) {            
              echo"$values\n";
          }
      }

    }else{
      /** Update Data cuti dengan foto */
        if(in_array($fileExt, $allowed_ext) === true){
          if ($file_size <= $max_size) {

            $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_user[posisi_id]' AND active='Y'";
        $result_hak_cuti = $connection->query($query_hak_cuti);
        if($result_hak_cuti->num_rows > 0){
            $data_hak_cuti = $result_hak_cuti->fetch_assoc();
            $jumlah_hak_cuti = $data_hak_cuti['jumlah'];

            $query_cuti ="SELECT SUM(jumlah) AS total FROM cuti WHERE user_id='$data_user[user_id]' AND status='Y'";
            $result_cuti = $connection->query($query_cuti);
              if($result_hak_cuti->num_rows > 0){
                $data_cuti = $result_cuti->fetch_assoc();
                $sisa_cuti = $jumlah_hak_cuti - $data_cuti['total'];
              }else{
                $sisa_cuti = $jumlah_hak_cuti;
              }
        
        if($sisa_cuti > $jumlah){

            $query_cuti ="SELECT files FROM cuti WHERE user_id='$data_user[user_id]' AND cuti_id='$id'";
            $result_cuti = $connection->query($query_cuti);
            if($result_cuti->num_rows > 0){
              $data_cuti = $result_cuti->fetch_assoc();
              if(file_exists("../../sw-content/cuti/".$data_cuti['files']."")){
                unlink ('../../sw-content/cuti/'.$data_cuti['files'].'');
              }
            }

            $update="UPDATE cuti SET jenis='$jenis',
                tanggal_mulai='$tanggal_mulai',
                tanggal_selesai='$tanggal_selesai',
                keterangan='$keterangan',
                jumlah='$jumlah',
                atasan='$atasan',
                files='$files',
                date='$date',
                time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND cuti_id='$id'"; 
            if($connection->query($update) === false) { 
              echo'Sepertinya Sistem Kami sedang error!';
              die($connection->error.__LINE__); 
            } else{
              echo'success';
              switch ($uploadImageType) {
                case IMAGETYPE_JPEG:
                    $resourceType = imagecreatefromjpeg($file_tmp); 
                    $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                    imagejpeg($imageLayer,$uploadPath."".$files_name.'.'. $fileExt);
                    break;
        
                case IMAGETYPE_GIF:
                    $resourceType = imagecreatefromgif($file_tmp); 
                    $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                    imagegif($imageLayer,$uploadPath."".$files_name.'.'. $fileExt);
                    break;
        
                case IMAGETYPE_PNG:
                    $resourceType = imagecreatefrompng($file_tmp); 
                    $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                    imagepng($imageLayer,$uploadPath."".$files_name.'.'. $fileExt);
                    break;
        
                default:
                    $imageProcess = 0;
                break;
              }
            }
          }else{
            echo'Hak cuti Anda "'.strip_tags($data_user['nama_lengkap']).'" tidak cukup!';
          }
          }else{
            echo'Data Hak cuti tidak ditemukan, Silahkan hubungi Admin!';
          }


          }else{
            echo 'Foto yang Anda upload terlalu besar maksimal harus 2MB!';
          }
        }else{
          echo 'Foto yang dibolehkan harus format JPG, JPEG dan GIF!';
        }


    }



/** Delete Izin*/
break;
case 'delete':
    $id       = anti_injection(convert("decrypt",$_POST['id']));
    $query_cuti ="SELECT files FROM cuti WHERE user_id='$data_user[user_id]' AND cuti_id='$id'";
    $result_cuti = $connection->query($query_cuti);
    if($result_cuti->num_rows > 0){
      $data_cuti = $result_cuti->fetch_assoc();
      if(file_exists("../../sw-content/cuti/".$data_cuti['files']."")){
        unlink ('../../sw-content/cuti/'.$data_cuti['files'].'');
      }
    }
    $deleted = "DELETE FROM cuti WHERE user_id='$data_user[user_id]' AND cuti_id='$id'";
    if($connection->query($deleted) === true) {
      echo'success';
    } else { 
      echo'Data tidak berhasil dihapus.!';
      die($connection->error.__LINE__);
    }

  break;
  }
}