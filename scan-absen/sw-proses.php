<?PHP require_once'../sw-library/sw-config.php';
require_once'../sw-library/sw-function.php';
//require_once'../sw-library/csrf.php';

$ip_login 		    = $_SERVER['REMOTE_ADDR'];
$created_login	 = date('Y-m-d H:i:s');
$iB 			       = getBrowser();
$browser 		     = $iB['name'].' '.$iB['version'];
$expired_cookie = time()+60*60*24*7;


$query_whatsapp_pesan = "SELECT * FROM whatsapp_pesan WHERE whatsapp_pesan_id='1' LIMIT 1";
$result_whatsapp_pesan = $connection->query($query_whatsapp_pesan);
$data_whatsapp_pesan = $result_whatsapp_pesan->fetch_assoc();


switch (@$_GET['action']){
case 'absen-in':
$error = array();

if (empty($_POST['qrcode'])) {
  $error[] = 'Barcode/Qr Code tidak boleh kosong';
} else {
  $qrcode = anti_injection($_POST['qrcode']);
}

if (empty($_POST['latitude'])) { 
  $error[] = 'Lokasi tidak boleh kosong';
} else {
  $latitude = htmlentities(strip_tags($_POST['latitude']));
}

if (empty($error)){

   /** Cek Qrcode User/pegawai yg absen */
   $query_user ="SELECT user.user_id,user.nama_lengkap,user.telp,lokasi.lokasi_id FROM user 
   LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE user.qrcode='$qrcode'";
   $result_user = $connection->query($query_user);
     if($result_user->num_rows > 0){
       $data_user = $result_user->fetch_assoc();
       $nomorwa        = $data_user['telp'];
       
       $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_telat,jam_kerja.jam_pulang FROM user_jam_kerja
       LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
       LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
       WHERE user_jam_kerja.user_id='$data_user[user_id]' AND jam_kerja.hari='$hari_ini' AND user_jam_kerja.active='Y' AND jam_kerja.active='Y'";
       $result_jam_kerja = $connection->query($query_jam_kerja);
       
       if($result_jam_kerja->num_rows > 0){
         $data_jam_kerja = $result_jam_kerja->fetch_assoc();
         /** Status Masuk */
         if($data_jam_kerja['jam_telat'] > $time){
           $status_in ='Tepat Waktu';
         }else{
           $status_in ='Telat';
         }

        $query_absen ="SELECT absen_id FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
        $result_absen = $connection->query($query_absen);
        if(!$result_absen->num_rows > 0) {
                /** Pesan Ke WhatsApp */
                if($whatsapp_tipe =='wablas'){
                  $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
                }
                
                if($whatsapp_tipe =='universal'){
                  $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
                  $isipesan = str_replace(' ', '%20', $isipesan);
                }
                /** Pesan Ke WhatsApp */

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
                        '$data_user[lokasi_id]',
                        '$data_jam_kerja[jam_kerja_master_id]',
                        '$data_jam_kerja[jam_masuk]',
                        '$data_jam_kerja[jam_telat]',
                        '$data_jam_kerja[jam_pulang]',
                        '$time', /** absen masuk */
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
                    echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time.'!';
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
                echo'success/Terimakasih "'.$data_user['nama_lengkap'].'", Absensi Anda berhasil pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time.'!';
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

break;
case 'absen-out':

$error = array();

if (empty($_POST['qrcode'])) {
  $error[] = 'Barcode/Qr Code tidak boleh kosong';
} else {
  $qrcode= anti_injection($_POST['qrcode']);
}

if (empty($error)){
  $query_user ="SELECT user.user_id,user.nama_lengkap,user.telp,lokasi.lokasi_id FROM user 
  LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE user.qrcode='$qrcode'";
  $result_user = $connection->query($query_user);
    if($result_user->num_rows > 0){
      $data_user = $result_user->fetch_assoc();
      $nomorwa        = $data_user['telp'];


      $query_absen ="SELECT absen_id,jam_kerja_out,absen_out FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
      $result_absen = $connection->query($query_absen);
      if($result_absen->num_rows > 0) {
        $data_absensi = $result_absen->fetch_assoc();

            if($data_absensi['jam_kerja_out'] > $time_sekarang){
              $status_out ='Pulang Cepat';
            }else{
              $status_out ='';
            }

            if($data_absensi['absen_out']=='00:00:00'){

              /** Pesan Ke WhatsApp */
              if($whatsapp_tipe =='wablas'){
                $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'<br><br>-Nama: '.$data_user['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
              }
              
              if($whatsapp_tipe =='universal'){
                $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'%0A%0A-Nama: '.$data_user['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
                $isipesan = str_replace(' ', '%20', $isipesan);
              }
              /** Pesan Ke WhatsApp */

            /*Update Data Absensi */
              $update ="UPDATE absen SET absen_out='$time',
                      status_pulang='$status_out' WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
              if($connection->query($update) === false) { 
                echo'Sepertinya Sistem Kami sedang error!';
                die($connection->error.__LINE__); 
              } else{
                  echo'success/Selamat Anda berhasil Absen Pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time.', Sampai ketemu besok "'.$data_user['nama_lengkap'].'"!';
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
              echo'success/Selamat Anda berhasil Absen Pulang pada Tanggal '.tanggal_ind($date).' dan Jam : '.$time.', Sampai ketemu besok "'.$data_user['nama_lengkap'].'"!';
            }
          }else{
            /** Jika Data absensi masuk tidak ditemukan!*/
            echo'Sebelumnya Siswa '.$data_user['nama_lengkap'].' belum pernah Absen masuk!';
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
}