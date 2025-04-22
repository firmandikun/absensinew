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
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      /** Cek Qrcode User/pegawai yg absen */
      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE user.qrcode='$qrcode' AND lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
      
          if($data_lokasi['lokasi_radius'] > $radius){
            
            /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
            $query_absensi ="SELECT absen_id,jam_kerja_in,jam_kerja_out,absen_out FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
            $result_absensi = $connection->query($query_absensi);
            if($result_absensi->num_rows > 0){
              $data_absensi     = $result_absensi->fetch_assoc();
              $time_in     = strtotime(''.$data_absensi['jam_kerja_in'].' + 60 minute');
              $time_in     = date('H:i:s', $time_in);

              /** Status Pulang */
                if($data_absensi['jam_kerja_out'] > $time_sekarang){
                  $status_out ='Pulang Cepat';
                }else{
                  $status_out ='';
                }

                
                  if($data_absensi['absen_out']=='00:00:00'){

                      /** Jika sudah ada absensi masuk makan update absen pulang */
                        $update ="UPDATE absen SET absen_out='$time_sekarang',
                                  latitude_longtitude_out='$latitude',
                                  status_pulang='$status_out'
                                  WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
                        if($connection->query($update) === false) { 
                          die($connection->error.__LINE__); 
                          echo'Sepertinya Sistem Kami sedang error, Silahkan hubungi Admin!';
                      } else{
                          echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
                          
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
                        /** Berikan notifikasi Absen pulang jika sudah terinput */
                        echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absensi['absen_out'].'!';
                  }
            

            }else{
              /** Notifikasi belum ada data absen masuk */
              echo'Hallo, "'.$data_user['nama_lengkap'].'", Silahkan absen masuk terlebih dahulu!';
            }
          }else{
            /** Notifikasi radius */
            echo'Posisi Anda sat ini jauh dari radius!';
          }
          }else{
            echo'Qr code/User tidak ditemukan, silahkan hubungi Admin!';
          }

        }else{       
            foreach ($error as $key => $values) {            
                echo"$values\n";
              }
        }
 
