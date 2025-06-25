<?php use PHPMailer\PHPMailer\PHPMailer;
      use PHPMailer\PHPMailer\Exception;

if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/user.php';



$iB 			       = getBrowser();
$browser 		     = $iB['name'].' '.$iB['version'];
$ip 		         = $_SERVER['REMOTE_ADDR'];
$time_online     = time();

switch (@$_GET['action']){
case 'add':
$error = array();

if (empty($_POST['fullname'])) {
    $error[] = 'Nama Lengkap tidak boleh kosong';
  } else {
    $fullname = anti_injection($_POST['fullname']);
}

if (empty($_POST['username'])) {
  $error[] = 'Username tidak boleh kosong';
} else {
  $username = anti_injection($_POST['username']);
}

if (empty($_POST['phone'])) {
  $error[] = 'No. email tidak boleh kosong';
} else {
  $phone = anti_injection($_POST['phone']);
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

if (empty($_POST['level'])) {
  $error[] = 'Level tidak boleh kosong';
} else {
  $level = anti_injection($_POST['level']);
}

// Validate posisi_id if level is 3 (Atasan)
if ($level == '3') {
  if (empty($_POST['posisi_id'])) {
    $error[] = 'Posisi harus dipilih untuk level Atasan';
  } else {
    $posisi_id = anti_injection($_POST['posisi_id']);
  }
} else {
  $posisi_id = null;
}

if (empty($_POST['active'])) {
  $active ='N';
}else{
  $active ='Y';
}


if (empty($error)) {

$query="SELECT email from admin where email='$email'";
$result= $connection->query($query) or die($connection->error.__LINE__);
if(!$result ->num_rows >0){

    $add ="INSERT INTO admin(fullname,
                username,
                phone,
                email,
                password,
                avatar,
                registrasi_date,
                tanggal_login,
                time,
                status,
                level,
                posisi_id,
                ip,
                browser,
                active) values('$fullname',
                '$username',
                '$phone',
                '$email',
                '$password',
                'avatar.jpg',
                '$date $time',
                '$date $time',
                '$date $time',
                'Offline',
                '$level',
                " . ($posisi_id ? "'$posisi_id'" : "NULL") . ",
                '$ip',
                '$browser',
                '$active')";
    if($connection->query($add) === false) { 
          die($connection->error.__LINE__); 
          echo'Data tidak berhasil disimpan!';
      } else{
          echo'success';
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

    if (empty($_POST['fullname'])) {
      $error[] = 'Nama Lengkap tidak boleh kosong';
    } else {
      $fullname = anti_injection($_POST['fullname']);
  }

  if (empty($_POST['username'])) {
    $error[] = 'Username tidak boleh kosong';
  } else {
    $username = anti_injection($_POST['username']);
  }

  if (empty($_POST['phone'])) {
    $error[] = 'No. email tidak boleh kosong';
  } else {
    $phone = anti_injection($_POST['phone']);
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

    if (empty($_POST['level'])) {
      $error[] = 'Level tidak boleh kosong';
    } else {
      $level = anti_injection($_POST['level']);
    }

    // Validate posisi_id if level is 3 (Atasan)
    if ($level == '3') {
      if (empty($_POST['posisi_id'])) {
        $error[] = 'Posisi harus dipilih untuk level Atasan';
      } else {
        $posisi_id = anti_injection($_POST['posisi_id']);
      }
    } else {
      $posisi_id = null;
    }

    if (empty($_POST['active'])) {
      $active ='N';
    }else{
      $active ='Y';
    }
      
  if (empty($error)) {
    $update="UPDATE admin SET fullname='$fullname',
            username='$username',
            phone='$phone',
            email='$email',
            level='$level',
            posisi_id=" . ($posisi_id ? "'$posisi_id'" : "NULL") . ",
            active='$active' WHERE admin_id='$id'";
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


/* ----------------- Forgot/Resset Password -----------*/
break;
case 'forgot':
require_once '../../../sw-library/PHPMailer/Exception.php';
require_once '../../../sw-library/PHPMailer/PHPMailer.php';
require_once '../../../sw-library/PHPMailer/SMTP.php';

$error = array();
  if (empty($_POST['id'])) {
      $error[] = 'ID tidak ditemukan, Silahkan coba kembali';
    } else {
      $id       = anti_injection(epm_decode($_POST['id']));
  }

  $password = '123456';
  $password = password_hash($password,PASSWORD_DEFAULT);
  
  if(empty($error)){
    $query_user="SELECT fullname,email,username from admin WHERE admin_id='$id'";
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
        $mail->SMTPDebug = 2; // Aktifkan untuk melakukan debugging

        $mail->setFrom($gmail_username, $site_name);  //Email Pengirim
        $mail->addAddress($row_user['email'], $row_user['fullname']); // Email Penerima

        $mail->isHTML(true); // Aktifkan jika isi emailnya berupa html
        // Subjek email
        $mail->Subject = 'Resset password Baru | '.$site_name.'';

        $mailContent = '<h1>'.$site_name.'</h1><br>
            <h3>Halo, '.$row_user['fullname'].'</h3><br>
            <p>Selamat akun anda berahsil kami reset ulang, silahkan login dengan password baru<br>
            Username : '.$row_user['username'].'<br>
            Email : '.$row_user['email'].'<br><b>Password Baru Anda : 123456</b><br>IP : '.$ip.'<br>Browser : '.$browser.'<br><br><br><br>
            Harap simpan baik-baik akun Anda.<br><br>
            Hormat Kami,<br>'.$site_name.'<br>Email otomatis, Mohon tidak membalas email ini</p>';
        $mail->Body = $mailContent;
        $mail->AddEmbeddedImage('image/logo.png', ''.$site_name.'', '../../../sw-content/'.$site_logo.''); //Logo 
      }

        $update="UPDATE admin SET password='$password' WHERE admin_id='$id'";
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
  $update="UPDATE admin SET active='$active' WHERE admin_id='$id'";
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
        $query ="SELECT avatar FROM admin where admin_id='$id'";
        $result = $connection->query($query);
        if($result->num_rows > 0){
          $row = $result->fetch_assoc();
            /** Delete Avatar */
            $avatar_delete = strip_tags($row['avatar']);
            $tmpfile_aavatar = "../../../sw-content/avatar/".$avatar_delete;
              if(file_exists("../../../sw-content/avatar/$avatar_delete")){
                 if($avatar_delete=='avatar.jpg'){
                  /**avatar default tidak kehapus */
                 }else{
                  /** avatar udah diubah maka hapus */
                  unlink ($tmpfile_avatar); 
                 }
              }
              
          }
    /* Script Delete Data ------------*/
      $deleted  = "DELETE FROM admin WHERE admin_id='$id'";
      if($connection->query($deleted) === true) {
          echo'success';
        } else { 
          //tidak berhasil
          echo'Data tidak berhasil dihapus.!';
          die($connection->error.__LINE__);
      }

break;
}}