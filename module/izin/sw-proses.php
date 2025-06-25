<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../sw-library/csrf.php';
  require_once'../../module/oauth/user.php';
  $max_size = 2000000; //2MB
  $allowed_ext = array('jpg','jpeg','gif', 'png');
  $uploadPath       = '../../sw-content/izin/';
  function resizeImage($resourceType,$image_width,$image_height){
    $resizeWidth = 500;
    $resizeHeight = ($image_height/$image_width)*$resizeWidth;
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
  }

switch (@$_GET['action']){
case 'data-izin':

if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND MONTH(tanggal_mulai) ='$month' AND YEAR(tanggal_mulai) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND tanggal_mulai BETWEEN '$mulai' AND '$selesai'";
}

$query_izin ="SELECT izin_id,tanggal_mulai,tanggal_selesai,jenis,files,keterangan,status FROM izin WHERE user_id='$data_user[user_id]' $filter ORDER BY izin_id DESC LIMIT 10";
$result_izin = $connection->query($query_izin);
if($result_izin->num_rows > 0){
  while ($data_izin= $result_izin->fetch_assoc()){
    $izin_id = anti_injection($data_izin['izin_id']);
    if($data_izin['status'] == '-'){
      $status='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_izin['status'] == 'Y'){
      $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_izin['status'] == 'N'){
      $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status='';
    }
    
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_izin['tanggal_mulai']).' s/d '.tanggal_ind($data_izin['tanggal_selesai']).' | </span>'.$status.'</p>
            </div>
            
        </div>

        <div class="row align-items-center">
            <div class="col align-self-center">
                <p class="text-secondary">Alasan: '.strip_tags($data_izin['jenis']).'</p>
            </div>
        
            <div class="col-auto align-self-center">';
                if($data_izin['status']== '-'){
                echo'
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_izin['izin_id']).'" data-tanggal="'.tanggal_ind($data_izin['tanggal_mulai']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_izin['izin_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>';
                }
                echo'
                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_izin['izin_id'].'" aria-expanded="false" aria-controls="collapse'.$data_izin['izin_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_izin['izin_id'].'">
          <p class="text-secondary">'.strip_tags($data_izin['keterangan']).'</p>
        </div>

    </div>
</div>';
}
  echo'
  <div class="text-center show_more_main'.$izin_id.' mt-4">
      <button data-id="'.$izin_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';


}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Izin masih kosong</div>';
}

/** Moad More Izin */
break;
case 'data-izin-load':
if (empty($_POST['mulai']) OR empty($_POST['selesai'])){ 
  $filter = "AND MONTH(tanggal_mulai) ='$month' AND YEAR(tanggal_mulai) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_POST['mulai']));
  $selesai = date('Y-m-d', strtotime($_POST['selesai']));
  $filter = "AND tanggal_mulai BETWEEN '$mulai' AND '$selesai'";
}

$id = anti_injection($_POST['id']);

$query_count    ="SELECT COUNT(izin_id) AS total FROM izin WHERE izin_id < $id $filter ORDER BY izin_id DESC";
$result_count   = $connection->query($query_count);
$data_count     = $result_count->fetch_assoc();
$totalRowCount  = $data_count['total'];

$showLimit = 10;
$query_izin ="SELECT izin_id,tanggal_mulai,tanggal_selesai,jenis,files,keterangan,status FROM izin WHERE user_id='$data_user[user_id]' AND izin_id < $id $filter ORDER BY izin_id DESC LIMIT $showLimit";
$result_izin = $connection->query($query_izin);

