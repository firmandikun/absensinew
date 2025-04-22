<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../module/oauth/user.php';

switch (@$_GET['action']){
case 'data-recognition':
$query_wajah = "SELECT recognition_id,photo FROM recognition WHERE user_id='$data_user[user_id]'";
$result_wajah = $connection->query($query_wajah);
if($result_wajah->num_rows > 0){
  while($data_wajah = $result_wajah->fetch_assoc()) {
  echo'
  <div class="col-12 col-md-6">
    <div class="card border-0 mb-4 overflow-hidden">
    <div class="card-body position-relative" style="height:300px">
      <div class="background text-center">';
        if(file_exists('../../sw-content/labeled-images/'.$data_wajah['photo'].'')){
            echo'<img src="./sw-content/labeled-images/'.strip_tags($data_wajah['photo']).'" height="100%" width="100%">';
        }else{
            echo'<img src="./sw-content/thumbnail.jpg" height="100%" width="100%">';
        }
      echo'
      </div>
        <div class="bottom-left m-2">
          <a href="javascript:void(0);" class="btn btn-sm btn-danger rounded mt-3 btn-delete" data-id="'.convert("encrypt",$data_wajah['recognition_id']).'">
            <i class="fa-regular fa-trash-can"></i>
          </a>
        </div>
      </div>
    </div>
  </div>';
  }
}else{
  echo'<div class="alert alert-warning mt-3">Saat ini data <b>Foto wajah</b> masih kosong, Silahkan daftarkan wajah Anda pastikan ditempat yang terang dan jelas.</div>';
}

/** Tambah baru*/
break;
case 'add':
$error = array();
    
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
    
  }


if (empty($error)){
  /** Cek Foto Wajah */
  $query_wajah = "SELECT photo FROM recognition WHERE user_id='$data_user[user_id]'";
  $result_wajah = $connection->query($query_wajah);
  $data_wajah = $result_wajah->num_rows + 1;

    $photo   = ''.seo_title(strtolower($data_user["nama_lengkap"])).''.time().'.jpg';
    $filename = '../../sw-content/labeled-images/'.$photo.'';
      
      if($data_wajah < 2){
        $add ="INSERT INTO recognition (user_id, photo) values('$data_user[user_id]', '$photo')";

        if($connection->query($add) === false) { 
          echo'Sepertinya Sistem Kami sedang error!';
          die($connection->error.__LINE__); 
        } else{
          echo'success';
          imagepng($thumb, $filename, 8); 
        }
      }else{
        echo'Batas Foto Wajah maksimal hanya satu';
      }
    }else{       
      foreach ($error as $key => $values) {            
          echo"$values\n";
      }
  }
 


  /** Delete*/
  break;
  case 'delete':
  $id       = anti_injection(convert("decrypt",$_POST['id']));
  $query_wajah ="SELECT photo FROM recognition WHERE recognition_id='$id'";
  $result_wajah = $connection->query($query_wajah);
  if($result_wajah->num_rows > 0){
    $data_wajah = $result_wajah->fetch_assoc();
      $files = strip_tags($data_wajah['photo']);
      $tmpfile = '../../sw-content/labeled-images/'.$files.'';
      $deleted = "DELETE FROM recognition WHERE recognition_id='$id'";
      if($connection->query($deleted) === true) {
        echo'success';
        if(file_exists('../../sw-content/labeled-images/'.$data_wajah['photo'].'')){
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