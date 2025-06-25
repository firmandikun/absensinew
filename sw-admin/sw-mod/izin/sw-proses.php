<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';

$max_size = 5000000; //5MB
$allowed_ext = array('jpg','jpeg','gif', 'png');
$uploadPath       = '../../../sw-content/izin/';

function resizeImage($resourceType,$image_width,$image_height){
  $resizeWidth = 500;
  $resizeHeight = ($image_height/$image_width)*$resizeWidth;
  $imageLayer = imagecreatetruecolor($resizeWidth,$resizeHeight);
  imagecopyresampled($imageLayer,$resourceType,0,0,0,0,$resizeWidth,$resizeHeight, $image_width,$image_height);
  return $imageLayer;
}

switch (@$_GET['action']){

case 'add':
    $error = array();
    if (empty($_POST['id'])) {
      $id = '';
    } else {
      $id = anti_injection($_POST['id']);
    }

    if (empty($_POST['user_id'])) {
      $error[] = 'Siswa tidak boleh kosong';
    } else {
      $user_id = anti_injection($_POST['user_id']);
    }

    if (empty($_POST['atasan_id'])) {
      $error[] = 'Atasan harus dipilih';
    } else {
      $atasan_id = anti_injection($_POST['atasan_id']);
    }

    if (empty($_POST['tanggal'])) {
      $error[] = 'Tanggal Mulai tidak boleh kosong';
    } else {
      $tanggal = date('Y-m-d', strtotime($_POST['tanggal']));
    }

    if (empty($_POST['tanggal_selesai'])) {
      $error[] = 'Tanggal Selesai tidak boleh kosong';
    } else {
      $tanggal_selesai = date('Y-m-d', strtotime($_POST['tanggal_selesai']));
    }

    if (empty($_POST['jenis'])) {
      $error[] = 'Jenis Izin tidak boleh kosong';
    } else {
      $jenis = anti_injection($_POST['jenis']);
    }

    if (empty($_POST['keterangan'])) {
      $error[] = 'Keterangan tidak boleh kosong';
    } else {
      $keterangan = anti_injection($_POST['keterangan']);
    }

    
  if (empty($error)) {
    $query  = "SELECT izin_id FROM izin WHERE user_id='$user_id' AND izin_id='$id'";
    $result = $connection->query($query);
    if(!$result ->num_rows >0){
        
      /** User */
      $query_user   = "SELECT lokasi_id FROM user WHERE user_id='$user_id'";
      $result_user  = $connection->query($query_user);
      $data_user    = $result_user->fetch_assoc();
      /** End User */
      
      if (empty($_FILES['files']['name'])){
          $add ="INSERT INTO izin(user_id,
                        atasan_id,
                        tanggal_mulai,
                        tanggal_selesai,
                        files,
                        jenis,
                        keterangan,
                        time,
                        date,
                        status) values('$user_id',
                        '$atasan_id',
                        '$tanggal',
                        '$tanggal_selesai',
                        '',/** Foto */
                        '$jenis',
                        '$keterangan',
                        '$time',
                        '$date',
                        'Y')";
            if($connection->query($add) === false) { 
                die($connection->error.__LINE__); 
                echo'Data tidak berhasil disimpan!';
            } else{
                echo'success';
                while (strtotime($tanggal) <= strtotime($tanggal_selesai)) {
                  
                  $add_absen ="INSERT INTO absen (user_id,
                        tanggal,
                        lokasi_id,
                        jam_kerja_id,
                        jam_kerja_in,
                        jam_kerja_toleransi,
                        jam_kerja_out,
                        absen_in,
                        absen_out,
                        foto_in,
                        foto_out,
                        status_masuk,
                        status_pulang,
                        kehadiran,
                        latitude_longtitude_in,
                        latitude_longtitude_out,
                        radius,
                        tipe,
                        keterangan) values('$user_id',
                        '$tanggal',
                        '$data_user[lokasi_id]',
                        '0',
                        '00:00:00', /** jam_kerja In */
                        '00:00:00', /** Jam Toleransi */
                        '00:00:00', /** Jam Kerja Out */
                        '00:00:00', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '', /** Foto masuk kosong */
                        '', /** Foto pulang kosong */
                        '$jenis', /** Status Masuk */
                        '$jenis', /** Status pulang kosong */
                        'Izin', /** 1. Hadir */
                        '',
                        '', /** Latitude out */
                        'N',
                        'Izin',
                        '$jenis')"; /** Keterangan Kosong */
                  $connection->query($add_absen);
                  $tanggal = date('Y-m-d',strtotime('+1 days',strtotime($tanggal)));
                }
                
               
            }
          }else{
            /** Tambah Data Izin Dengan Foto */
            $file_name        = $_FILES['files']['name'];
            $fileExt          = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
            $file_size        = $_FILES['files']['size'];
            $file_tmp         = $_FILES['files']['tmp_name'];
                
            if(in_array($fileExt, $allowed_ext) === true){
              if ($file_size <= $max_size) {
          
            $sourceProperties = getimagesize($file_tmp);
            $uploadImageType  = $sourceProperties[2];
            $sourceImageWidth = $sourceProperties[0];
            $sourceImageHeight = $sourceProperties[1];
            
            $resizeFileName  = md5($file_name);
            $foto            = ''.$resizeFileName.'.'.$fileExt.'';

            $add ="INSERT INTO izin(user_id,
                        atasan_id,
                        tanggal_mulai,
                        tanggal_selesai,
                        files,
                        jenis,
                        keterangan,
                        time,
                        date,
                        status) values('$user_id',
                        '$atasan_id',
                        '$tanggal',
                        '$tanggal_selesai',
                        '$foto',
                        '$jenis',
                        '$keterangan',
                        '$time',
                        '$date',
                        'Y')";
            if($connection->query($add) === false) { 
                die($connection->error.__LINE__); 
                echo'Data tidak berhasil disimpan!';
            } else{
                echo'success';
                  while (strtotime($tanggal) <= strtotime($tanggal_selesai)) {
                    $add_absen ="INSERT INTO absen (user_id,
                            tanggal,
                            lokasi_id,
                            jam_kerja_id,
                            jam_kerja_in,
                            jam_kerja_toleransi,
                            jam_kerja_out,
                            absen_in,
                            absen_out,
                            foto_in,
                            foto_out,
                            status_masuk,
                            status_pulang,
                            kehadiran,
                            latitude_longtitude_in,
                            latitude_longtitude_out,
                            radius,
                            tipe,
                            keterangan) values('$user_id',
                            '$tanggal',
                            '$data_user[lokasi_id]',
                            '0',
                            '00:00:00', /** jam_kerja In */
                            '00:00:00', /** Jam Toleransi */
                            '00:00:00', /** Jam Kerja Out */
                            '00:00:00', /** absen masuk */
                            '00:00:00', /** Absen pulang kosong */
                            '', /** Foto masuk kosong */
                            '', /** Foto pulang kosong */
                            '$jenis', /** Status Masuk */
                            '$jenis', /** Status pulang kosong */
                            'Izin', /** 1. Hadir */
                            '',
                            '', /** Latitude out */
                            'N',
                            'Izin',
                            '$jenis')"; /** Keterangan Kosong */
                    $connection->query($add_absen);
                    $tanggal = date('Y-m-d',strtotime('+1 days',strtotime($tanggal)));
                  }

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
            }else{
              echo'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG,JPEG,GIF..!';
            }

          }
  
        }else{
          /* --  Update data -- */
          if (empty($_FILES['files']['name'])){
            /** Update Tanpa Upload Foto */
            $update="UPDATE izin SET user_id='$user_id',
                    jenis='$jenis',
                    keterangan='$keterangan',
                    time='$time',
                    date='$date' WHERE izin_id='$id'"; 
            if($connection->query($update) === false) { 
                die($connection->error.__LINE__); 
                echo'Data tidak berhasil disimpan!';
            } else{
                echo'success';
            }
          
          }else{
            /** Update Dengan Upload foto */
            $file_name        = $_FILES['files']['name'];
            $fileExt          = pathinfo($_FILES['files']['name'], PATHINFO_EXTENSION);
            $file_size        = $_FILES['files']['size'];
            $file_tmp         = $_FILES['files']['tmp_name'];
                
            if(in_array($fileExt, $allowed_ext) === true){
              if ($file_size <= $max_size) {

                $query_izin ="SELECT files FROM izin WHERE izin_id='$id'";
                $result_izin = $connection->query($query_izin);
                if($result_izin->num_rows > 0){
                  $data_izin = $result_izin->fetch_assoc();
                  if(file_exists("../../../sw-content/izin/".$data_izin['files']."")){
                    unlink ('../../../sw-content/izin/'.$data_izin['files'].'');
                  }
                }

          
            $sourceProperties = getimagesize($file_tmp);
            $uploadImageType  = $sourceProperties[2];
            $sourceImageWidth = $sourceProperties[0];
            $sourceImageHeight = $sourceProperties[1];
            
            $resizeFileName  = md5($file_name);
            $foto            = ''.$resizeFileName.'.'.$fileExt.'';
            
            $update="UPDATE izin SET user_id='$user_id',
                    jenis='$jenis',
                    files='$foto',
                    keterangan='$keterangan',
                    time='$time',
                    date='$date' WHERE izin_id='$id'"; 
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
            }else{
              echo'Gambar/Foto yang di unggah tidak sesuai dengan format, Berkas harus berformat JPG,JPEG,GIF..!';
            }
          
          }
          
    }
  }
  else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
  }


