<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
  exit();
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../module/oauth/user.php';
  $max_size = 2000000; //2MB
  $allowed_ext = array('jpg','jpeg','gif', 'png');
  $uploadPath       = '../../sw-content/laporan-kerja/';
  function resizeImage($resourceType,$image_width,$image_height){
    $resizeWidth = 500;
    $resizeHeight = ($image_height/$image_width)*$resizeWidth;
    $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
    imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
    return $imageLayer;
  }

switch (@$_GET['action']){
  
case 'data-uraian-kerja':
if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND YEARWEEK(tanggal)=YEARWEEK(NOW())";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND tanggal BETWEEN  '$mulai' AND '$selesai'";
}

$query_histori ="SELECT uraian_kerja_id,nama,tanggal,keterangan,files,status FROM uraian_kerja WHERE user_id='$data_user[user_id]' $filter ORDER BY uraian_kerja_id DESC LIMIT 10";
$result_histori = $connection->query($query_histori);
if($result_histori->num_rows > 0){
  while ($data_histori= $result_histori->fetch_assoc()){
    $uraian_kerja_id = anti_injection($data_histori['uraian_kerja_id']);

    if($data_histori['files'] ==''){
      $foto ='<img src="./sw-content/thumbnail.jpg" class="imaged w100 rounded" height="50">';
  }else{
      if(!file_exists('../../sw-content/laporan-kerja/'.$data_histori['files'].'')){
          $foto ='<img src="./sw-content/thumbnail.jpg" class="imaged w100 rounded" height="50">';
      }else{
          $foto ='<a class="badge badge-warning" href="data:image/gif;base64,'.base64_encode(file_get_contents('../../sw-content/laporan-kerja/'.strip_tags($data_histori['files']).'')).'" target=_blank"">Dokumen</a>';
      }
  }

echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row">
            <div class="col align-self-center">
                <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_histori['tanggal']).'</span> '.$foto.'</p>
            </div>
        </div>

        <div class="row align-items-center">
            <div class="col align-self-center">
              <p class="text-secondary">'.strip_tags($data_histori['nama']).'</p>
            </div>
        
            <div class="col-auto align-self-center">
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_histori['uraian_kerja_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_histori['uraian_kerja_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>

                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['uraian_kerja_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['uraian_kerja_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_histori['uraian_kerja_id'].'">
          <p class="text-secondary">'.strip_tags($data_histori['keterangan']).'</p>
        </div>

    </div>
</div>';
}
  echo'
  <div class="text-center show_more_main'.$uraian_kerja_id.' mt-4">
      <button data-id="'.$uraian_kerja_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';


}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Laporan kerja masih kosong</div>';
}

/** Moad More */
break;
case 'data-uraian-kerja-load':

if (empty($_POST['mulai']) OR empty($_POST['selesai'])){ 
  $filter = "AND YEARWEEK(tanggal)=YEARWEEK(NOW())";
} else { 
  $mulai = date('Y-m-d', strtotime($_POST['mulai']));
  $selesai = date('Y-m-d', strtotime($_POST['selesai']));
  $filter = "AND tanggal BETWEEN '$mulai' AND '$selesai'";
}


$id = anti_injection($_POST['id']);

$query_count    ="SELECT COUNT(uraian_kerja_id) AS total FROM uraian_kerja WHERE uraian_kerja_id < $id $filter ORDER BY uraian_kerja_id DESC";
$result_count   = $connection->query($query_count);
$data_count     = $result_count->fetch_assoc();
$totalRowCount  = $data_count['total'];

$showLimit = 10;
$query_histori ="SELECT uraian_kerja_id,nama,tanggal,keterangan,status FROM uraian_kerja WHERE user_id='$data_user[user_id]' AND uraian_kerja_id < $id ORDER BY uraian_kerja_id DESC LIMIT $showLimit";
$result_histori = $connection->query($query_histori);

if($result_histori->num_rows > 0){
  while ($data_histori= $result_histori->fetch_assoc()){
echo'
<div class="card border-0 mb-2">
<div class="card-body">
<div class="row">
    <div class="col align-self-center">
        <p class="text-secondary"><span class="small text-primary">'.tanggal_ind($data_histori['tanggal']).'</span></p>
    </div>
</div>

<div class="row align-items-center">
    <div class="col align-self-center">
      <p class="text-secondary">'.strip_tags($data_histori['nama']).'</p>
    </div>

    <div class="col-auto align-self-center">
        <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_histori['uraian_kerja_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal']).'">
          <i class="fa-solid fa-pencil"></i>
        </a>
  
        <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_histori['uraian_kerja_id']).'">
          <i class="fa-regular fa-trash-can"></i>
        </a>

        <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['uraian_kerja_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['uraian_kerja_id'].'">
          <i class="fas fa-ellipsis-v"></i>
        </a>

      </div>
  </div>
  <!-- Collapse -->
  <div class="collapse mt-1" id="collapse'.$data_histori['uraian_kerja_id'].'">
    <p class="text-secondary">'.strip_tags($data_histori['keterangan']).'</p>
  </div>

  </div>
</div>';
}

if($totalRowCount > $showLimit){
  echo'
  <div class="text-center show_more_main'.$uraian_kerja_id.' mt-4">
      <button data-id="'.$uraian_kerja_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';
  }

}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data Laporan kerja sudah tidak ada!</div>';
}


