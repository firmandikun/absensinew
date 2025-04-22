<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../module/oauth/user.php';


$query_whatsapp_pesan = "SELECT * FROM whatsapp_pesan WHERE whatsapp_pesan_id='1' LIMIT 1";
$result_whatsapp_pesan = $connection->query($query_whatsapp_pesan);
$data_whatsapp_pesan = $result_whatsapp_pesan->fetch_assoc();
$nomorwa = strip_tags($row_site['site_phone']);


switch (@$_GET['action']){
case 'get-user':
$query_get_user = "SELECT nama_lengkap,photo FROM user
INNER JOIN recognition ON user.user_id=recognition.user_id WHERE user.user_id='$data_user[user_id]'";
$result_get_user = $connection->query($query_get_user);
if($result_get_user->num_rows > 0){
    $row = $result_get_user->fetch_assoc();
    echo seo_title(strtolower($row["nama_lengkap"]));
}else{
  echo'default';
}


/** Absen masuk dengan qr code dengan radius*/
break;
case 'absen-qrcode-radius':
    $error = array();
      if (empty($_POST['qrcode'])) { 
          $error[] = 'Qrcode tidak boleh kosong';
        } else { 
          $qrcode  = htmlentities($_POST['qrcode']);
      }

      if (empty($_POST['latitude'])) { 
          $error[] = 'Lokasi tidak boleh kosong';
        } else {
          $latitude = htmlentities(strip_tags($_POST['latitude']));
      }

      if (empty($_POST['radius'])) { 
        $error[] = 'Radius tidak boleh kosong';
      } else {
        $radius = htmlentities(strip_tags($_POST['radius']));
      }

    if (empty($error)){

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      /** Cek Qrcode User/pegawai yg absen */
      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]' AND user.qrcode='$qrcode'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
          
          $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_telat,jam_kerja.jam_pulang FROM user_jam_kerja
          LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
          LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
          WHERE user_jam_kerja.user_id='$data_user[user_id]' AND hari='$hari_ini' AND user_jam_kerja.active='Y' AND jam_kerja.active='Y'";
          $result_jam_kerja = $connection->query($query_jam_kerja);
          
          if($result_jam_kerja->num_rows > 0){
            $data_jam_kerja = $result_jam_kerja->fetch_assoc();

            /** Status Masuk */
            if ($time_sekarang <= $data_jam_kerja['jam_telat']) {
                $status_in = 'Tepat Waktu';
            } else {
                $status_in = 'Telat';
            }

          if($data_lokasi['lokasi_radius'] > $radius){
              /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
              $query_absensi ="SELECT absen_id,absen_in FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
              $result_absensi = $connection->query($query_absensi);
              if(!$result_absensi->num_rows > 0){

                /** Jika belum ada makan tambah absen baru */
                $add ="INSERT INTO absen (user_id,
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
                        keterangan) values('$data_user[user_id]',
                        '$date',
                        '$data_lokasi[lokasi_id]',
                        '$data_jam_kerja[jam_kerja_master_id]',
                        '$data_jam_kerja[jam_masuk]',
                        '$data_jam_kerja[jam_telat]',
                        '$data_jam_kerja[jam_pulang]',
                        '$time_sekarang', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '', /** Foto masuk kosong */
                        '', /** Foto pulang kosong */
                        '$status_in',
                        '', /** Status pulang kosong */
                        'Hadir', /** 1. Hadir */
                        '$latitude',
                        '', /** Latitude out */
                        'Y',
                        'qrcode',
                        '-')"; /** Keterangan Kosong */
                  if($connection->query($add) === false) { 
                    echo'Sepertinya Sistem Kami sedang error!';
                    die($connection->error.__LINE__); 
                } else{
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                    /** Notifikasi WhastApp */
                    if($whatsapp_tipe =='wablas'){
                      KirimWa($nomorwa,$isipesan,$link,$token);
                    }
                    if($whatsapp_tipe =='universal'){
                      KirimWa($sender,$nomorwa,$isipesan,$link,$token);
                    }
                    /** Notifikasi WhastApp */
                }
              }else{
                /** Berikan notifikasi Absen masuk jika sudah terinput */
                $data_absen = $result_absensi->fetch_assoc();
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absen['absen_in'].'!';
              }
              }else{
                /** Notifikasi radius */
                echo'Posisi Anda sat ini jauh dari radius!';
              }
              }else{
                echo'Jam Kerja tidak ditemukan, silahkan periksa jam kerja Anda!';
              }
            }else{
              echo'Qr code/User tidak ditemukan, silahkan hubungi Admin!';
            }
        }else{       
          foreach ($error as $key => $values) {            
              echo"$values\n";
          }
      }
 


