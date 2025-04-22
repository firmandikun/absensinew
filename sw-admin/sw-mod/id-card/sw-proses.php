<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';
$max_size = 3000000; //2MB
$allowed_ext = array('jpg','jpeg','gif', 'png');

function resizeImage($resourceType,$image_width,$image_height){
  $resizeWidth = 700;
  $resizeHeight = ($image_height/$image_width)*$resizeWidth;
  $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
  imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
  return $imageLayer;
}

switch (@$_GET['action']){
/* ---------- ADD  ---------- */
case 'add':
    $error = array();

    $id = anti_injection($_POST['id']);

    if (empty($_POST['nama'])){
      $error[]    = 'Nama Tema tidak boleh kosong';
      } else {
        $nama = anti_injection($_POST['nama']);
    }

    if($id == NULL){
      /** Add */
      if (empty($_FILES['foto']['name'])){
        $error[]    = 'Foto belum di unggah.!';
      } else {
        $file_name    = htmlentities($_FILES['foto']['name']);
        $fileExt      = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $file_size    = $_FILES['foto']['size'];
        $file_tmp     = $_FILES['foto']['tmp_name'];

        $sourceProperties   = getimagesize($file_tmp);
        $uploadImageType    = $sourceProperties[2];
        $sourceImageWidth   = $sourceProperties[0];
        $sourceImageHeight  = $sourceProperties[1];

        $resizeFileName   = 'tema-'.time().'';
        $uploadPath       = '../../../sw-content/tema/';
        $foto             = ''.$resizeFileName.'.'.$fileExt.'';
      }

      if (empty($error)) {
        if(in_array($fileExt, $allowed_ext) === true){
          if ($file_size <= $max_size) {

            $add ="INSERT INTO kartu_nama (nama,
                  foto,
                  active) values('$nama',
                  '$foto',
                  'Y')";
          
          if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Sepertinya Sistem Kami sedang error!';
          } else{
              echo'success';  
              switch ($uploadImageType) {
              case IMAGETYPE_JPEG:
                  $resourceType = imagecreatefromjpeg($file_tmp); 
                  $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                  imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                  break;
      
              case IMAGETYPE_GIF:
                  $resourceType = imagecreatefromgif($file_tmp); 
                  $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                  imagegif($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                  break;
      
              case IMAGETYPE_PNG:
                  $resourceType = imagecreatefrompng($file_tmp); 
                  $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                  imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                  break;
      
              default:
                  $imageProcess = 0;
              break;
            }
          }

         }else{
          echo 'Foto terlalu besar Maksimal Size 2MB.!';
        }
        }
        else{
          echo'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG,JPEG,GIF..!';
        }
      }else{
        foreach ($error as $key => $values) {            
          echo"$values\n";
        }
      }

    }else{
      /** Update */
        $file_name  = $_FILES['foto']['name'];
       if($file_name ==''){
        if (empty($error)) {
          /** Update Input */
          $update ="UPDATE kartu_nama SET nama='$nama' WHERE kartu_nama_id='$id'";
          if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Sepertinya Sistem Kami sedang error!';
          } else{
              echo'success';
          }}else{           
            foreach ($error as $key => $values) {            
              echo "$values\n";
            }
          }
          /** End Update input */
       }else{
        /** Update foto */
            $fileExt          = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
            $file_size        = $_FILES['foto']['size'];
            $file_tmp         = $_FILES['foto']['tmp_name'];
                
            if(in_array($fileExt, $allowed_ext) === true){
            if ($file_size <= $max_size) {

                $sourceProperties = getimagesize($file_tmp);
                $uploadImageType  = $sourceProperties[2];
                $sourceImageWidth = $sourceProperties[0];
                $sourceImageHeight = $sourceProperties[1];

                $resizeFileName   = 'slider-'.$date.'-'.time().'';
                $uploadPath       = '../../../sw-content/tema/';
                $foto            = ''.$resizeFileName.'.'.$fileExt.'';

                $query ="SELECT foto FROM kartu_nama where kartu_nama_id='$id'";
                $result = $connection->query($query);
                if($result->num_rows > 0){
                  $row = $result->fetch_assoc();
                  if(file_exists("../../../sw-content/tema/".$row['foto']."")){
                      unlink ("../../../sw-content/tema/".$row['foto']."");
                  }
                }
                $update="UPDATE kartu_nama SET nama='$nama', foto='$foto' WHERE kartu_nama_id='$id'";
                if($connection->query($update) === false) { 
                  die($connection->error.__LINE__); 
                  echo'Sepertinya Sistem Kami sedang error!';
              } else{
                  echo'success';  
                  switch ($uploadImageType) {
                  case IMAGETYPE_JPEG:
                      $resourceType = imagecreatefromjpeg($file_tmp); 
                      $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                      imagejpeg($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                      break;
          
                  case IMAGETYPE_GIF:
                      $resourceType = imagecreatefromgif($file_tmp); 
                      $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                      imagegif($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                      break;
          
                  case IMAGETYPE_PNG:
                      $resourceType = imagecreatefrompng($file_tmp); 
                      $imageLayer = resizeImage($resourceType,$sourceImageWidth,$sourceImageHeight);
                      imagepng($imageLayer,$uploadPath."".$resizeFileName.'.'. $fileExt);
                      break;
          
                  default:
                      $imageProcess = 0;
                  break;
                  }
                }
              }
              else{
                  echo 'Foto terlalu besar Maksimal Size 5MB.!';
                }
              }
              else{
                echo'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG,JPEG,GIF..!';
              }

       }
    }
  

/** Set Active */
break;
case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE kartu_nama SET active='$active' WHERE kartu_nama_id='$id'";
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
        $query ="SELECT foto FROM kartu_nama where kartu_nama_id='$id'";
        $result = $connection->query($query);
            if($result->num_rows > 0){
              $row = $result->fetch_assoc();
              if(file_exists("../../../sw-content/tema/".$row['foto']."")){
                  unlink ("../../../sw-content/tema/".$row['foto']."");
              }
            }

        /* Script Delete Data ------------*/
          $deleted = "DELETE FROM kartu_nama WHERE kartu_nama_id='$id'";
          if($connection->query($deleted) === true) {
            echo'success';
          } else { 
            //tidak berhasil
            echo'Data tidak berhasil dihapus.!';
            die($connection->error.__LINE__);
          }


break;
}}