/** Tambah baru */
break;
case 'add':
    $error = array();
    if (empty($_POST['nama'])) { 
      $error[] = 'Nama Laporan Kerja tidak boleh kosong';
    } else {
      $nama = htmlentities(strip_tags($_POST['nama']));
    }

    if (empty($_POST['tanggal'])) { 
      $error[] = 'Tanggal tidak boleh kosong';
    } else {
      $tanggal = date('Y-m-d', strtotime($_POST['tanggal']));
    }

    if (empty($_POST['keterangan'])) { 
      $error[] = 'Keterangan tidak boleh kosong';
    } else {
      $keterangan = anti_injection($_POST['keterangan']);
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

      if(in_array($fileExt, $allowed_ext) === true){
        if ($file_size <= $max_size) {
          $sourceProperties = getimagesize($file_tmp);
          $uploadImageType  = $sourceProperties[2];
          $sourceImageWidth = $sourceProperties[0];
          $sourceImageHeight = $sourceProperties[1];
          
          $files_name  = ''.time().'-'.md5($files).'';
          $files       = ''.$files_name.'.'.$fileExt.'';

          $add ="INSERT INTO uraian_kerja (user_id,
                  lokasi_id,
                  nama,
                  tanggal,
                  keterangan,
                  files,
                  date,
                  time,
                  status) values('$data_user[user_id]',
                  '$data_user[lokasi_id]',
                  '$nama',
                  '$tanggal',
                  '$keterangan',
                  '$files',
                  '$date',
                  '$time_sekarang',
                  'Y')";

            $notifikasi ="INSERT INTO notifikasi (user_id,
                    nama,
                    keterangan,
                    link,
                    tanggal,
                    datetime,
                    tipe,
                    status) values('$data_user[user_id]',
                    '$data_user[nama_lengkap]',
                    'Baru saja menambah Laporan kerja',
                    'uraian-kerja',
                    '$tanggal',
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
            foreach ($error as $key => $values) {            
                echo"$values\n";
            }
        }
 


/** Get Update Data*/
break;
case 'get-data-update':
$id       = anti_injection(convert("decrypt",$_POST['id']));
$query_uraian  = "SELECT * FROM uraian_kerja WHERE user_id='$data_user[user_id]' AND uraian_kerja_id='$id'";
$result_uraian = $connection->query($query_uraian);
if($result_uraian->num_rows > 0){
  while ($data_uraian = $result_uraian->fetch_assoc()) {
    $data['uraian_kerja_id']  = anti_injection($data_uraian["uraian_kerja_id"]);
    $data['nama']             = htmlentities($data_uraian["nama"]);
    $data['tanggal']          = tanggal_ind($data_uraian["tanggal"]);
    $data['keterangan']       = strip_tags($data_uraian["keterangan"]);
    $data['files']            = strip_tags($data_uraian["files"]);
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

  if (empty($_POST['nama'])) { 
    $error[] = 'Nama Uraian Kerja tidak boleh kosong';
  } else {
    $nama = htmlentities(strip_tags($_POST['nama']));
  }

  if (empty($_POST['tanggal'])) { 
    $error[] = 'Tanggal tidak boleh kosong';
  } else {
    $tanggal = date('Y-m-d', strtotime($_POST['tanggal']));
  }

  if (empty($_POST['keterangan'])) { 
    $error[] = 'Keterangan tidak boleh kosong';
  } else {
    $keterangan = anti_injection($_POST['keterangan']);
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

    $files_name  = ''.time().'-'.md5($files).'';
    $files       = ''.$files_name.'.'.$fileExt.'';
  }

  if($files==''){
      if (empty($error)){
        $update="UPDATE uraian_kerja SET nama='$nama',
                tanggal='$tanggal',
                keterangan='$keterangan',
                files='$files',
                date='$date',
                time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND uraian_kerja_id='$id'"; 
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
          foreach ($error as $key => $values) {            
              echo"$values\n";
          }
      }
  
  }else{
    /** Update dengan foto */
    if(in_array($fileExt, $allowed_ext) === true){
      if ($file_size <= $max_size) {

        $query ="SELECT files FROM uraian_kerja WHERE user_id='$data_user[user_id]' AND uraian_kerja_id='$id'";
        $result = $connection->query($query);
        if($result->num_rows > 0){
          $data = $result_izin->fetch_assoc();
          if(file_exists("../../sw-content/laporan-kerja/".$data['files']."")){
            unlink ('../../sw-content/laporan-kerja/'.$data['files'].'');
          }
        }

        $update="UPDATE uraian_kerja SET nama='$nama',
            tanggal='$tanggal',
            keterangan='$keterangan',
            date='$date',
            time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND uraian_kerja_id='$id'"; 
        if($connection->query($update) === false) { 
          echo'Sepertinya Sistem Kami sedang error!';
          die($connection->error.__LINE__); 
        } else{
          echo'success';
        }
      }else{
        echo 'Foto yang Anda upload terlalu besar maksimal harus 2MB!';
      }
    }else{
      echo 'Foto yang dibolehkan harus format JPG, JPEG dan GIF!';
    }
  }



  /** Delete*/
  break;
  case 'delete':
      $id       = anti_injection(convert("decrypt",$_POST['id']));

      $query ="SELECT files FROM uraian_kerja WHERE user_id='$data_user[user_id]' AND uraian_kerja_id='$id'";
      $result = $connection->query($query);
        if($result->num_rows > 0){
          $data = $result->fetch_assoc();
          if(file_exists("../../sw-content/laporan-kerja/".$data['files']."")){
            unlink ('../../sw-content/laporan-kerja/'.$data['files'].'');
          }
        }

        
      $deleted = "DELETE FROM uraian_kerja WHERE uraian_kerja_id='$id'";
      if($connection->query($deleted) === true) {
        echo'success';
      } else { 
        echo'Data tidak berhasil dihapus.!';
        die($connection->error.__LINE__);
      }

    break;
  }
}