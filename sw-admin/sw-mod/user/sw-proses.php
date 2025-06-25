<?php use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\Exception;
      session_start();
      
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';
require_once'../../../sw-library/phpqrcode/qrlib.php'; 

require_once '../../../sw-library/PHPMailer/Exception.php';
require_once '../../../sw-library/PHPMailer/PHPMailer.php';
require_once '../../../sw-library/PHPMailer/SMTP.php';

$iB 			       = getBrowser();
$browser 		     = $iB['name'].' '.$iB['version'];
$ip 		         = $_SERVER['REMOTE_ADDR'];
$time_online     = time();

$max_size = 20000000; //2MB
$allowed_ext = array('jpg','jpeg','gif', 'png');
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

    if (empty($_POST['nip'])) {
      $error[] = 'NIP tidak boleh kosong';
    } else {
      $nip = anti_injection($_POST['nip']);
    }

    if (empty($_POST['nama_lengkap'])) {
        $error[] = 'Nama Lengkap tidak boleh kosong';
      } else {
        $nama_lengkap = anti_injection($_POST['nama_lengkap']);
    }

    if (empty($_POST['tempat_lahir'])) {
      $error[] = 'Tempat Lahir tidak boleh kosong';
    } else {
      $tempat_lahir = anti_injection($_POST['tempat_lahir']);
    }

    if (empty($_POST['tanggal_lahir'])) {
        $error[] = 'Tanggal Lahir tidak boleh kosong';
      } else {
        $tanggal_lahir = date('Y-m-d', strtotime($_POST['tanggal_lahir']));
    }

    if (empty($_POST['jenis_kelamin'])) {
      $error[] = 'Jenis Kelamin tidak boleh kosong';
    } else {
      $jenis_kelamin = anti_injection($_POST['jenis_kelamin']);
    }

    if (empty($_POST['telp'])) {
      $error[] = 'No. Telp tidak boleh kosong';
    } else {
      $telp = anti_injection($_POST['telp']);
    }

    if (empty($_POST['alamat'])) {
      $error[] = 'Alamat Lengkap tidak boleh kosong';
    } else {
      $alamat = anti_injection($_POST['alamat']);
    }

    if (empty($_POST['email'])) {
      $error[] = 'Email tidak boleh kosong';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $error[] = "Email yang Anda masukan tidak valid"; 
        }else{
          $email = htmlentities(strip_tags($_POST['email']));
        }
    }

    if (empty($_POST['password'])) {
      $error[] = 'Password tidak boleh kosong';
    } else {
      $password = htmlentities(strip_tags($_POST['password']));
      $password = password_hash($password,PASSWORD_DEFAULT);
    }

    if (empty($_POST['lokasi'])) {
      $error[] = 'Lokasi Kerja tidak boleh kosong';
    } else {
      $lokasi = anti_injection($_POST['lokasi']);
    }

    if (empty($_POST['posisi'])) {
      $error[] = 'Posisi tidak boleh kosong';
    } else {
      $posisi = anti_injection($_POST['posisi']);
    }

    $libur = isset($_POST['libur']) ? implode(',', $_POST['libur']) : '';
    
    $atasan_id = !empty($_POST['atasan_id']) ? anti_injection($_POST['atasan_id']) : 'NULL';
    
  if (empty($error)) {

  $query="SELECT email from user where email='$email'";
  $result= $connection->query($query) or die($connection->error.__LINE__);
  if(!$result ->num_rows >0){

        $query_user   ="SELECT max(user_id) as kode_max FROM user";
        $result_user  = $connection->query($query_user);
        $row_user     = $result_user->fetch_assoc();
        $user_id      = $row_user['kode_max'];
        $user_id++;

        /* --  Membuat Random Karakter ---- */
        $random_karakter = md5($nama_lengkap);
        $shuffle  = substr(str_shuffle($random_karakter),0,5);
        $qrcode   = ''.strtoupper($shuffle).'-'.$user_id.'';
        /* --  End Random Karakter ---- */

        $codeContents = $qrcode;
        $tempdir = '../../../sw-content/qrcode/';
        $namafile = ''.seo_title($codeContents).'.jpg';

        $add ="INSERT INTO user(user_id,
                    email,
                    password,
                    nip,
                    nama_lengkap,
                    tempat_lahir,
                    tanggal_lahir,
                    jenis_kelamin,
                    telp,
                    alamat,
                    lokasi_id,
                    posisi_id,
                    atasan_id,
                    qrcode,
                    avatar,
                    tanggal_registrasi,
                    tanggal_login,
                    time,
                    ip,
                    browser,
                    status,
                    active,
                    libur) values('$user_id',
                    '$email',
                    '$password',
                    '$nip',
                    '$nama_lengkap',
                    '$tempat_lahir',
                    '$tanggal_lahir',
                    '$jenis_kelamin',
                    '$telp',
                    '$alamat',
                    '$lokasi',
                    '$posisi',
                    $atasan_id,
                    '$qrcode',
                    'avatar.jpg',
                    '$date $time',
                    '$date $time',
                    '$date $time',
                    '$ip',
                    '$browser',
                    'Offline',
                    'Y',
                    '$libur')";
        if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
          } else{
              echo'success';
              if(file_exists('../../../sw-content/upload/qrcode/'.$namafile.'')){}else{
                $quality = 'QR_ECLEVEL_Q'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
                $ukuran = 10; //batasan 1 paling kecil, 10 paling besar
                $padding = 1;
                QRCode::png($codeContents,$tempdir.$namafile,$quality,$ukuran,$padding);
              }
          }
        }
      else{
        echo'Sepertinya Email "'.$email.'" sudah terdaftar!';
      }}
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

    if (empty($_POST['nip'])) {
      $error[] = 'NIP tidak boleh kosong';
    } else {
      $nip = anti_injection($_POST['nip']);
    }

    if (empty($_POST['nama_lengkap'])) {
        $error[] = 'Nama Lengkap tidak boleh kosong';
      } else {
        $nama_lengkap = anti_injection($_POST['nama_lengkap']);
    }

    if (empty($_POST['tempat_lahir'])) {
      $error[] = 'Tempat Lahir tidak boleh kosong';
    } else {
      $tempat_lahir = anti_injection($_POST['tempat_lahir']);
    }

    if (empty($_POST['tanggal_lahir'])) {
        $error[] = 'Tanggal Lahir tidak boleh kosong';
      } else {
        $tanggal_lahir = date('Y-m-d', strtotime($_POST['tanggal_lahir']));
    }

    if (empty($_POST['jenis_kelamin'])) {
      $error[] = 'Jenis Kelamin tidak boleh kosong';
    } else {
      $jenis_kelamin = anti_injection($_POST['jenis_kelamin']);
    }

    if (empty($_POST['telp'])) {
      $error[] = 'No. Telp tidak boleh kosong';
    } else {
      $telp = anti_injection($_POST['telp']);
    }

    if (empty($_POST['alamat'])) {
      $error[] = 'Alamat Lengkap tidak boleh kosong';
    } else {
      $alamat = anti_injection($_POST['alamat']);
    }

    if (empty($_POST['email'])) {
      $error[] = 'Email tidak boleh kosong';
    } else {
        if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
          $error[] = "Email yang Anda masukan tidak valid"; 
        }else{
          $email = htmlentities(strip_tags($_POST['email']));
        }
    }

    if (empty($_POST['lokasi'])) {
      $error[] = 'Lokasi Kerja tidak boleh kosong';
    } else {
      $lokasi = anti_injection($_POST['lokasi']);
    }

    if (empty($_POST['posisi'])) {
      $error[] = 'Posisi tidak boleh kosong';
    } else {
      $posisi = anti_injection($_POST['posisi']);
    }

    $libur = isset($_POST['libur']) ? implode(',', $_POST['libur']) : '';
    
    $atasan_id = !empty($_POST['atasan_id']) ? anti_injection($_POST['atasan_id']) : 'NULL';
    
  if (empty($error)) {
    $update="UPDATE user SET nip='$nip',
            email='$email',
            nama_lengkap='$nama_lengkap',
            tempat_lahir='$tempat_lahir',
            tanggal_lahir='$tanggal_lahir',
            jenis_kelamin='$jenis_kelamin',
            telp='$telp',
            alamat='$alamat',
            lokasi_id='$lokasi',
            posisi_id='$posisi',
            atasan_id=$atasan_id,
            libur='$libur' WHERE user_id='$id'"; 
    if($connection->query($update) === false) { 
        die($connection->error.__LINE__); 
        echo'Data tidak berhasil disimpan!';
    } else{
        echo'success';
    }
  }else{
    foreach ($error as $key => $values) {            
      echo"$values\n";
    }
  }


