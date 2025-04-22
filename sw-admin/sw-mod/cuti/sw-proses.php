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
$uploadPath       = '../../../sw-content/cuti/';

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
      $id ='';
    } else {
      $id = anti_injection($_POST['id']);
    }

    if (empty($_POST['user_id'])) {
      $error[] = 'Siswa tidak boleh kosong';
    } else {
      $user_id = anti_injection($_POST['user_id']);
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
    }

    if (empty($_POST['jumlah'])) { 
      $error[] = 'Jumlah tidak boleh kosong';
    } else {
      $jumlah = anti_injection($_POST['jumlah']);
    }

    if (empty($_POST['atasan'])) { 
      $error[] = 'Atasan tidak boleh kosong';
    } else {
      $atasan = anti_injection($_POST['atasan']);
    }

if($id== ''){
  if (empty($error)) {
    $query_cuti ="SELECT cuti_id FROM cuti WHERE tanggal_mulai='$tanggal_mulai' AND user_id='$user_id'";
    $result_cuti = $connection->query($query_cuti);
    if(!$result_cuti->num_rows > 0){

      $query_pegawai ="SELECT nama_lengkap,posisi_id,lokasi_id FROM user WHERE user_id='$user_id'";
      $result_pegawai = $connection->query($query_pegawai);
      $data_pegawai = $result_pegawai->fetch_assoc();

        $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_pegawai[posisi_id]' AND active='Y'";
        $result_hak_cuti = $connection->query($query_hak_cuti);
        if($result_hak_cuti->num_rows > 0){
            $data_hak_cuti = $result_hak_cuti->fetch_assoc();
            $jumlah_hak_cuti = $data_hak_cuti['jumlah'];
            
            $query_cuti ="SELECT SUM(jumlah) AS total FROM cuti WHERE user_id='$user_id' AND status='Y'";
            $result_cuti = $connection->query($query_cuti);
                  if($result_hak_cuti->num_rows > 0){
                    $data_cuti = $result_cuti->fetch_assoc();
                    $sisa_cuti = $jumlah_hak_cuti - $data_cuti['total'];
                  }else{
                    $sisa_cuti = $jumlah_hak_cuti;
                  }
                
                if($sisa_cuti > $jumlah){
                  if (empty($_FILES['files']['name'])){
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
                              status) values('$user_id',
                              '$data_pegawai[nama_lengkap]',
                              '$jenis',
                              '$tanggal_mulai',
                              '$tanggal_selesai',
                              '$jumlah',
                              '$keterangan',
                              '$atasan',
                              '', /** Upload kosong */
                              '$date',
                              '$time_sekarang',
                              'Y')";

                        if($connection->query($add) === false) { 
                          echo'Sepertinya Sistem Kami sedang error!';
                          die($connection->error.__LINE__); 
                        } else{
                          echo'success';
                          while (strtotime($tanggal_mulai) <= strtotime($tanggal_selesai)) {
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
                                        '$tanggal_mulai',
                                        '$data_pegawai[lokasi_id]',
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
                                        'Cuti', /** Hadir */
                                        '',
                                        '', /** Latitude out */
                                        'N',
                                        'Cuti',
                                        '$jenis')"; /** Keterangan Kosong */
                            $connection->query($add_absen);
                            $tanggal_mulai = date('Y-m-d',strtotime('+1 days',strtotime($tanggal_mulai)));
                          }
                        }

                    /**Upload Pakai Foto */
                    }else{

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
                              status) values('$user_id',
                              '$data_pegawai[nama_lengkap]',
                              '$jenis',
                              '$tanggal_mulai',
                              '$tanggal_selesai',
                              '$jumlah',
                              '$keterangan',
                              '$atasan',
                              '', /** Upload kosong */
                              '$date',
                              '$time_sekarang',
                              'Y')";

                        if($connection->query($add) === false) { 
                          echo'Sepertinya Sistem Kami sedang error!';
                          die($connection->error.__LINE__); 
                        } else{
                          echo'success';
                          while (strtotime($tanggal_mulai) <= strtotime($tanggal_selesai)) {
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
                                        '$tanggal_mulai',
                                        '$data_pegawai[lokasi_id]',
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
                                        'Cuti', /** Hadir */
                                        '',
                                        '', /** Latitude out */
                                        'N',
                                        'Cuti',
                                        '$jenis')"; /** Keterangan Kosong */
                            $connection->query($add_absen);
                            $tanggal_mulai = date('Y-m-d',strtotime('+1 days',strtotime($tanggal_mulai)));
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
                    echo'Hak cuti Anda "'.strip_tags($data_pegawai['nama_lengkap']).'" tidak cukup!';
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

}else{
  if (empty($error)) {
    /** Update Cuti */
    $query_pegawai ="SELECT nama_lengkap,posisi_id,lokasi_id FROM user WHERE user_id='$user_id'";
    $result_pegawai = $connection->query($query_pegawai);
    $data_pegawai = $result_pegawai->fetch_assoc();
    
    $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_pegawai[posisi_id]' AND active='Y'";
    $result_hak_cuti = $connection->query($query_hak_cuti);
    if($result_hak_cuti->num_rows > 0){
        $data_hak_cuti = $result_hak_cuti->fetch_assoc();
        $jumlah_hak_cuti = $data_hak_cuti['jumlah'];

        $query_cuti ="SELECT SUM(jumlah) AS total FROM cuti WHERE user_id='$user_id' AND status='Y'";
        $result_cuti = $connection->query($query_cuti);
          if($result_hak_cuti->num_rows > 0){
            $data_cuti = $result_cuti->fetch_assoc();
            $sisa_cuti = $jumlah_hak_cuti - $data_cuti['total'];
          }else{
            $sisa_cuti = $jumlah_hak_cuti;
          }
    
      if($sisa_cuti > $jumlah){
        if (empty($_FILES['files']['name'])){
            $update="UPDATE cuti SET user_id='$user_id',
                jenis='$jenis',
                tanggal_mulai='$tanggal_mulai',
                tanggal_selesai='$tanggal_selesai',
                jumlah='$jumlah',
                keterangan='$keterangan',
                atasan='$atasan',
                time='$time',
                date='$date' WHERE cuti_id='$id'"; 
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

              $query_cuti ="SELECT files FROM cuti WHERE cuti_id='$id'";
              $result_cuti = $connection->query($query_cuti);
              if($result_cuti->num_rows > 0){
                $data_cuti = $result_cuti->fetch_assoc();
                if(file_exists("../../../sw-content/cuti/".$data_cuti['files']."")){
                  unlink ('../../../sw-content/cuti/'.$data_cuti['files'].'');
                }
              }

                  $sourceProperties = getimagesize($file_tmp);
                  $uploadImageType  = $sourceProperties[2];
                  $sourceImageWidth = $sourceProperties[0];
                  $sourceImageHeight = $sourceProperties[1];
                  $resizeFileName  = md5($file_name);
                  $foto            = ''.$resizeFileName.'.'.$fileExt.'';  

                  $update="UPDATE cuti SET user_id='$user_id',
                    jenis='$jenis',
                    tanggal_mulai='$tanggal_mulai',
                    tanggal_selesai='$tanggal_selesai',
                    jumlah='$jumlah',
                    keterangan='$keterangan',
                    atasan='$atasan',
                    files='$files',
                    time='$time',
                    date='$date' WHERE cuti_id='$id'"; 
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

      }else{
        echo'Hak cuti Anda "'.strip_tags($data_pegawai['nama_lengkap']).'" tidak cukup!';
      }

    }else{
      echo'Data Hak cuti tidak ditemukan, Silahkan hubungi Admin!';
    }

  }else{
    foreach ($error as $key => $values) {            
      echo"$values\n";
    }
  }

}


/** Data Update Cuti */
break;
case 'get-data-update':
$id             = anti_injection(epm_decode($_POST['id']));
$query_cuti   = "SELECT * FROM cuti WHERE cuti_id='$id'";
$result_cuti  = $connection->query($query_cuti);
if($result_cuti->num_rows > 0){
  $data_cuti = $result_cuti->fetch_assoc();
    $data['cuti_id']        = $data_cuti["cuti_id"];
    $data['user_id']        = $data_cuti["user_id"];
    $data['jenis']          = strip_tags($data_cuti["jenis"]);
    $data['tanggal_mulai']  = tanggal_ind($data_cuti["tanggal_mulai"]);
    $data['tanggal_selesai']= tanggal_ind($data_cuti["tanggal_selesai"]);
    $data['keterangan']     = strip_tags($data_cuti["keterangan"]);
    $data['jumlah']         = strip_tags($data_cuti["jumlah"]);
    $data['atasan']         = strip_tags($data_cuti["atasan"]);
    $data['files']          = strip_tags($data_cuti["files"]);
  echo json_encode($data);
}else{
  echo'Data tidak ditemukan!';
}
  

/** --------- Set Status ------ */
break;
case 'setujui':
  $id       = anti_injection(epm_decode($_POST['id']));
  $status   = htmlentities($_POST['status']);

      $query_cuti = "SELECT user_id,tanggal_mulai,tanggal_selesai,jenis,status FROM cuti WHERE cuti_id='$id'";
      $result_cuti = $connection->query($query_cuti);
      if($result_cuti->num_rows > 0){
        $data_cuti        = $result_cuti->fetch_assoc();
        $tanggal          = $data_cuti['tanggal_mulai'];
        $tanggal_selesai  = $data_cuti['tanggal_selesai'];

        /** User */
        $query_user   = "SELECT nama_lengkap,lokasi_id FROM user WHERE user_id='$data_cuti[user_id]'";
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
              status) values('$data_cuti[user_id]',
              '$data_user[nama_lengkap]',
              'Permohonan Cuti Anda disetujui',
              'cuti',
              '$date',
              '$timeNow',
              '2',
              'N')";
      
        if($data_cuti['status'] =='N' OR $data_cuti['status'] =='-'){
            $update = "UPDATE cuti SET status='$status' WHERE cuti_id='$id'";
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
                          keterangan) values('$data_cuti[user_id]',
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
                          '$data_cuti[jenis]', /** Status Masuk */
                          '$data_cuti[jenis]', /** Status pulang kosong */
                          'Cuti', /** Hadir */
                          '',
                          '', /** Latitude out */
                          'N',
                          'Cuti',
                          '$data_cuti[jenis]')"; /** Keterangan Kosong */
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

/** Tolak */ 
break;
case 'tolak':
$id       = anti_injection(epm_decode($_POST['id']));
$status   = htmlentities($_POST['status']);

$query_cuti = "SELECT user_id,tanggal_mulai,tanggal_selesai,status FROM cuti WHERE cuti_id='$id'";
$result_cuti = $connection->query($query_cuti);
if($result_cuti->num_rows > 0){
  $data_cuti = $result_cuti->fetch_assoc();
  $start = date('Y-m-d',strtotime('-1 days',strtotime($data_cuti['tanggal_mulai'])));
  $finish = date('Y-m-d',strtotime('-1 days',strtotime($data_cuti['tanggal_selesai'])));

  /** User */
  $query_user   = "SELECT nama_lengkap FROM user WHERE user_id='$data_cuti[user_id]'";
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
          status) values('$data_cuti[user_id]',
          '$data_user[nama_lengkap]',
          'Permohonan Cuti Anda ditolak',
          'cuti',
          '$date',
          '$timeNow',
          '2',
          'N')";
    
if($data_cuti['status'] =='Y' OR $data_cuti['status'] =='-'){
    $update = "UPDATE cuti SET status='$status' WHERE cuti_id='$id'";
    if($connection->query($update) === false) { 
      echo'error';
      die($connection->error.__LINE__); 
    }else{
      echo'success';
      $connection->query($notifikasi);
      
      while (strtotime($start) <= strtotime($finish)) {
        $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
        $deleted_absen = "DELETE FROM absen WHERE tanggal='$start' AND user_id='$data_cuti[user_id]'";
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
        $query_cuti ="SELECT user_id,tanggal_mulai,tanggal_selesai,status FROM cuti WHERE cuti_id='$id'";
        $result_cuti = $connection->query($query_cuti);
        if($result_cuti->num_rows > 0){
          $data_cuti = $result_cuti->fetch_assoc();

          if($data_cuti['status'] =='N' OR $data_cuti['status'] =='-'){
              $start = date('Y-m-d',strtotime('-1 days',strtotime($data_cuti['tanggal_mulai'])));
              $finish = date('Y-m-d',strtotime('-1 days',strtotime($data_cuti['tanggal_selesai'])));

            /* Script Delete Data ------------*/
              $deleted = "DELETE FROM cuti WHERE cuti_id='$id'";
              if($connection->query($deleted) === true) {
                echo'success';
                if($data_cuti['status'] =='Y'){
                  while (strtotime($start) <= strtotime($finish)) {
                    $start = date('Y-m-d',strtotime('+1 days',strtotime($start)));
                    $deleted_absen = "DELETE FROM absen WHERE tanggal='$start' AND user_id='$data_cuti[user_id]'";
                    $connection->query($deleted_absen);
                  }
                }

              } else { 
                //tidak berhasil
                echo'Data tidak berhasil dihapus.!';
                die($connection->error.__LINE__);
              }
          }else{
            echo'Data Cuti Telah disetujui dan tidak dapat dihapus!';
          }

        }else{
          echo 'Data tidak ditemukan!';
        }
   

break;
}}