/** Absen Qrcode tanpa radius  */
break;
case 'absen-qrcode':
    $error = array();
      if (empty($_POST['qrcode'])) { 
          $error[] = 'Qrcode tidak boleh kosong';
        } else { 
          $qrcode  = htmlentities($_POST['qrcode']);
      }

      if (empty($_POST['latitude'])) { 
          $error[] = 'Lokasi tidak boleh kosong';
        } else {
          $latitude = htmlentities(strip_tags($_POST['latitude']));
      }

    if (empty($error)){

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      /** Cek Qrcode User/pegwai yg absen */
      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]' AND user.qrcode='$qrcode'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
          
          $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_telat,jam_kerja.jam_pulang FROM user_jam_kerja
          LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
          LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
          WHERE user_jam_kerja.user_id='$data_user[user_id]' AND hari='$hari_ini' AND user_jam_kerja.active='Y' AND jam_kerja.active='Y'";
          $result_jam_kerja = $connection->query($query_jam_kerja);
          
          if($result_jam_kerja->num_rows > 0){
            $data_jam_kerja = $result_jam_kerja->fetch_assoc();

            /** Status Masuk */
            if ($time_sekarang <= $data_jam_kerja['jam_telat']) {
                $status_in = 'Tepat Waktu';
            } else {
                $status_in = 'Telat';
            }

              /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
              $query_absensi ="SELECT absen_id,absen_in FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
              $result_absensi = $connection->query($query_absensi);
              if(!$result_absensi->num_rows > 0){

                /** Jika belum ada makan tambah absen baru */
                $add ="INSERT INTO absen (user_id,
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
                        keterangan) values('$data_user[user_id]',
                        '$date',
                        '$data_lokasi[lokasi_id]',
                        '$data_jam_kerja[jam_kerja_master_id]',
                        '$data_jam_kerja[jam_masuk]',
                        '$data_jam_kerja[jam_telat]',
                        '$data_jam_kerja[jam_pulang]',
                        '$time_sekarang', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '', /** Foto masuk kosong */
                        '', /** Foto pulang kosong */
                        '$status_in',
                        '', /** Status pulang kosong */
                        'Hadir', /** 1. Hadir */
                        '$latitude',
                        '', /** Latitude out */
                        'N',
                        'qrcode',
                        '-')"; /** Keterangan Kosong */
                  if($connection->query($add) === false) { 
                    echo'Sepertinya Sistem Kami sedang error!';
                    die($connection->error.__LINE__); 
                } else{
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                    /** Notifikasi WhastApp */
                    if($whatsapp_tipe =='wablas'){
                      KirimWa($nomorwa,$isipesan,$link,$token);
                    }
                    if($whatsapp_tipe =='universal'){
                      KirimWa($sender,$nomorwa,$isipesan,$link,$token);
                    }
                    /** Notifikasi WhastApp */
                }
              }else{
                /** Berikan notifikasi Absen masuk jika sudah terinput */
                $data_absen = $result_absensi->fetch_assoc();
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absen['absen_in'].'!';
              }
              
              }else{
                echo'Jam Kerja tidak ditemukan, silahkan periksa jam kerja Anda!';
              }
            }else{
              echo'Qr code/User tidak ditemukan, silahkan hubungi Admin!';
            }
        }else{       
          foreach ($error as $key => $values) {            
              echo"$values\n";
          }
      }