if($result_izin->num_rows > 0){
  while ($data_izin= $result_izin->fetch_assoc()){
    $izin_id = anti_injection($data_izin['izin_id']);
    if($data_izin['status'] == '-'){
      $status='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_izin['status'] == 'Y'){
      $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_izin['status'] == 'N'){
      $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status='';
    }
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_izin['tanggal_mulai']).' s/d '.tanggal_ind($data_izin['tanggal_selesai']).' | </span>'.$status.'</p>
            </div>
            
        </div>

        <div class="row align-items-center">
            <div class="col align-self-center">
                <p class="text-secondary">Alasan: '.strip_tags($data_izin['jenis']).'</p>
            </div>
        

            <div class="col-auto align-self-center">';
            if($data_izin['status']==1){
              echo'
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_izin['izin_id']).'" data-tanggal="'.tanggal_ind($data_izin['tanggal_mulai']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_izin['izin_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>';
            }
              echo'
                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_izin['izin_id'].'" aria-expanded="false" aria-controls="collapse'.$data_izin['izin_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_izin['izin_id'].'">
          <p class="text-secondary">'.strip_tags($data_izin['keterangan']).'</p>
        </div>

    </div>
</div>';
}

if($totalRowCount > $showLimit){
  echo'
  <div class="text-center show_more_main'.$izin_id.' mt-4">
      <button data-id="'.$izin_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';
  }

}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Izin sudah tidak ada!</div>';
}