/** Data Update izin */
  break;
  case 'get-data-update':
  $id             = anti_injection(epm_decode($_POST['id']));
  $query_izin   = "SELECT * FROM izin WHERE izin_id='$id'";
  $result_izin  = $connection->query($query_izin);
  if($result_izin->num_rows > 0){
    $data_izin = $result_izin->fetch_assoc();
      $data['izin_id']        = $data_izin["izin_id"];
      $data['user_id']        = $data_izin["user_id"];
      $data['tanggal_mulai']  = tanggal_ind($data_izin["tanggal_mulai"]);
      $data['tanggal_selesai']= tanggal_ind($data_izin["tanggal_selesai"]);
      $data['jenis']          = strip_tags($data_izin["jenis"]);
      $data['keterangan']     = strip_tags($data_izin["keterangan"]);
      $data['files']          = strip_tags($data_izin["files"]);
    echo json_encode($data);
  }else{

  }
  

/** --------- Set Status ------ */
break;
case 'setujui':
  $id       = anti_injection(epm_decode($_POST['id']));
  $status   = htmlentities($_POST['status']);

      $query_izin = "SELECT user_id,tanggal_mulai,tanggal_selesai,jenis,status FROM izin WHERE  izin_id='$id'";
      $result_izin = $connection->query($query_izin);
      if($result_izin->num_rows > 0){
        $data_izin = $result_izin->fetch_assoc();
        $tanggal          = $data_izin['tanggal_mulai'];
        $tanggal_selesai  = $data_izin['tanggal_selesai'];

        /** User */
        $query_user   = "SELECT nama_lengkap,lokasi_id FROM user WHERE user_id='$data_izin[user_id]'";
        $result_user  = $connection->query($query_user);
        $data_user    = $result_user->fetch_assoc();
        /** End User */

        /** Notifikasi */
        $notifikasi ="INSERT INTO notifikasi (user_id,
                nama,
                keterangan,
                link,
                tanggal,
                datetime,
                tipe,
                status) values('$data_izin[user_id]',
                '$data_user[nama_lengkap]',
                'Permohonan Izin Anda disetujui',
                'izin',
                '$date',
                '$timeNow',
                '2',
                'N')";
      
        if($data_izin['status'] =='N' OR $data_izin['status'] =='-'){
            $update = "UPDATE izin SET status='$status' WHERE izin_id='$id'";
            if($connection->query($update) === false) { 
              echo'Data tidak dapat disimpan!';
              die($connection->error.__LINE__); 
            }else{
              echo'success';
              $connection->query($notifikasi);

                while (strtotime($tanggal) <= strtotime($tanggal_selesai)) {
                  $add_absen ="INSERT INTO absen (user_id,
                        tanggal,
                        lokasi_id,
                        jam_kerja_id,
                        jam_kerja_in,
                        jam_kerja_toleransi,
                        jam_kerja_out,
                        absen_in,
                        absen_out,
                        foto_in,
                        foto_out,
                        status_masuk,
                        status_pulang,
                        kehadiran,
                        latitude_longtitude_in,
                        latitude_longtitude_out,
                        radius,
                        tipe,
                        keterangan) values('$data_izin[user_id]',
                        '$tanggal',
                        '$data_user[lokasi_id]',
                        '0',
                        '00:00:00', /** jam_kerja In */
                        '00:00:00', /** Jam Toleransi */
                        '00:00:00', /** Jam Kerja Out */
                        '00:00:00', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '', /** Foto masuk kosong */
                        '', /** Foto pulang kosong */
                        '$data_izin[jenis]',
                        '$data_izin[jenis]', /** Status pulang kosong */
                        'Izin', /** 1. Hadir */
                        '',
                        '', /** Latitude out */
                        'N',
                        'Izin',
                        '$data_izin[jenis]')"; /** Keterangan Kosong */
                $connection->query($add_absen);
                $tanggal = date('Y-m-d',strtotime('+1 days',strtotime($tanggal)));
              }
            }
          }else{
            echo'Sebelumnya Data ini Sudah disetujui!';
          }
      }else{
        echo'Data tidak ditemukan!';
      }

  
