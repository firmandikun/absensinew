<?php //use PHPMailer\PHPMailer\PHPMailer;
      //use PHPMailer\PHPMailer\Exception;
require_once'../../sw-library/sw-config.php';
require_once'../../sw-library/sw-function.php';
require_once'../../sw-library/csrf.php';
require_once'../../sw-library/phpqrcode/qrlib.php'; 

///require '../../sw-library/PHPMailer/Exception.php';
//require '../../sw-library/PHPMailer/PHPMailer.php';
//require '../../sw-library/PHPMailer/SMTP.php';



$iB 			       = getBrowser();
$browser 		     = $iB['name'].' '.$iB['version'];
$ip 		         = $_SERVER['REMOTE_ADDR'];
$time_online     = time();

switch (@$_GET['action']){
case 'signup':
$error = array();
    if (empty($_POST['nama_lengkap'])) {
        $error[] = 'Nama Lengkap tidak boleh kosong';
      } else {
        $nama_lengkap = anti_injection($_POST['nama_lengkap']);
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

    if (empty($_POST['confirm_password'])) {
      $error[] = 'Konfirmasi Password tidak boleh kosong';
    } else {
      $confirm_password = htmlentities(strip_tags($_POST['confirm_password']));
    }

    if (empty($_POST['password'])) {
      $error[] = 'Password tidak boleh kosong';
    } else {
      $password = htmlentities(strip_tags($_POST['password']));
    }
    
  if (empty($error)) {

  $query="SELECT email from user where email='$email'";
  $result= $connection->query($query) or die($connection->error.__LINE__);
  if(!$result ->num_rows >0){

      if($password == $confirm_password){
        $password = password_hash($password,PASSWORD_DEFAULT);

        $query_user   ="SELECT max(user_id) AS kode_max FROM user";
        $result_user  = $connection->query($query_user);
        $row_user     = $result_user->fetch_assoc();
        $user_id      = $row_user['kode_max'];
        $user_id++;

        $random_karakter = md5($nama_lengkap);
        $shuffle  = substr(str_shuffle($random_karakter),0,9);
        $qrcode   = ''.strtoupper($shuffle).'-'.$user_id.'';

        $codeContents = $qrcode;
        $tempdir  = '../../sw-content/qrcode/';
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
                    '0000 000 000',
                    '$nama_lengkap',
                    '', /** Tempat Lahir */
                    '$date', /** Tanggal Lahir */
                    '', /** Jenis Kelamin */
                    '', /** No. Telp */
                    '', /** Alamat */
                    '0', /** Lokasi */
                    '0', /** Posisi */
                    '$qrcode', 
                    'avatar.jpg',
                    '$date $time',
                    '$date $time',
                    '$date $time',
                    '$ip',
                    '$browser',
                    'Offline',
                    'Y')";
          if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
          } else{
              echo'success';
              if(file_exists('../../sw-content/upload/qrcode/'.$namafile.'')){}else{
                $quality = 'QR_ECLEVEL_Q'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
                $ukuran = 10; //batasan 1 paling kecil, 10 paling besar
                $padding = 1;
                QRCode::png($codeContents,$tempdir.$namafile,$quality,$ukuran,$padding);
              }
          }
        }else{
          echo'Sepertinya Password yang Anda masukkan tidak sama!';
        }
      }else{
        echo'Sepertinya Email "'.$email.'" sudah terdaftar!';
      }
    }else{           
        foreach ($error as $key => $values) {            
          echo"$values\n";
        }
    }

break;
}