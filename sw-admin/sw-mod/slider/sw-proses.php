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

switch (@$_GET['action']){
/* ---------- ADD  ---------- */
case 'add':
    $id = anti_injection($_POST['id']);
    $error = array();
    function resizeImage($resourceType,$image_width,$image_height){
      $resizeWidth = 700;
      $resizeHeight = ($image_height/$image_width)*$resizeWidth;
      $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
      imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
      return $imageLayer;
  }

    if (empty($_POST['slider_nama'])){
      $error[]    = 'Judul tidak boleh kosong';
      } else {
        $slider_nama = anti_injection($_POST['slider_nama']);
    }

    if (empty($_POST['slider_url'])){
        $error[]    = 'Url/Domain tidak boleh kosong';
      } else {
        $slider_url =htmlentities($_POST['slider_url']);
    }

  
    if($id == NULL){
      /** Add */
      if (empty($_FILES['foto']['name'])){
        $error[]    = 'Foto belum di unggah.!';
      } else {
        $file_name  = $_FILES['foto']['name'];
      }

      if (empty($error)) {
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
        $uploadPath       = '../../../sw-content/slider/';
        $foto            = ''.$resizeFileName.'.'.$fileExt.'';

        $add ="INSERT INTO slider (slider_nama,
                slider_url,
                foto,
                active) values('$slider_nama',
                '$slider_url',
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
          echo 'Foto terlalu besar Maksimal Size 5MB.!';
        }
        }
        else{
          echo'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG,JPEG,GIF..!';
        }
      }else{
        foreach ($error as $key => $values) {            
          echo $values;
        }
      }

    }else{
      /** Update */
      $file_name  = $_FILES['foto']['name'];
       if($file_name ==''){
        if (empty($error)) {
          /** Update Input */
          $update ="UPDATE slider SET slider_nama='$slider_nama', slider_url='$slider_url' WHERE slider_id='$id'";
          if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Sepertinya Sistem Kami sedang error!';
          } else{
              echo'success';
          }}else{           
            foreach ($error as $key => $values) {            
              echo $values;
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
                $uploadPath       = '../../../sw-content/slider/';
                $foto            = ''.$resizeFileName.'.'.$fileExt.'';
                $query ="SELECT foto from slider where slider_id='$id'";
                $result = $connection->query($query);
                      if($result->num_rows > 0){
                        $row = $result->fetch_assoc();
                        $foto_delete = strip_tags($row['foto']);
                        $tmpfile = "../../../sw-content/slider/".$foto_delete;
                        if(file_exists("../../../sw-content/slider/$foto_delete")){
                            unlink ($tmpfile);
                        }
                      }
                $update="UPDATE slider SET slider_nama='$slider_nama', slider_url='$slider_url', foto='$foto' WHERE slider_id='$id'";
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
  

/** Set Active Slider */
break;
case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE slider SET active='$active' WHERE slider_id='$id'";
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
        $query ="SELECT foto from slider where slider_id='$id'";
        $result = $connection->query($query);
            if($result->num_rows > 0){
              $row = $result->fetch_assoc();
              $foto_delete = strip_tags($row['foto']);
              $tmpfile = "../../../sw-content/slider/".$foto_delete;
              if(file_exists("../../../sw-content/slider/$foto_delete")){
                  unlink ($tmpfile);
              }
            }

        /* Script Delete Data ------------*/
          $deleted = "DELETE FROM slider WHERE slider_id='$id'";
          if($connection->query($deleted) === true) {
            echo'success';
          } else { 
            //tidak berhasil
            echo'Data tidak berhasil dihapus.!';
            die($connection->error.__LINE__);
          }


break;
}}