break;
case 'update_supervisor_status':
    // Perlu epm_decode karena data-id di-encode
    $id = anti_injection(epm_decode($_POST['id']));
    $supervisor_status = $_POST['supervisor_status'];
    $allowed = ['pending', 'approved', 'rejected'];
    if (in_array($supervisor_status, $allowed)) {
        $update = "UPDATE izin SET supervisor_status='$supervisor_status' WHERE izin_id='$id'";
        if($connection->query($update) === false) {
            echo 'error';
        } else {
            echo 'success';
        }
    } else {
        echo 'Status supervisor tidak valid!';
    }
    break;
case 'tolak':
      $id       = anti_injection(epm_decode($_POST['id']));
      $status   = htmlentities($_POST['status']);
    
          $query_izin = "SELECT user_id,tanggal_mulai,tanggal_selesai,status FROM izin WHERE izin_id='$id'";
          $result_izin = $connection->query($query_izin);
          if($result_izin->num_rows > 0){
            $data_izin = $result_izin->fetch_assoc();
            $start = date('Y-m-d',strtotime('-1 days',strtotime($data_izin['tanggal_mulai'])));
            $finish = date('Y-m-d',strtotime('-1 days',strtotime($data_izin['tanggal_selesai'])));

            /** User */
            $query_user   = "SELECT nama_lengkap FROM user WHERE user_id='$data_izin[user_id]'";
            $result_user  = $connection->query($query_user);
            $data_user    = $result_user->fetch_assoc();
            /** End User */

            /** Notifikasi */
            $notifikasi ="INSERT INTO notifikasi (user_id,
                      nama,
                      keterangan,
                      link,
                      tanggal,
                      datetime,
                      tipe,
                      status) values('$data_izin[user_id]',
                      '$data_user[nama_lengkap]',
                      'Permohonan Izin Anda ditolak',
                      'izin',
                      '$date',
                      '$timeNow',
                      '2',
                      'N')";
              
          if($data_izin['status'] =='Y' OR $data_izin['status'] =='-'){
              $update = "UPDATE izin SET status='$status' WHERE izin_id='$id'";
              if($connection->query($update) === false) { 
                echo'error';
                die($connection->error.__LINE__); 
              }else{
                echo'success';
                $connection->query($notifikasi);

                while (strtotime($start) <= strtotime($finish)) {
                  $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
                  $deleted_absen = "DELETE FROM absen WHERE tanggal='$start' AND user_id='$data_izin[user_id]'";
                  $connection->query($deleted_absen);
                }
            }
          }else{
            echo'Sebelumnya Data ini Sudah disetujui!';
          }
      }else{
        echo'Data tidak temukan!';
      }
  