/* ----------------- Forgot/Resset Password -----------*/
break;
case 'forgot':
$error = array();
  if (empty($_POST['id'])) {
      $error[] = 'ID tidak ditemukan, Silahkan coba kembali';
    } else {
      $id       = anti_injection(epm_decode($_POST['id']));
  }

  $password = '123456';
  $password = password_hash($password,PASSWORD_DEFAULT);
  
    $query_user="SELECT nama_lengkap,email from user WHERE user_id='$id'";
    $result_user= $connection->query($query_user);
		if($result_user ->num_rows >0){
		  $row_user = $result_user->fetch_assoc();
        
      // Konfigurasi SMTP
	      if($gmail_active =='Y'){
	        $mail = new PHPMailer;
	        $mail->isSMTP();
	        $mail->Host = $gmail_host;
	        $mail->Username = $gmail_username; // Email Pengirim
	        $mail->Password = $gmail_password; // Isikan dengan Password email pengirim
	        $mail->Port = $gmail_port;
	        $mail->SMTPAuth = true;
	        $mail->SMTPSecure = 'ssl';
	        //$mail->SMTPDebug = 2; // Aktifkan untuk melakukan debugging

	        $mail->setFrom($gmail_username, $site_name);  //Email Pengirim
	        $mail->addAddress($row_user['email'], $row_user['nama_lengkap']); // Email Penerima

	        $mail->isHTML(true); // Aktifkan jika isi emailnya berupa html
	        // Subjek email
	        $mail->Subject = 'Resset password Baru | '.$site_name.'';

	        $mailContent = '<h1>'.$site_name.'</h1><br>
	            <h3>Halo, '.$row_user['nama_lengkap'].'</h3><br>
	            <p>Selamat akun anda berahsil kami reset ulang, silahkan login dengan password baru dibawah ini<br>
	            Email : '.$row_user['email'].'
	            <b>Password Baru Anda : 123456</b><br>
	            IP : '.$ip.'<br>Browser : '.$browser.'<br><br><br><br>
	            Harap simpan baik-baik akun Anda.<br><br>
	            Hormat Kami,<br>'.$site_name.'<br>Email otomatis, Mohon tidak membalas email ini</p>';
	        $mail->Body = $mailContent;
	        //$mail->AddEmbeddedImage(''.$site_name.'', '../../../sw-content/'.$site_logo.''); //Logo 
	      }

  if(empty($error)){
      $update="UPDATE user SET password='$password' WHERE user_id='$id'";
        if($connection->query($update) === false) { 
          die($connection->error.__LINE__); 
          echo'Sepertinya Sistem Kami sedang error!';
        } else{
          echo'success';
          if($gmail_active =='Y'){
            if($mail->send()){
              //echo 'Pesan telah terkirim';
            }else{
              echo 'Mailer Error: Send email hanya bekerja saat online' . $mail->ErrorInfo;
            }
          }
        }
      }else{
        echo'Akun Anda tidak ditemukan, silahkan cek kembali.!';
      }
    }else{           
        foreach ($error as $key => $values) {            
          echo $values;
        }
    }


    /** Setactive user */

