<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../module/oauth/user.php';

  $uploadPath       = '../../sw-content/kunjungan/';

switch (@$_GET['action']){
case 'data-kunjungan':

if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND MONTH(date) ='$month' AND YEAR(date) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND date BETWEEN '$mulai' AND '$selesai'";
}

$query_kunjungan ="SELECT kunjungan_id,lokasi,foto,keterangan,date,time,status FROM kunjungan WHERE user_id='$data_user[user_id]' $filter ORDER BY kunjungan_id DESC LIMIT 10";
$result_kunjungan = $connection->query($query_kunjungan);
if($result_kunjungan->num_rows > 0){
  while ($data_kunjungan= $result_kunjungan->fetch_assoc()){
    $kunjungan_id = anti_injection($data_kunjungan['kunjungan_id']);
    
    if($data_kunjungan['status'] == '-'){
      $status='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_kunjungan['status'] == 'Y'){
      $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_kunjungan['status'] == 'N'){
      $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status='';
    }

    if(file_exists('../../sw-content/kunjungan/'.$data_kunjungan['foto'].'')){
      $photo ='
       <div class="background" style="background-image: url(data:image/gif;base64,'.base64_encode(file_get_contents('../../sw-content/kunjungan/'.$data_kunjungan['foto'].'')).');">
                     
        </div>';
    }else{
        $photo ='<img src="./sw-content/thumbnail.jpg" class="rounded" height="30">';
    }

    
    
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col align-self-left">
                  <small class="text-info">'.tanggal_ind($data_kunjungan['date']).'</small>
                 <p class="text-secondary">'.strip_tags($data_kunjungan['lokasi']).'</p>
            </div>

            <div class="col align-self-center">
              <figure class="avatar avatar-40 rounded-circle" style="margin-bottom:0px">
                 '.$photo.'
              </figure>
            </div>

            
            <div class="col-auto align-self-center">
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_kunjungan['kunjungan_id']).'" data-tanggal="'.tanggal_ind($data_kunjungan['date']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_kunjungan['kunjungan_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>

                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_kunjungan['kunjungan_id'].'" aria-expanded="false" aria-controls="collapse'.$data_kunjungan['kunjungan_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_kunjungan['kunjungan_id'].'">
          <p class="text-secondary">'.strip_tags($data_kunjungan['keterangan']).'</p>
        </div>

    </div>
</div>';
}
  echo'
  <div class="text-center show_more_main'.$kunjungan_id.' mt-4">
      <button data-id="'.$kunjungan_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';


}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data kunjungan masih kosong</div>';
}

/** Moad More kunjungan */
break;
case 'data-kunjungan-load':
if (empty($_POST['mulai']) OR empty($_POST['selesai'])){ 
  $filter = "AND MONTH(date) ='$month' AND YEAR(date) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_POST['mulai']));
  $selesai = date('Y-m-d', strtotime($_POST['selesai']));
  $filter = "AND date BETWEEN '$mulai' AND '$selesai'";
}

$id = anti_injection($_POST['id']);

$query_count    ="SELECT COUNT(kunjungan_id) AS total FROM kunjungan WHERE kunjungan_id < $id $filter ORDER BY kunjungan_id DESC";
$result_count   = $connection->query($query_count);
$data_count     = $result_count->fetch_assoc();
$totalRowCount  = $data_count['total'];

$showLimit = 10;
$query_kunjungan ="SELECT kunjungan_id,lokasi,foto,keterangan,date,time,status FROM kunjungan WHERE user_id='$data_user[user_id]' AND kunjungan_id < $id $filter ORDER BY kunjungan_id DESC LIMIT $showLimit";
$result_kunjungan = $connection->query($query_kunjungan);

if($result_kunjungan->num_rows > 0){
  while ($data_kunjungan= $result_kunjungan->fetch_assoc()){
    $kunjungan_id = anti_injection($data_kunjungan['kunjungan_id']);

    if($data_kunjungan['status'] == '-'){
      $status='<span class="badge badge-primary badge-pill">Panding</span>';
    }elseif($data_kunjungan['status'] == 'Y'){
      $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
    }elseif($data_kunjungan['status'] == 'N'){
      $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
    }else{
      $status='';
    }

    if(file_exists('../../sw-content/kunjungan/'.$data_kunjungan['foto'].'')){
      $photo ='
       <div class="background" style="background-image: url(data:image/gif;base64,'.base64_encode(file_get_contents('../../sw-content/kunjungan/'.$data_kunjungan['foto'].'')).');">
                     
        </div>';
    }else{
        $photo ='<img src="./sw-content/thumbnail.jpg" class="rounded" height="30">';
    }
echo'
<div class="card border-0 mb-2">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col align-self-left">
                  <small class="text-info">'.tanggal_ind($data_kunjungan['date']).'</small>
                 <p class="text-secondary">'.strip_tags($data_kunjungan['lokasi']).'</p>
            </div>

            <div class="col align-self-center">
              <figure class="avatar avatar-40 rounded-circle" style="margin-bottom:0px">
                 '.$photo.'
              </figure>
            </div>

            
            <div class="col-auto align-self-center">
                <a href="javascript:void(0);" class="btn-link text-primary btn-update mr-2" data-id="'.convert("encrypt",$data_kunjungan['kunjungan_id']).'" data-tanggal="'.tanggal_ind($data_kunjungan['date']).'">
                  <i class="fa-solid fa-pencil"></i>
                </a>
          
                <a href="javascript:void(0);" class="btn-link text-danger btn-delete" data-id="'.convert("encrypt",$data_kunjungan['kunjungan_id']).'">
                  <i class="fa-regular fa-trash-can"></i>
                </a>

                <a href="javascript:void(0);" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_kunjungan['kunjungan_id'].'" aria-expanded="false" aria-controls="collapse'.$data_kunjungan['kunjungan_id'].'">
                  <i class="fas fa-ellipsis-v"></i>
                </a>

            </div>
        </div>
        <!-- Collapse -->
        <div class="collapse mt-1" id="collapse'.$data_kunjungan['kunjungan_id'].'">
          <p class="text-secondary">'.strip_tags($data_kunjungan['keterangan']).'</p>
        </div>

    </div>
</div>';
}

if($totalRowCount > $showLimit){
  echo'
  <div class="text-center show_more_main'.$kunjungan_id.' mt-4">
      <button data-id="'.$kunjungan_id.'" class="btn btn-light rounded load-more">Show more</button>
  </div>';
  }

}else{
  echo'<div class="alert alert-secondary mt-3">Saat ini data kunjungan sudah tidak ada!</div>';
}


/** Tambah baru dan update Data kunjungan*/
break;
case 'add':
    $error = array();
    
    if (empty($_POST['id'])) { 
      $id ='';
    } else {
      $id = anti_injection($_POST['id']);
    }

    if (empty($_POST['lokasi'])) { 
      $error[] = 'Lokasi tidak boleh kosong';
    } else {
      $lokasi = htmlentities(strip_tags($_POST['lokasi']));
    }

      if (empty($_POST['keterangan'])) { 
        $error[] = 'Keterangan tidak boleh kosong';
      } else {
        $keterangan = htmlentities(strip_tags($_POST['keterangan']));
      }

      if (empty($_POST['foto'])) { 
        $error[] = 'Foto tidak boleh kosong';
      } else {
        $foto = htmlentities(strip_tags($_POST['foto']));
      }


      if (empty($_POST['img'])){
        $error[]    = 'Foto tidak dapat di unggah!';
      } else {
        $img = $_POST['img'];
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $fetch_imgParts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $fetch_imgParts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($fetch_imgParts[1]);
    
        $im = imagecreatefromstring($image_base64);
        $source_width = imagesx($im);
        $source_height = imagesy($im);
        $ratio =  $source_height / $source_width;
    
        $new_width =400; // assign new width to new resized image
        $new_height = $ratio * 400;
    
        $thumb = imagecreatetruecolor($new_width, $new_height);
        $transparency = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
        imagefilledrectangle($thumb, 0, 0, $new_width, $new_height, $transparency);
        imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);
        $photo   = ''.seo_title(strtolower($data_user["nama_lengkap"])).''.time().'.jpg';
        $filename = '../../sw-content/kunjungan/'.$photo.'';
      }

    if (empty($error)){
      $notifikasi ="INSERT INTO notifikasi (user_id,
                nama,
                keterangan,
                link,
                tanggal,
                datetime,
                tipe,
                status) values('$data_user[user_id]',
                '$data_user[nama_lengkap]',
                'Baru saja kunjungan',
                'laporan-kunjungan',
                '$date',
                '$timeNow',
                '1',
                'N')";

            $add ="INSERT INTO kunjungan (user_id,
                    lokasi_id,
                    lokasi,
                    keterangan,
                    foto,
                    date,
                    time,
                    status) values('$data_user[user_id]',
                    '$data_user[lokasi_id]',
                    '$lokasi',
                    '$keterangan',
                    '$photo',
                    '$date',
                    '$time_sekarang',
                    'Y')";

              if($connection->query($add) === false) { 
                echo'Sepertinya Sistem Kami sedang error!';
                die($connection->error.__LINE__); 
              } else{
                echo'success';
                $connection->query($notifikasi);
                imagepng($thumb, $filename, 8); 
              }
          }else{       
            foreach ($error as $key => $values) {            
                echo"$values\n";
            }
        }