break; 
/** Absen masuk dengan qr code tanpa radius*/
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
      /** Cek Qrcode User/pegawai yg absen */

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE user.qrcode='$qrcode' AND lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
      
            /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
            $query_absensi ="SELECT absen_id,jam_kerja_in,jam_kerja_out,absen_out FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
            $result_absensi = $connection->query($query_absensi);
            if($result_absensi->num_rows > 0){
              $data_absensi     = $result_absensi->fetch_assoc();
              $time_in     = strtotime(''.$data_absensi['jam_kerja_in'].' + 60 minute');
              $time_in     = date('H:i:s', $time_in);

              /** Status Pulang */
                if($data_absensi['jam_kerja_out'] > $time_sekarang){
                  $status_out ='Pulang Cepat';
                }else{
                  $status_out ='';
                }

               
                  if($data_absensi['absen_out']=='00:00:00'){

                      /** Jika sudah ada absensi masuk makan update absen pulang */
                        $update ="UPDATE absen SET absen_out='$time_sekarang',
                                  latitude_longtitude_out='$latitude',
                                  status_pulang='$status_out'
                                  WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
                        if($connection->query($update) === false) { 
                          die($connection->error.__LINE__); 
                          echo'Sepertinya Sistem Kami sedang error, Silahkan hubungi Admin!';
                      } else{
                          echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
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
                        /** Berikan notifikasi Absen pulang jika sudah terinput */
                        echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absensi['absen_out'].'!';
                  }
             

            }else{
              /** Notifikasi belum ada data absen masuk */
              echo'Hallo, "'.$data_user['nama_lengkap'].'", Silahkan absen masuk terlebih dahulu!';
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
        $foto   = 'absen-pulang-'.$data_user['user_id'].'-'.time().'.jpg';
        $filename = '../../sw-content/absen/'.$foto.''; // output file name

    if (empty($error)){

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      /** Cek Lokasi */
      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
          
        if($data_lokasi['lokasi_radius'] > $radius){
        
          /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
          $query_absensi ="SELECT absen_id,jam_kerja_in,jam_kerja_out,absen_out FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
          $result_absensi = $connection->query($query_absensi);
          if($result_absensi->num_rows > 0){
            $data_absensi     = $result_absensi->fetch_assoc();
            $time_in     = strtotime(''.$data_absensi['jam_kerja_in'].' + 60 minute');
            $time_in     = date('H:i:s', $time_in);
      
            /** Status Pulang */
            if($data_absensi['jam_kerja_out'] > $time_sekarang){
              $status_out ='Pulang Cepat';
            }else{
              $status_out ='';
            }
        
           
                if($data_absensi['absen_out']=='00:00:00'){

                /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
                      $update ="UPDATE absen SET absen_out='$time_sekarang',
                        foto_out='$foto',
                        latitude_longtitude_out='$latitude',
                        status_pulang='$status_out'
                        WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
                  if($connection->query($update) === false) { 
                    echo'Sepertinya Sistem Kami sedang error, Silahkan hubungi Admin!';
                    die($connection->error.__LINE__); 
                } else{
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
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
                /** Berikan notifikasi Absen pulang jika sudah terinput */
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absensi['absen_out'].'!';
              }
            
      
          }else{
            /** Notifikasi belum ada data absen masuk */
            echo'Hallo, "'.$data_user['nama_lengkap'].'", Silahkan absen masuk terlebih dahulu!';
          }
        }else{
          /** Notifikasi radius */
          echo'Posisi Anda sat ini jauh dari radius!';
        }
      }else{
        echo'Lokasi Anda saat ini tidak ditemukan, silahkan hubungi Admin!';
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
      }

        /* -------- Upload Foto  -------*/
        $foto   = 'absen-pulang-'.$data_user['user_id'].'-'.time().'.jpg';
        $filename = '../../sw-content/absen/'.$foto.''; // output file name

    if (empty($error)){

      if($whatsapp_tipe =='wablas'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
      }
      
      if($whatsapp_tipe =='universal'){
        $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
        $isipesan = str_replace(' ', '%20', $isipesan);
      }

      /** Cek Lokasi */
      $query_lokasi ="SELECT user.user_id,lokasi.lokasi_id,lokasi.lokasi_radius FROM user 
      LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE lokasi.lokasi_status='Y' AND user.user_id='$data_user[user_id]'";
      $result_lokasi = $connection->query($query_lokasi);
        if($result_lokasi->num_rows > 0){
          $data_lokasi     = $result_lokasi->fetch_assoc();
          
          /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
          $query_absensi ="SELECT absen_id,jam_kerja_in,jam_kerja_out,absen_out FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]' LIMIT 1";
          $result_absensi = $connection->query($query_absensi);
          if($result_absensi->num_rows > 0){
            $data_absensi     = $result_absensi->fetch_assoc();
            $time_in     = strtotime(''.$data_absensi['jam_kerja_in'].' + 60 minute');
            $time_in     = date('H:i:s', $time_in);
      
            /** Status Pulang */
            if($data_absensi['jam_kerja_out'] > $time_sekarang){
              $status_out ='Pulang Cepat';
            }else{
              $status_out ='';
            }
        
           
            if($data_absensi['absen_out']=='00:00:00'){
                /** Cek Absensi Hari ini berdasarkan tanggal sekarang */
                      $update ="UPDATE absen SET absen_out='$time_sekarang',
                        foto_out='$foto',
                        latitude_longtitude_out='$latitude',
                        status_pulang='$status_out'
                        WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
                  if($connection->query($update) === false) { 
                    echo'Sepertinya Sistem Kami sedang error, Silahkan hubungi Admin!';
                    die($connection->error.__LINE__); 
                } else{
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time_sekarang.'!';
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
                /** Berikan notifikasi Absen pulang jika sudah terinput */
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Anda berhasil absen pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$data_absensi['absen_out'].'!';
              }
           
      
          }else{
            /** Notifikasi belum ada data absen masuk */
            echo'Hallo, "'.$data_user['nama_lengkap'].'", Silahkan absen masuk terlebih dahulu!';
          }
      }else{
        echo'Lokasi Anda saat ini tidak ditemukan, silahkan hubungi Admin!';
      }    
      }else{       
          foreach ($error as $key => $values) {            
              echo"$values\n";
            }
      }


break;
  }
}