break;
case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE user SET active='$active' WHERE user_id='$id'";
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
        /* Script Delete Foto Lama dan Qr Code ------------*/
        $query ="SELECT qrcode,avatar from user where user_id='$id'";
        $result = $connection->query($query);
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
            /** Delete Qrcode */
            $qrcode_delete =''.seo_title($row['qrcode']).'.jpg';
            $tmpfile = "../../../sw-content/qrcode/".$qrcode_delete;
              if(file_exists("../../../sw-content/qrcode/$qrcode_delete")){
                 unlink ($tmpfile); 
              }
            /** Delete Avatar */
            $avatar_delete = strip_tags($row['avatar']);
            $tmpfile_aavatar = "../../../sw-content/avatar/".$avatar_delete;
              if(file_exists("../../../sw-content/avatar/$avatar_delete")){
                 if($avatar_delete==='avatar.jpg'){
                  /**avatar default tidak kehapus */
                 }else{
                  /** avatar udah diubah maka hapus */
                  unlink ($tmpfile_avatar); 
                 }
              }
              
          }
    /* Script Delete Data ------------*/
      $deleted  = "DELETE FROM user WHERE user_id='$id'";
      if($connection->query($deleted) === true) {
          echo'success';
        } else { 
          //tidak berhasil
          echo'Data tidak berhasil dihapus.!';
          die($connection->error.__LINE__);
      }