/** Get Update Data kunjungan */
break;
case 'get-data-update':
$id       = anti_injection(convert("decrypt",$_POST['id']));
$query_kunjungan  = "SELECT * FROM kunjungan WHERE user_id='$data_user[user_id]' AND kunjungan_id='$id'";
$result_kunjungan = $connection->query($query_kunjungan);
if($result_kunjungan->num_rows > 0){
  while ($data_kunjungan = $result_kunjungan->fetch_assoc()) {
    $data['kunjungan_id']     = anti_injection($data_kunjungan["kunjungan_id"]);
    $data['lokasi']           = strip_tags($data_kunjungan["lokasi"]);
    $data['keterangan']       = strip_tags($data_kunjungan["keterangan"]);
    $data['foto']             = strip_tags($data_kunjungan["foto"]);
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


  if (empty($_POST['lokasi'])) { 
    $error[] = 'Lokasi kunjungan tidak boleh kosong';
  } else {
    $lokasi = htmlentities(strip_tags($_POST['lokasi']));
  }

  if (empty($_POST['keterangan'])) { 
    $error[] = 'Keterangan tidak boleh kosong';
  } else {
    $keterangan = htmlentities(strip_tags($_POST['keterangan']));
  }


  if (empty($_POST['foto'])) { 
    $foto = '';
  } else {
    $foto = htmlentities(strip_tags($_POST['foto']));
  }

  if (empty($_POST['img'])){
    $error[]    = 'Foto tidak dapat di unggah!';
  } else {
    $img = $_POST['img'];
    $img = str_replace('data:image/jpeg;base64,', '', $img);
    $img = str_replace(' ', '+', $img);
    $fetch_imgParts = explode(";base64,", $img);
    $image_type_aux = explode("image/", $fetch_imgParts[0]);
    $image_type = $image_type_aux[1];
    $image_base64 = base64_decode($fetch_imgParts[1]);

    $im = imagecreatefromstring($image_base64);
    $source_width = imagesx($im);
    $source_height = imagesy($im);
    $ratio =  $source_height / $source_width;

    $new_width =400; // assign new width to new resized image
    $new_height = $ratio * 400;

    $thumb = imagecreatetruecolor($new_width, $new_height);
    $transparency = imagecolorallocatealpha($thumb, 255, 255, 255, 127);
    imagefilledrectangle($thumb, 0, 0, $new_width, $new_height, $transparency);
    imagecopyresampled($thumb, $im, 0, 0, 0, 0, $new_width, $new_height, $source_width, $source_height);
    $photo   = ''.seo_title(strtolower($data_user["nama_lengkap"])).''.time().'.jpg';
    $filename = '../../sw-content/kunjungan/'.$photo.'';
  }


if($foto==''){
  if (empty($error)){
      $update="UPDATE kunjungan SET lokasi='$lokasi',
              keterangan='$keterangan',
              date='$date',
              time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND kunjungan_id='$id'"; 
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
  /** Update Data kunjungan dengan foto */
        $query_kunjungan ="SELECT foto FROM kunjungan WHERE user_id='$data_user[user_id]' AND kunjungan_id='$id'";
        $result_kunjungan = $connection->query($query_kunjungan);
        if($result_kunjungan->num_rows > 0){
          $data_kunjungan = $result_kunjungan->fetch_assoc();
          if(file_exists("../../sw-content/kunjungan/".$data_kunjungan['foto']."")){
            unlink ('../../sw-content/kunjungan/'.$data_kunjungan['foto'].'');
          }
        }

        $update="UPDATE kunjungan SET lokasi='$lokasi',
            keterangan='$keterangan',
            foto='$photo',
            date='$date',
            time='$time_sekarang' WHERE user_id='$data_user[user_id]' AND kunjungan_id='$id'";

        if($connection->query($update) === false) { 
          echo'Sepertinya Sistem Kami sedang error!';
          die($connection->error.__LINE__); 
        } else{
          echo'success';
          imagepng($thumb, $filename, 8); 
        }
      
  }


  /** Delete kunjungan*/
  break;
  case 'delete':
  $id       = anti_injection(convert("decrypt",$_POST['id']));
  $query_kunjungan ="SELECT kunjungan_id,foto FROM kunjungan WHERE kunjungan_id='$id'";
  $result_kunjungan = $connection->query($query_kunjungan);
  if($result_kunjungan->num_rows > 0){
    $data_kunjungan = $result_kunjungan->fetch_assoc();

      $deleted = "DELETE FROM kunjungan WHERE kunjungan_id='$id'";
      if($connection->query($deleted) === true) {
        echo'success';
        if(file_exists("../../sw-content/kunjungan/".$data_kunjungan['foto']."")){
          unlink ('../../sw-content/kunjungan/'.$data_kunjungan['foto'].'');
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