/* -------------------------------- ABSEN MENGGUNAKAN FOTO -------------------------------- */
/** Absen Selfie dengan radius */
break;
case 'absen-selfie-radius':
  $error = array();
      if (empty($_POST['latitude'])) { 
          $error[] = 'Lokasi tidak boleh kosong';
        } else {
          $latitude = htmlentities(strip_tags($_POST['latitude']));
      }

      if (empty($_POST['radius'])) { 
        if($_POST['radius'] =='0'){
          $radius = 0;
        }else{
          $error[] = 'Radius tidak boleh kosong';
        }
      } else {
        $radius = htmlentities(strip_tags($_POST['radius']));
      }

      if (empty($_POST['img'])){
        $error[]    = 'Foto tidak dapat di unggah!';
      } else {
        $img = $_POST['img'];
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $fetch_imgParts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $fetch_imgParts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($fetch_imgParts[1]);
    
        $im = imagecreatefromstring($image_base64);
        $source_width = imagesx($im);
        $source_height = imagesy($im);
        $ratio =  $source_height / $source_width;
    
        $new_width = 250; // assign new width to new resized image
        $new_height = $ratio * 290;
        $watermark = "".strip_tags($data_user['nama_lengkap'])."\n".$time_sekarang." - ".tanggal_ind($date)."";
      }

    /* -------- Upload Foto  -------*/
    $foto   = 'absen-masuk-'.$data_user['user_id'].'-'.time().'.jpg';
    $filename = '../../sw-content/absen/'.$foto.''; // output file name

    if (empty($error)){

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      /** Cek Lokasi */
      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
          
          $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_telat,jam_kerja.jam_pulang FROM user_jam_kerja
          LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
          LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
          WHERE user_jam_kerja.user_id='$data_user[user_id]' AND jam_kerja.hari='$hari_ini' AND user_jam_kerja.active='Y' AND jam_kerja.active='Y'";
          $result_jam_kerja = $connection->query($query_jam_kerja);
          
          if($result_jam_kerja->num_rows > 0){
            $data_jam_kerja = $result_jam_kerja->fetch_assoc();

            /** Status Masuk */
            if ($time_sekarang <= $data_jam_kerja['jam_telat']) {
                $status_in = 'Tepat Waktu';
            } else {
                $status_in = 'Telat';
            }

          if($data_lokasi['lokasi_radius'] > $radius){
              /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
              $query_absensi ="SELECT absen_id,absen_in FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
              $result_absensi = $connection->query($query_absensi);
              if(!$result_absensi->num_rows > 0){

  

                /** Jika belum ada makan tambah absen baru */
                $add ="INSERT INTO absen (user_id,
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
                        keterangan) values('$data_user[user_id]',
                        '$date',
                        '$data_lokasi[lokasi_id]',
                        '$data_jam_kerja[jam_kerja_master_id]',
                        '$data_jam_kerja[jam_masuk]',
                        '$data_jam_kerja[jam_telat]',
                        '$data_jam_kerja[jam_pulang]',
                        '$time_sekarang', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '$foto', /** Foto masuk */
                        '', /** Foto pulang kosong */
                        '$status_in',
                        '', /** Status pulang kosong */
                        'Hadir', /** 1. Hadir */
                        '$latitude',
                        '', /** Latitude out */
                        'Y',
                        'Foto',
                        '-')"; /** Keterangan Kosong */
                  if($connection->query($add) === false) { 
                    echo'Sepertinya Sistem Kami sedang error!';
                    die($connection->error.__LINE__); 
                } else{
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                    addTextWatermark($im, $watermark, $filename);	
                    /** Notifikasi WhastApp */
                    if($whatsapp_tipe =='wablas'){
                      KirimWa($nomorwa,$isipesan,$link,$token);
                    }
                    if($whatsapp_tipe =='universal'){
                      KirimWa($sender,$nomorwa,$isipesan,$link,$token);
                    }
                    /** Notifikasi WhastApp */
                }
              }else{
                /** Berikan notifikasi Absen masuk jika sudah terinput */
                $data_absen = $result_absensi->fetch_assoc();
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absen['absen_in'].'!';
              }
              }else{
                /** Notifikasi radius */
                echo'Posisi Anda sat ini jauh dari radius!';
              }
            }else{
                echo'Jam Kerja tidak ditemukan, silahkan periksa jam kerja Anda!';
            }
            }else{
              echo'Lokasi Anda tidak ditemukan, silahkan hubungi Admin!';
            }
        }else{       
          foreach ($error as $key => $values) {            
              echo"$values\n";
          }
      }


