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
$uploadPath       = '../../../sw-content/artikel/thumbnail/';

function resizeImage($resourceType,$image_width,$image_height){
  $resizeWidth = 500;
  $resizeHeight = ($image_height/$image_width)*$resizeWidth;
  $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
  imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
  return $imageLayer;
}

switch (@$_GET['action']){
/* ---------- ADD  ---------- */
case 'add':
    $error = array();

    if (empty($_POST['judul'])) {
      $error[] = 'Judul tidak boleh kosong';
    } else {
      $judul = anti_injection($_POST['judul']);
      $domain = seo_title($judul);
    }

    if (empty($_POST['deskripsi'])) {
        $error[] = 'Deskripsi tidak boleh kosong';
      } else {
        $deskripsi = mysqli_real_escape_string($connection,$_POST['deskripsi']);
    }

    if (empty($_POST['kategori'])) {
      $error[] = 'Kategori tidak boleh kosong';
    } else {
      $kategori = anti_injection($_POST['kategori']);
    }

    if (empty($_POST['date'])) {
        $error[] = 'Tanggal tidak boleh kosong';
      } else {
        $date = date('Y-m-d', strtotime($_POST['date']));
    }

    if (empty($_POST['time'])) {
      $error[] = 'Waktu tidak boleh kosong';
    } else {
      $time = strip_tags($_POST['time']);
    }

    if (empty($_FILES['foto']['name'])){
      $error[]    = 'Thumbnail belum di unggah.!';
    } else {
      $file_name  = $_FILES['foto']['name'];
    }

    if (empty($_POST['active'])) {
      $active ='N';
    } else {
      $active ='Y';
    }

  if (empty($error)){
        $fileExt          = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $file_size        = $_FILES['foto']['size'];
        $file_tmp         = $_FILES['foto']['tmp_name'];
            
        if(in_array($fileExt, $allowed_ext) === true){
          if ($file_size <= $max_size) {

        $sourceProperties = getimagesize($file_tmp);
        $uploadImageType  = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        
        $resizeFileName  = seo_title($file_name);
        $foto            = ''.$resizeFileName.'.'.$fileExt.'';

        $add ="INSERT INTO artikel(penerbit,
                    judul,
                    domain,
                    deskripsi,
                    foto,
                    kategori,
                    time,
                    date,
                    statistik,
                    active) values('$current_user[fullname]',
                    '$judul',
                    '$domain',
                    '$deskripsi',
                    '$foto',
                    '$kategori',
                    '$time',
                    '$date',
                    '0',
                    '$active')";
        if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
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
      }
      else{           
          foreach ($error as $key => $values) {            
            echo"$values\n";
          }
      }


/* -------------- Update ----------*/
break;
case 'update':
$error = array();
    if (empty($_POST['id'])) {
      $error[] = 'ID tidak ditemukan';
    } else {
      $id = anti_injection(epm_decode($_POST['id']));
    }

    if (empty($_POST['judul'])) {
      $error[] = 'Judul tidak boleh kosong';
    } else {
      $judul = anti_injection($_POST['judul']);
      $domain = seo_title($judul);
    }

    if (empty($_POST['deskripsi'])) {
        $error[] = 'Deskripsi tidak boleh kosong';
      } else {
        $deskripsi = mysqli_real_escape_string($connection,$_POST['deskripsi']);
    }

    if (empty($_POST['kategori'])) {
      $error[] = 'Kategori tidak boleh kosong';
    } else {
      $kategori = anti_injection($_POST['kategori']);
    }

    if (empty($_POST['date'])) {
        $error[] = 'Tanggal tidak boleh kosong';
      } else {
        $date = date('Y-m-d', strtotime($_POST['date']));
    }

    if (empty($_POST['time'])) {
      $error[] = 'Waktu tidak boleh kosong';
    } else {
      $time = strip_tags($_POST['time']);
    }


    if (empty($_POST['active'])) {
      $active ='N';
    } else {
      $active ='Y';
    }

    
if (empty($_FILES['foto']['name'])){

      if (empty($error)) {
        $update="UPDATE artikel SET judul='$judul',
                domain='$domain',
                deskripsi='$deskripsi',
                kategori='$kategori',
                time='$time',
                date='$date',
                active='$active' WHERE artikel_id='$id'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo'success';
        }}
      else{
        foreach ($error as $key => $values) {            
          echo"$values\n";
        }
      }
  }else{
    /** Update and upload foto */
        $file_name  = $_FILES['foto']['name'];
        $fileExt          = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $file_size        = $_FILES['foto']['size'];
        $file_tmp         = $_FILES['foto']['tmp_name'];
            
        if(in_array($fileExt, $allowed_ext) === true){
          if ($file_size <= $max_size) {

        $sourceProperties = getimagesize($file_tmp);
        $uploadImageType  = $sourceProperties[2];
        $sourceImageWidth = $sourceProperties[0];
        $sourceImageHeight = $sourceProperties[1];
        
        $resizeFileName  = seo_title($file_name);
        $foto            = ''.$resizeFileName.'.'.$fileExt.'';

        $update="UPDATE artikel SET judul='$judul',
                domain='$domain',
                deskripsi='$deskripsi',
                foto='$foto',
                kategori='$kategori',
                time='$time',
                date='$date',
                active='$active' WHERE artikel_id='$id'";

        if($connection->query($update) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
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
  }


/* --------------- Delete ------------*/
break;
case 'delete':
  $id       = anti_injection(epm_decode($_POST['id']));
  /* Script Delete Foto Lama dan Qr Code ------------*/
  $query ="SELECT foto from artikel where artikel_id='$id'";
  $result = $connection->query($query);
  if($result->num_rows > 0){
    $row = $result->fetch_assoc();
    $foto = strip_tags($row['foto']);
    $tmpfile_foto= "../../../sw-content/artikel/thumbnail/".$foto;
        if(file_exists("../../../sw-content/artikel/thumbnail/$foto")){
            /** avatar udah diubah maka hapus */
            unlink ($tmpfile_foto); 
        }
    }
/* Script Delete Data ------------*/
$deleted  = "DELETE FROM artikel WHERE artikel_id='$id'";
if($connection->query($deleted) === true) {
    echo'success';
  } else { 
    //tidak berhasil
    echo'Data tidak berhasil dihapus.!';
    die($connection->error.__LINE__);
}


/** Tambah Kategori */
break;
case 'add-kategori':
$error = array();

    if (empty($_POST['title'])) {
      $error[] = 'Judul tidak boleh kosong';
    } else {
      $title    = anti_injection($_POST['title']);
      $seotitle = seo_title($title);
    }

  if (empty($error)){
    $query_kategori="SELECT title from kategori WHERE title='$title'";
    $result_kategori = $connection->query($query_kategori);
    if(!$result_kategori->num_rows > 0){

        $add ="INSERT INTO kategori (title,seotitle) values('$title','$seotitle')";
        if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'error/Data tidak berhasil disimpan!';
          } else{
              $query="SELECT * from kategori order by title ASC";
              $result = $connection->query($query);
              while($row = $result->fetch_assoc()) { 
                  echo'<option value="'.$row['seotitle'].'">'.strip_tags($row['title']).'</option>';
              }
          }
        }else{
          echo'error/Kategori '.$title.' sudah ada';
        }
      }
      else{           
          foreach ($error as $key => $values) {   
            echo'error/';         
            echo"$values\n";
          }
      }

      

break;
}}