/* --------------- Delete ------------*/
    break;
    case 'delete':
        $id       = anti_injection(epm_decode($_POST['id']));
        $query_izin ="SELECT user_id,tanggal_mulai,tanggal_selesai,files,status FROM izin WHERE izin_id='$id'";
        $result_izin = $connection->query($query_izin);
        if($result_izin->num_rows > 0){
          $data_izin = $result_izin->fetch_assoc();

          if($data_izin['status'] =='N' OR $data_izin['status'] =='-'){
              $start = date('Y-m-d',strtotime('-1 days',strtotime($data_izin['tanggal_mulai'])));
              $finish = date('Y-m-d',strtotime('-1 days',strtotime($data_izin['tanggal_selesai'])));

              if(file_exists("../../sw-content/izin/".$data_izin['files']."")){
                  unlink ('../../../sw-content/izin/'.$data_izin['files'].''); 
              }
            /* Script Delete Data ------------*/
              $deleted = "DELETE FROM izin WHERE izin_id='$id'";
              if($connection->query($deleted) === true) {
                echo'success';
                if($data_izin['status'] =='Y'){
                  while (strtotime($start) <= strtotime($finish)) {
                    $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
                    $deleted_absen = "DELETE FROM absen WHERE tanggal='$start' AND user_id='$data_izin[user_id]'";
                    $connection->query($deleted_absen);
                  }
                }

              } else { 
                //tidak berhasil
                echo'Data tidak berhasil dihapus.!';
                die($connection->error.__LINE__);
              }
          }else{
            echo'Data Izin Telah disetujui dan tidak dapat dihapus!';
          }

        }else{
          echo 'Data tidak ditemukan!';
        }
   

break;
}}