/** Absen Selfie Tanpa radius */
break;
case 'absen-selfie':
$error = array();
      if (empty($_POST['latitude'])) { 
          $error[] = 'Lokasi tidak boleh kosong';
        } else {
          $latitude = htmlentities(strip_tags($_POST['latitude']));
      }
      
      if (empty($_POST['img'])){
        $error[]    = 'Foto tidak dapat di unggah!';
      } else {
        $img = $_POST['img'];
        $img = str_replace('data:image/jpeg;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $fetch_imgParts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $fetch_imgParts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($fetch_imgParts[1]);
    
        $im = imagecreatefromstring($image_base64);
        $source_width = imagesx($im);
        $source_height = imagesy($im);
        $ratio =  $source_height / $source_width;
    
        $new_width = 250; // assign new width to new resized image
        $new_height = $ratio * 290;
        $watermark = "".strip_tags($data_user['nama_lengkap'])."\n".$time_sekarang." - ".tanggal_ind($date)."";
        //$watermark = ''.$time_sekarang.' - '.tanggal_ind($date).'';
      }

        /* -------- Upload Foto  -------*/
        $foto   = 'absen-masuk-'.$data_user['user_id'].'-'.time().'.jpg';
        $filename = '../../sw-content/absen/'.$foto.''; // output file name

    if (empty($error)){

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

        /** Cek Lokasi */
        $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
        LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]'";
        $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();

          $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_telat,jam_kerja.jam_pulang FROM user_jam_kerja
          LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
          LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
          WHERE user_jam_kerja.user_id='$data_user[user_id]' AND jam_kerja.hari='$hari_ini' AND user_jam_kerja.active='Y' AND jam_kerja.active='Y'";
          $result_jam_kerja = $connection->query($query_jam_kerja);
          
          if($result_jam_kerja->num_rows > 0){
            $data_jam_kerja = $result_jam_kerja->fetch_assoc();


           /** Status Masuk */
            if ($time_sekarang <= $data_jam_kerja['jam_telat']) {
                $status_in = 'Tepat Waktu';
            } else {
                $status_in = 'Telat';
            }

              /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
              $query_absensi ="SELECT absen_id,absen_in FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
              $result_absensi = $connection->query($query_absensi);
              if(!$result_absensi->num_rows > 0){

                /** Jika belum ada makan tambah absen baru */
                $add ="INSERT INTO absen (user_id,
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
                        keterangan) values('$data_user[user_id]',
                        '$date',
                        '$data_lokasi[lokasi_id]',
                        '$data_jam_kerja[jam_kerja_master_id]',
                        '$data_jam_kerja[jam_masuk]',
                        '$data_jam_kerja[jam_telat]',
                        '$data_jam_kerja[jam_pulang]',
                        '$time_sekarang', /** absen masuk */
                        '00:00:00', /** Absen pulang kosong */
                        '$foto', /** Foto masuk */
                        '', /** Foto pulang kosong */
                        '$status_in',
                        '', /** Status pulang kosong */
                        'Hadir', /** 1. Hadir */
                        '$latitude',
                        '', /** Latitude out */
                        'N',
                        'Foto',
                        '-')"; /** Keterangan Kosong */
                  if($connection->query($add) === false) { 
                    echo'Sepertinya Sistem Kami sedang error!';
                    die($connection->error.__LINE__); 
                } else{
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                    addTextWatermark($im, $watermark, $filename);	
                    /** Notifikasi WhastApp */
                    if($whatsapp_tipe =='wablas'){
                      KirimWa($nomorwa,$isipesan,$link,$token);
                    }
                    if($whatsapp_tipe =='universal'){
                      KirimWa($sender,$nomorwa,$isipesan,$link,$token);
                    }
                    /** Notifikasi WhastApp */
                }
              }else{
                /** Berikan notifikasi Absen masuk jika sudah terinput */
                $data_absen = $result_absensi->fetch_assoc();
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absen['absen_in'].'!';
              }
            }else{
                echo'Jam Kerja tidak ditemukan, silahkan periksa jam kerja Anda!';
            }
          }else{
            echo'Lokasi Anda tidak ditemukan, silahkan hubungi Admin!';
          }
        }else{       
          foreach ($error as $key => $values) {            
              echo"$values\n";
          }
        }

    break;
  }
}