/** Avatar */
break;
case 'avatar':

    if (empty($_GET['id'])){
      $error[]    = 'ID tidak bolh kosong!';
    } else {
      $id = anti_injection($_GET['id']);
    }

    if (empty($_FILES['avatar']['name'])){
      $error[]    = 'Foto belum di unggah.!';
    } else {
      $file_name        = $_FILES['avatar']['name'];
      $fileExt          = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
      $file_size        = $_FILES['avatar']['size'];
      $file_tmp         = $_FILES['avatar']['tmp_name'];

      $sourceProperties = getimagesize($file_tmp);
      $uploadImageType  = $sourceProperties[2];
      $sourceImageWidth = $sourceProperties[0];
      $sourceImageHeight = $sourceProperties[1];

      $resizeFileName   = 'avatar-'.$id.''.time().'';
      $uploadPath       = '../../../sw-content/avatar/';
      $foto            = ''.$resizeFileName.'.'.$fileExt.'';
    }
  if (empty($error)) { 
    if(in_array($fileExt, $allowed_ext) === true){
      if ($file_size <= $max_size) {

      $query = "SELECT avatar FROM user WHERE user_id='$id'"; 
      $result = $connection->query($query);
      $rows= $result->fetch_assoc();
      $avatar = $rows['avatar'];
          if(file_exists("../../../sw-content/avatar/$avatar")){
            if($avatar == 'avatar.jpg'){
              //Jika avatar.kpg makan tidak hapus file
            }else{
              unlink( "../../../sw-content/avatar/$avatar");
            }
          }
          $update ="UPDATE user SET avatar='$foto' WHERE user_id='$id'";
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

/* ----- Import -------*/
break;
case 'import':
// Allowed mime types
$csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

if(!empty($_FILES['files']['name']) && in_array($_FILES['files']['type'], $csvMimes)){
        // If the file is uploaded
        if(is_uploaded_file($_FILES['files']['tmp_name'])){
            // Open uploaded CSV file with read-only mode
            $csvFile = fopen($_FILES['files']['tmp_name'], 'r');
    
            // Skip the first line
            fgetcsv($csvFile);
            
            // Parse data from CSV file line by line
            while(($line = fgetcsv($csvFile)) !== FALSE){
                $query_user="SELECT max(user_id) as kode_max FROM user";
                $result_user = $connection->query($query_user);
                $row_user = $result_user->fetch_assoc();
                $user_id = $row_user['kode_max'];
                $user_id++;
                
                $query_lokasi   = "SELECT lokasi_id FROM lokasi  ORDER BY lokasi_id ASC";
                $result_lokasi  = $connection->query($query_lokasi);
                $data_lokasi    = $result_lokasi->fetch_assoc();

                $query_posisi   = "SELECT posisi_id FROM posisi ORDER BY posisi_id ASC";
                $result_posisi  = $connection->query($query_posisi);
                $data_posisi    = $result_posisi->fetch_assoc();


                // Get row data -------- //
                $nip              = strip_tags($line[0]);
                $nama_lengkap     = strip_tags($line[1]);
                $email            = strip_tags($line[2]);
                $password         = strip_tags($line[3]);
                $password         = password_hash($password,PASSWORD_DEFAULT);
                $tempat_lahir     = strip_tags($line[4]);
                $tanggal_lahir    = date('Y-m-d', strtotime($line[5]));
                $jenis_kelamin    = strip_tags($line[6]);
                $telp             = strip_tags($line[7]);
                $alamat           = strip_tags($line[8]);

                /* --  Membuat Random Karakter ---- */
                $random_karakter = md5($nama_lengkap);
                $shuffle  = substr(str_shuffle($random_karakter),0,5);
                $qrcode   = ''.strtoupper($shuffle).'-'.$user_id.'';
                /* --  End Random Karakter ---- */

                $codeContents = $qrcode;
                $tempdir = '../../../sw-content/qrcode/';
                $namafile = ''.seo_title($codeContents).'.jpg';

              // Check berdasa  ID
              $query  = "SELECT user_id FROM user WHERE email='$email'";
              $result = $connection->query($query);
              if($result->num_rows > 0){
                  $row = $result->fetch_assoc();
                // Update member data in the database
                $update="UPDATE user SET nip='$nip',
                                  email='$email',
                                  nama_lengkap='$nama_lengkap',
                                  tempat_lahir='$tempat_lahir',
                                  tanggal_lahir='$tanggal_lahir',
                                  jenis_kelamin='$jenis_kelamin',
                                  telp='$telp',
                                  alamat='$alamat',
                                  lokasi_id='$data_lokasi[lokasi_id]',
                                  posisi_id='$data_posisi[posisi_id]' WHERE user_id='$row[user_id]'";
                    $connection->query($update);
                }else{
                  

                if(file_exists('../../../sw-content/qrcode/'.$namafile.'')){}else{
                  $quality = 'QR_ECLEVEL_Q'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
                  $ukuran = 10; //batasan 1 paling kecil, 10 paling besar
                  $padding = 1;
                  QRCode::png($codeContents,$tempdir.$namafile,$quality,$ukuran,$padding);
                }
                    // Insert  data in the database
                    $add ="INSERT INTO user(user_id,
                              email,
                              password,
                              nip,
                              nama_lengkap,
                              tempat_lahir,
                              tanggal_lahir,
                              jenis_kelamin,
                              telp,
                              alamat,
                              lokasi_id,
                              posisi_id,
                              qrcode,
                              avatar,
                              tanggal_registrasi,
                              tanggal_login,
                              time,
                              ip,
                              browser,
                              status,
                              active) values('$user_id',
                              '$email',
                              '$password',
                              '$nip',
                              '$nama_lengkap',
                              '$tempat_lahir',
                              '$tanggal_lahir',
                              '$jenis_kelamin',
                              '$telp',
                              '$alamat',
                              '$data_lokasi[lokasi_id]',
                              '$data_posisi[posisi_id]',
                              '$qrcode',
                              'avatar.jpg',
                              '$date $time',
                              '$date $time',
                              '$timein',
                              '$ip',
                              '$browser',
                              'Offline',
                              'Y')";
                    if($connection->query($add) === false) {
                        echo'Data User/Pegawai Tidak dapat di Import.!';
                        die($connection->error.__LINE__); 
                    }else{
                        //echo'success';
                    }
                }
            }
            
            // Close opened CSV file
            fclose($csvFile);
           echo'success';
        }else{
            echo'Data User/Pegawai tidak berhasil di import.!';
        }
    }else{
          echo'File tidak sesuai format, Upload file CSV.!';

    }
    

break;
case 'dropdown':
  if (empty($_POST['posisi'])) {
    $posisi = '';
  } else {
    $posisi = anti_injection($_POST['posisi']);
  }

$query_pegawai = "SELECT user_id,nama_lengkap FROM user WHERE posisi_id='$posisi'";
$result_pegawai = $connection->query($query_pegawai);
if($result_pegawai->num_rows > 0) {
  echo'<option value="">Semua</option>';
  while($data_pegawai = $result_pegawai->fetch_assoc()){
    echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
  }
}else{
  echo'<option value="">Data tidak ditemukan</option>';
}

break;
}}