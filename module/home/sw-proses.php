<?php
require_once'../../sw-library/sw-config.php';
require_once'../../sw-library/sw-function.php';

$expired_cookie = time() + 60 * 60 * 24 * 30;

switch (@$_GET['action']){
case 'login':
$error = array();
  if (empty($_POST['username'])) { 
      $error[] = 'Username / Email tidak boleh kosong';
    } else { 
      $username = anti_injection($_POST['username']);
  }

  if (empty($_POST['password'])) { 
      $error[] = 'Password tidak boleh kosong';
    } else {
      $password_hash = htmlentities(strip_tags($_POST['password']));
  }

if (empty($error)){
  $time_online = time();
  if(filter_var($username, FILTER_VALIDATE_EMAIL)){
    $query_login ="SELECT user_id,email,nama_lengkap,password,tanggal_login,active FROM user WHERE email='$username'";
    $result_login       = $connection->query($query_login);
  if($result_login->num_rows > 0){
  	$row_user    = $result_login->fetch_assoc();
    
    if($row_user['active'] == 'Y'){
      $USER_KEY   = convert("encrypt", strip_tags($row_user['user_id']));
      $TOKEN_KEY  = convert("encrypt", strip_tags($row_user['email']));
      /* ---------- Update Status Online --------- */
      $update_user = "UPDATE user SET tanggal_login='$date $time', time='$time_online', status='Online' WHERE user_id='$row_user[user_id]'";
      $connection->query($update_user);
    /* ---------- Update Status Online --------- */
		  //verify password 
        if(password_verify($password_hash,$row_user['password'])) {
            setcookie('USER_KEY', $USER_KEY, $expired_cookie, '/');
            setcookie('TOKEN_KEY', $USER_KEY, $expired_cookie, '/');
            echo'success';
        }else{
            echo "Username/Email dan password yang Anda masukkan salah!";
        }
    } else{
      echo'Saat ini akun Anda belum aktif, silahkan hubungi Admin!';
    }
  }
  else {
    echo'Akun Anda Tidak ditemukan!';
    }
  }
}
  else{       
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


/** Baca notifikasi */
break; 
case'notifikasi':
  $error = array();
  if (empty($_POST['id'])) {
    $error[] = 'Nama tidak boleh kosong';
  } else {
    $id = anti_injection($_POST['id']);
  }
  if (empty($error)) {
    $update="UPDATE notifikasi SET status='Y' WHERE notifikasi_id='$id' AND user_id='$data_user[user_id]'"; 
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
break;

break;
}