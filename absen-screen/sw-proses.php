<?PHP require_once'../sw-library/sw-config.php';
require_once'../sw-library/sw-function.php';

$query_whatsapp_pesan = "SELECT * FROM whatsapp_pesan WHERE whatsapp_pesan_id='1' LIMIT 1";
$result_whatsapp_pesan = $connection->query($query_whatsapp_pesan);
$data_whatsapp_pesan = $result_whatsapp_pesan->fetch_assoc();

switch (@$_GET['action']){
case 'absen':

if($time_sekarang > $data_setting_absen['mulai_absen_masuk'] && $time_sekarang < $data_setting_absen['mulai_absen_pulang']){
/** Absen Masuk */
$error = array();

  if (empty($_POST['qrcode'])) {
    $error[] = 'QRCODE tidak boleh kosong';
  } else {
    $qrcode = anti_injection($_POST['qrcode']);
  }


if (empty($error)){

  $query_pegawai ="SELECT user_id,nama_lengkap,telp,lokasi_id,avatar FROM user WHERE qrcode='$qrcode'";
  $result_pegawai = $connection->query($query_pegawai);
  if ($result_pegawai->num_rows > 0){
      $data_pegawai = $result_pegawai->fetch_assoc();
      $nomorwa        = htmlentities($data_pegawai['telp']);


      if($data_pegawai['avatar'] == NULL OR $data_pegawai['avatar']=='avatar.jpg'){
          $avatar = '../sw-content/avatar/avatar.jpg';
      }else{
          if(file_exists('../sw-content/avatar/'.$data_pegawai['avatar'].'')){
              $avatar = '../sw-content/avatar/'.$data_pegawai['avatar'].'';
          }else{
              $avatar = '../sw-content/avatar/avatar.jpg';
          }
      }
          
            
      /** Cek Jam Kerja Setiap Pegawai */
      $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_telat,jam_kerja.jam_pulang FROM user_jam_kerja
      LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
      LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
      WHERE user_jam_kerja.user_id='$data_pegawai[user_id]' AND jam_kerja.hari='$hari_ini' AND user_jam_kerja.active='Y' AND jam_kerja.active='Y'";
      $result_jam_kerja = $connection->query($query_jam_kerja);
      
      if($result_jam_kerja->num_rows > 0){
        $data_jam_kerja = $result_jam_kerja->fetch_assoc();
        /** Status Masuk */
        if($data_jam_kerja['jam_telat'] > $time){
          $status_masuk ='Tepat Waktu';
        }else{
          $status_masuk ='Telat';
        }

        $query_absen ="SELECT absen_id FROM absen WHERE tanggal='$date' AND user_id='$data_pegawai[user_id]'";
        $result_absen = $connection->query($query_absen);
          if(!$result_absen->num_rows > 0) {
              /** Pesan Ke WhatsApp */
              if($whatsapp_tipe =='wablas'){
                $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'<br><br>-Nama: '.$data_pegawai['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
              }

              if($whatsapp_tipe =='universal'){
                $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_masuk']).'%0A%0A-Nama: '.$data_pegawai['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
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
                    keterangan) values('$data_pegawai[user_id]',
                    '$date',
                    '$data_pegawai[lokasi_id]',
                    '$data_jam_kerja[jam_kerja_master_id]',
                    '$data_jam_kerja[jam_masuk]',
                    '$data_jam_kerja[jam_telat]',
                    '$data_jam_kerja[jam_pulang]',
                    '$time', /** absen masuk */
                    '00:00:00', /** Absen pulang kosong */
                    '', /** Foto masuk kosong */
                    '', /** Foto pulang kosong */
                    '$status_masuk',
                    '', /** Status pulang kosong */
                    'Hadir', /** 1. Hadir */
                    '', /** Latitude in */
                    '', /** Latitude out */
                    'Y',
                    'qrcode',
                    '-')"; /** Keterangan Kosong */
                  if($connection->query($add) === false) { 
                    echo'Sepertinya Sistem Kami sedang error!';
                    die($connection->error.__LINE__); 
                } else{
                    /** Tampilkan Jika sukses */
                    $data['nama_pegawai']   = strip_tags($data_pegawai['nama_lengkap']);
                    $data['status']         = strip_tags($status_masuk);
                    $data['timestamp']      = $time;
                    $data['avatar']         = $avatar;
                    echo json_encode($data);
                    
                    if($whatsapp_tipe =='wablas'){
                      KirimWa($nomorwa,$isipesan,$link,$token);
                    }
                    if($whatsapp_tipe =='universal'){
                        KirimWa($sender,$nomorwa,$isipesan,$link,$token);
                    }
                }
              }else{
                /** Berikan notifikasi Absen masuk jika sudah terinput */
                $data['nama_pegawai']   = strip_tags($data_pegawai['nama_lengkap']);
                $data['status']         = strip_tags($status_masuk);
                $data['timestamp']      = $time;
                $data['avatar']         = $avatar;
                echo json_encode($data);
              }

            }else{
              //echo'Hari ini tidak Ada jadwal/jam sekolah!';
              $data['nama_pegawai']   = 'Nama Pegawai';
              $data['status']         = 'Tidak ada jadwal';
              $data['timestamp']      = '-';
              $data['avatar']         = '../template/img/image.png';
              echo json_encode($data);
            }

        }else{
              $data['nama_pegawai']   = 'Nama Pegawai';
              $data['status']         = 'User tidak ditemukan';
              $data['timestamp']    = '-';
              $data['avatar']       = '../template/img/image.png';
              echo json_encode($data);
        }
    }else{       
      $data['nama_pegawai']   = 'Nama Pegawai';
      $data['status']       = 'QRCODE tidak boleh kosong';
      $data['timestamp']    = '-';
      $data['avatar']       = '../template/img/image.png';
      echo json_encode($data);
  }

/*** ____________________________________________________________________  */
}else if($time_sekarang >= $data_setting_absen['mulai_absen_pulang']){
  /** Absen Pulang */
  
$error = array();

if (empty($_POST['qrcode'])) {
  $error[] = 'Barcode/Qr Code tidak boleh kosong';
} else {
  $qrcode= anti_injection($_POST['qrcode']);
}


if (empty($error)){

  $query_pegawai ="SELECT user_id,nama_lengkap,telp,lokasi_id,avatar FROM user WHERE qrcode='$qrcode'";
  $result_pegawai = $connection->query($query_pegawai);
  if ($result_pegawai->num_rows > 0){
      $data_pegawai = $result_pegawai->fetch_assoc();
      $nomorwa        = htmlentities($data_pegawai['telp']);

    if($data_pegawai['avatar'] == NULL OR $data_pegawai['avatar']=='avatar.jpg'){
        $avatar = '../sw-content/avatar/avatar.jpg';
    }else{
        if(file_exists('../sw-content/avatar/'.$data_pegawai['avatar'].'')){
            $avatar = '../sw-content/avatar/'.$data_pegawai['avatar'].'';
        }else{
            $avatar = '../sw-content/avatar/avatar.jpg';
        }
    }
      
          /** Pesan Ke WhatsApp */
           if($whatsapp_tipe =='wablas'){
            $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'<br>'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'<br><br>-Nama: '.$data_pegawai['nama_lengkap'].'<br>-Tanggal: '.tgl_ind($date).'<br>-Jam: '.$time_sekarang.'<br><br>'.strip_tags($data_whatsapp_pesan['penutupan']).'';
          }

          if($whatsapp_tipe =='universal'){
            $isipesan  = ''.strip_tags($data_whatsapp_pesan['pembukaan']).'%0A'.strip_tags($data_whatsapp_pesan['pesan_pulang']).'%0A%0A-Nama: '.$data_pegawai['nama_lengkap'].'%0A-Tanggal: '.tgl_ind($date).'%0A-Jam: '.$time_sekarang.'%0A%0A'.strip_tags($data_whatsapp_pesan['penutupan']).'';
            $isipesan = str_replace(' ', '%20', $isipesan);
          }
          /** Pesan Ke WhatsApp */

          $query_absen ="SELECT absen_id,absen_out,jam_kerja_out FROM absen WHERE tanggal='$date' AND user_id='$data_pegawai[user_id]'";
          $result_absen = $connection->query($query_absen);
          if($result_absen->num_rows > 0) {
            $data_absensi = $result_absen->fetch_assoc();

            if($data_absensi['jam_kerja_out'] < $time){
              $status_pulang ='';
            }else{
              $status_pulang='Pulang Cepat';
            }

            if($data_absensi['absen_out']=='00:00:00'){

            /*Update Data Absensi */
            $update ="UPDATE absen SET absen_out='$time',
                      status_pulang='$status_pulang' WHERE tanggal='$date' AND user_id='$data_pegawai[user_id]'";
              if($connection->query($update) === false) { 
                echo'Sepertinya Sistem Kami sedang error!';
                die($connection->error.__LINE__); 
              } else{
                  $data['nama_pegawai']   = strip_tags($data_pegawai['nama_lengkap']);
                  $data['status']       = strip_tags($status_pulang);
                  $data['timestamp']    = $time;
                  $data['avatar']       = $avatar;
                  echo json_encode($data);
                  
                  if($whatsapp_tipe =='wablas'){
                    KirimWa($nomorwa,$isipesan,$link,$token);
                  }
                  if($whatsapp_tipe =='universal'){
                      KirimWa($sender,$nomorwa,$isipesan,$link,$token);
                  }
              }
            }else{
                /*** Berikan notifikasi jika dobel input */
                $data['nama_pegawai']   = strip_tags($data_pegawai['nama_lengkap']);
                $data['status']       = strip_tags($status_pulang);
                $data['timestamp']    = $time;
                $data['avatar']       = $avatar;
                echo json_encode($data);
            }
          }else{
            /** Jika Data absensi masuk tidak ditemukan!*/
            $data['nama_pegawai']   = 'Nama Pegawai';
            $data['status']       = 'Absensi Masuk tidak ditemukan';
            $data['timestamp']    = '-';
            $data['avatar']       = '../template/img/image.png';
            echo json_encode($data);
          }

      }else{
        $data['nama_pegawai']   = 'Nama Pegawai';
        $data['status']       = 'User tidak ditemukan';
        $data['timestamp']    = '-';
        $data['avatar']       = '../template/img/image.png';
        echo json_encode($data);
      }

    }else{
      $data['nama_pegawai']   = 'Nama Pegawai';
      $data['status']       = 'QRCODE tidak boleh kosong';
      $data['timestamp']    = '-';
      $data['avatar']       = '../template/img/image.png';
      echo json_encode($data);
    }
}else{
  /*** Jika diluar jam Kerja*/
  $data['nama_pegawai']   = '-';
  $data['status']       = 'Absen sesuai waktu yang ditentukan';
  $data['timestamp']    = '-';
  $data['avatar']       = '../template/img/image.png';
  echo json_encode($data);
}


/** Data Absensi */
break;
case 'data-absensi':

  $query_absen ="SELECT absen.tanggal,absen.absen_in,absen.absen_out,absen.status_masuk,absen.status_pulang,user.nama_lengkap,user.avatar FROM absen INNER JOIN user ON absen.user_id=user.user_id WHERE absen.tanggal='$date' ORDER BY absen_in DESC, absen_out DESC LIMIT 7";
  $result_absen = $connection->query($query_absen);
  echo'
  <table class="table">
      <thead>
          <tr class="text-white">
              <th class="text-white">Nama</th>
              <th class="text-white">Masuk</th>
              <th class="text-white">Pulang</th>
              <th class="text-white">Status</th>
          </tr>
      </thead>
      <tbody>';
      if($result_absen->num_rows > 0){
        while($data_absen = $result_absen->fetch_assoc()){
  
        if($data_absen['status_masuk']=='Telat'){
          $status='Telat';
        }
        elseif ($data_absen['status']='Tepat Waktu') {
          $status='Tepat';
        }
        else{
          $status='-';
        }
  
        if($data_absen['status_pulang']=='Pulang Cepat'){
          $status_pulang='Pulang Cepat';
        }
        else{
          $status_pulang='Tepat';
        }

        if($data_absen['absen_out']=='00:00:00'){
          $absen_pulang = '-';
        }else{
          $absen_pulang = $data_absen['absen_out'];
        }
  
        echo'
        <tr class="text-white">
            <td>'.strip_tags($data_absen['nama_lengkap']).'</td>
            <td class="text-white">'.$data_absen['absen_in'].'</td>
            <td class="text-white">'.$absen_pulang.'</td>
            <td>'.$status.'</td>
        </tr>';
      }}
      echo'
      </tbody>
  </table>';


/* Data Counter */
break;
case'data-counter':
$query_pegawai = "SELECT user_id FROM user WHERE active='Y'";
$result_pegawai = $connection->query($query_pegawai);
$jumlah_pegawai = $result_pegawai->num_rows;

$query_posisi = "SELECT posisi_id FROM posisi";
$result_posisi = $connection->query($query_posisi);

$query_absen ="SELECT absen_id FROM absen WHERE tanggal='$date' AND kehadiran='Hadir'";
$result_absen = $connection->query($query_absen);

$query_absen_izin ="SELECT absen_id FROM absen WHERE tanggal='$date' AND kehadiran='Izin'";
$result_absen_izin = $connection->query($query_absen_izin);

$query_absen_cuti ="SELECT absen_id FROM absen WHERE tanggal='$date' AND kehadiran='Cuti'";
$result_absen_cuti = $connection->query($query_absen_cuti);

$belum_absen  = $result_pegawai->num_rows - $result_absen->num_rows - $result_absen_izin->num_rows - $result_absen_cuti->num_rows;
$persentase   = round($result_absen->num_rows/$result_pegawai->num_rows * 100,0);

$query_absen_ontime ="SELECT absen_id FROM absen WHERE tanggal='$date' AND kehadiran='Hadir' AND status_masuk='Tepat Waktu'";
$result_absen_ontime = $connection->query($query_absen_ontime);

$query_absen_telat ="SELECT absen_id FROM absen WHERE tanggal='$date' AND kehadiran='Hadir' AND status_masuk='Telat'";
$result_absen_telat = $connection->query($query_absen_telat);



/** Left  */
$data_counter['on_time']        = $result_absen_ontime->num_rows;
$data_counter['terlambat']      = $result_absen_telat->num_rows;
$data_counter['izin']           = $result_absen_izin->num_rows;
$data_counter['cuti']           = $result_absen_cuti->num_rows;

/** Right */
$data_counter['total_pegawai']  = $jumlah_pegawai;
$data_counter['total_posisi']   = $result_posisi->num_rows;
$data_counter['belum_absen']    = $belum_absen;
$data_counter['total_absen']    = $result_absen->num_rows;
$data_counter['persentase']     = $persentase;
echo json_encode($data_counter);
break;
}