/** Tambah baru dan update Data Izin*/
break;
case 'add':
    $error = array();
    if (empty($_POST['id'])) { 
      $id ='';
    } else {
      $id = anti_injection($_POST['id']);
    }

      if (empty($_POST['tanggal_mulai'])) { 
          $error[] = 'Tanggal mulai tidak boleh kosong';
        } else {
          $tanggal_mulai = date('Y-m-d', strtotime($_POST['tanggal_mulai']));
      }

      if (empty($_POST['tanggal_selesai'])) { 
        $error[] = 'Tanggal selesai tidak boleh kosong';
      } else {
        $tanggal_selesai = date('Y-m-d', strtotime($_POST['tanggal_selesai']));
    }

    if (empty($_POST['jenis'])) { 
      $error[] = 'Status izin tidak boleh kosong';
    } else {
      $jenis = htmlentities(strip_tags($_POST['jenis']));
    }

      if (empty($_POST['keterangan'])) { 
        $error[] = 'Keterangan tidak boleh kosong';
      } else {
        $keterangan = htmlentities(strip_tags($_POST['keterangan']));
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

      // Ambil atasan_id dari tabel user
      $query_atasan = "SELECT atasan_id FROM user WHERE user_id='$data_user[user_id]' LIMIT 1";
      $result_atasan = $connection->query($query_atasan);
      $atasan_id = null;
      if($result_atasan && $result_atasan->num_rows > 0){
        $row_atasan = $result_atasan->fetch_assoc();
        $atasan_id = $row_atasan['atasan_id'];
      }
      if(empty($atasan_id)){
        echo 'Pengajuan ditolak, Anda belum memiliki atasan.';
        exit;
      }

      $notifikasi ="INSERT INTO notifikasi (user_id,
                nama,
                keterangan,
                link,
                tanggal,
                datetime,
                tipe,
                status) values('$data_user[user_id]',
                '$data_user[nama_lengkap]',
                'Baru saja megajukan izin',
                'izin',
                '$tanggal_mulai',
                '$timeNow',
                '1',
                'N')";


        /** Cek Izin Hari ini berdasarkan tanggal sekarang */
        $query_izin ="SELECT izin_id FROM izin WHERE tanggal_mulai='$tanggal_mulai' AND user_id='$data_user[user_id]'";
        $result_izin = $connection->query($query_izin);
              if(!$result_izin->num_rows > 0){

                if(in_array($fileExt, $allowed_ext) === true){
                  if ($file_size <= $max_size) {

                $sourceProperties = getimagesize($file_tmp);
                $uploadImageType  = $sourceProperties[2];
                $sourceImageWidth = $sourceProperties[0];
                $sourceImageHeight = $sourceProperties[1];
                
                $files_name  = ''.time().'-'.seo_title($data_user['nama_lengkap']).'';
                $files       = ''.$files_name.'.'.$fileExt.'';

                $add ="INSERT INTO izin (user_id,
                        nama_lengkap,
                        tanggal_mulai,
                        tanggal_selesai,
                        files,
                        jenis,
                        keterangan,
                        atasan_id,
                        date,
                        time,
                        status) values('$data_user[user_id]',
                        '$data_user[nama_lengkap]',
                        '$tanggal_mulai',
                        '$tanggal_selesai',
                        '$files',
                        '$jenis',
                        '$keterangan',
                        '$atasan_id',
                        '$date',
                        '$time_sekarang',
                        '-')";

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
                echo 'Izin ditolak, pada tanggal '.tanggal_ind($tanggal_mulai).' sudah pernah izin!';
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
$query_izin  = "SELECT * FROM izin WHERE user_id='$data_user[user_id]' AND izin_id='$id'";
$result_izin = $connection->query($query_izin);
if($result_izin->num_rows > 0){
  while ($data_izin = $result_izin->fetch_assoc()) {
    $data['izin_id']          = anti_injection($data_izin["izin_id"]);
    $data['tanggal_mulai']    = tanggal_ind($data_izin["tanggal_mulai"]);
    $data['tanggal_selesai']  = tanggal_ind($data_izin["tanggal_selesai"]);
    $data['jenis']            = htmlentities($data_izin["jenis"]);
    $data['keterangan']       = strip_tags($data_izin["keterangan"]);
    $data['files']            = strip_tags($data_izin["files"]);
  }
  echo json_encode($data);
}else{
  echo'Data tidak ditemukan';
}

/** Update */
break;
case 'update':
$error = array();
  if (empty($_POST['id'])) { 
    $error[] = 'ID tidak ditemukan!';
  } else {
    $id = anti_injection($_POST['id']);
  }

  if (empty($_POST['tanggal_mulai'])) { 
      $error[] = 'Tanggal mulai tidak boleh kosong';
    } else {
      $tanggal_mulai = date('Y-m-d', strtotime($_POST['tanggal_mulai']));
  }

  if (empty($_POST['tanggal_selesai'])) { 
    $error[] = 'Tanggal selesai tidak boleh kosong';
  } else {
    $tanggal_selesai = date('Y-m-d', strtotime($_POST['tanggal_selesai']));
  }

  if (empty($_POST['jenis'])) { 
    $error[] = 'Status izin tidak boleh kosong';
  } else {
    $jenis = htmlentities(strip_tags($_POST['jenis']));
  }

  if (empty($_POST['keterangan'])) { 
    $error[] = 'Keterangan tidak boleh kosong';
  } else {
    $keterangan = htmlentities(strip_tags($_POST['keterangan']));
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
      $update="UPDATE izin SET tanggal_mulai='$tanggal_mulai',
              tanggal_selesai='$tanggal_selesai',
              jenis='$jenis',
              keterangan='$keterangan',
              date='$date',
              time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND izin_id='$id'"; 
        if($connection->query($update) === false) { 
          echo'Sepertinya Sistem Kami sedang error!';
          die($connection->error.__LINE__); 
        } else{
          echo'success';
        }
      }else{       
        foreach ($error as $key => $values) {            
            echo"$values\n";
        }
    }

}else{
  /** Update Data izin dengan foto */
    if(in_array($fileExt, $allowed_ext) === true){
      if ($file_size <= $max_size) {

        $query_izin ="SELECT files FROM izin WHERE user_id='$data_user[user_id]' AND izin_id='$id'";
        $result_izin = $connection->query($query_izin);
        if($result_izin->num_rows > 0){
          $data_izin = $result_izin->fetch_assoc();
          if(file_exists("../../sw-content/izin/".$data_izin['files']."")){
            unlink ('../../sw-content/izin/'.$data_izin['files'].'');
          }
        }

        $update="UPDATE izin SET tanggal_mulai='$tanggal_mulai',
            tanggal_selesai='$tanggal_selesai',
            jenis='$jenis',
            files='$files',
            keterangan='$keterangan',
            date='$date',
            time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND izin_id='$id'";

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
  $query_izin ="SELECT izin_id,files FROM izin WHERE izin_id='$id'";
  $result_izin = $connection->query($query_izin);
  if($result_izin->num_rows > 0){
    $data_izin = $result_izin->fetch_assoc();
    $files = strip_tags($data_izin['files']);
    $tmpfile = "../../sw-content/izin/".$files;
      $deleted = "DELETE FROM izin WHERE izin_id='$id'";
      if($connection->query($deleted) === true) {
        echo'success';
        if(file_exists("../../sw-content/izin/$files")){
          unlink ($tmpfile);
        }
      } else { 
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
      }
    }else{
      echo'Data tidak ditemukan, Silahkan hubungi Admin!';
    }

    break;
  }
}