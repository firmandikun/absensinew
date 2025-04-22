<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../module/oauth/user.php';

$max_size = 2000000; //2MB
$allowed_ext = array('jpg','jpeg','gif', 'png');
$uploadPath       = '../../sw-content/avatar/';

function resizeImage($resourceType,$image_width,$image_height){
  $resizeWidth = 500;
  $resizeHeight = ($image_height/$image_width)*$resizeWidth;
  $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
  imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
  return $imageLayer;
}

switch (@$_GET['action']){
case 'avatar':
$error = array();

  if (empty($_FILES['file']['name'])){
    $error[] = 'Foto Avatar tidak boleh kosong';
  } else {
    $files            = strip_tags($_FILES['file']['name']);
    $fileExt          = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $file_size        = $_FILES['file']['size'];
    $file_tmp         = $_FILES['file']['tmp_name'];
    $sourceProperties = getimagesize($file_tmp);
    $uploadImageType  = $sourceProperties[2];
    $sourceImageWidth = $sourceProperties[0];
    $sourceImageHeight = $sourceProperties[1];

    $files_name  = ''.time().'-'.seo_title($data_user['nama_lengkap']).'';
    $files       = ''.$files_name.'.'.$fileExt.'';
  }

  if (empty($error)){

    if(in_array($fileExt, $allowed_ext) === true){
      if ($file_size <= $max_size) {

        $query_avatar ="SELECT avatar FROM user WHERE user_id='$data_user[user_id]'";
        $result_avatar = $connection->query($query_avatar);
        if($result_avatar->num_rows > 0){
          $data_avatar = $result_avatar->fetch_assoc();
          if(file_exists("../../sw-content/avatar/".$data_avatar['avatar']."")){
            unlink ('../../sw-content/avatar/'.$data_avatar['avatar'].'');
          }
        }

        $update="UPDATE user SET avatar='$files' WHERE user_id='$data_user[user_id]'"; 
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

  }else{
    foreach ($error as $key => $values) {            
      echo"$values\n";
    }
  }

break;
}

}