<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/user.php';

switch (@$_GET['action']){
case 'dropdown':
  if (empty($_POST['lokasi'])) {
    $lokasi = '';
  } else {
    $lokasi = anti_injection($_POST['lokasi']);
  }

  if(isset($_SESSION['level']) && $_SESSION['level'] == 3){
    $query_pegawai = "SELECT user_id,nama_lengkap FROM user WHERE lokasi_id='$lokasi' AND atasan_id = '".$_SESSION['admin_id']."'";
  }else{
    $query_pegawai = "SELECT user_id,nama_lengkap FROM user WHERE lokasi_id='$lokasi'";
  }
  $result_pegawai = $connection->query($query_pegawai);
  if($result_pegawai->num_rows > 0) {
    while($data_pegawai = $result_pegawai->fetch_assoc()){
      echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
    }
  }else{
    echo'<option value="">Data tidak ditemukan</option>';
  }


break;
case 'filtering':
$warna      = '';
$background = '';
$jumlah_libur = 0;
$jumlah_libur_nasional = 0;
$jumlah_izin = 0;

if(isset($_POST['bulan']) OR isset($_POST['tahun'])){
  $bulan    = anti_injection($_POST['bulan']);
  $tahun    = anti_injection($_POST['tahun']);
} else{
  $bulan    = date ("m");
  $tahun    = date("Y");
}

/** Filter Siswa */
if(isset($_POST['pegawai'])){
  $pegawai  = anti_injection($_POST['pegawai']);
} 
else{
  $pegawai = "";
}

$hari       = date("d");
$jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));

echo'
<table class="table align-items-center table-flush table-striped datatable" style="width:100%">
  <thead class="thead-light">
    <tr>
      <th class="text-center" width="10">No</th>
      <th>Tanggal</th>
      <th>Pegawai</th>
      <th>Posisi</th>
      <th>Tolerasi</th>
      <th class="text-center">Foto Masuk</th>
      <th>Absen Masuk</th>
      <th class="text-center">Foto Pulang</th>
      <th>Absen Pulang</th>
      <th>Status</th>
      <th class="text-center">Aksi</th>
    </tr>
  </thead>
<tbody>';
                
for ($d=1;$d<=$jumlahhari;$d++) {
  $tanggal  = ''.$tahun.'-'.$bulan.'-'.$d.'';
  $hari_libur = date('l',strtotime($tanggal)); // e.g. 'Wednesday'
  // Konversi nama hari Inggris ke Indonesia
  $map_hari = [
    'Sunday'    => 'Minggu',
    'Monday'    => 'Senin',
    'Tuesday'   => 'Selasa',
    'Wednesday' => 'Rabu',
    'Thursday'  => 'Kamis',
    'Friday'    => 'Jumat',
    'Saturday'  => 'Sabtu'
  ];
  $hari_libur_id = isset($map_hari[$hari_libur]) ? strtolower($map_hari[$hari_libur]) : strtolower($hari_libur);

  if(isset($_POST['bulan']) OR isset($_POST['tahun']) OR isset($_POST['pegawai'])){
    $pegawai  = anti_injection($_POST['pegawai']);
    $bulan    = anti_injection($_POST['bulan']);
    $tahun    = anti_injection($_POST['tahun']);
    $filter = "WHERE absen.tanggal='$tanggal' AND MONTH(absen.tanggal)='$bulan' AND year(absen.tanggal)='$tahun' AND absen.user_id='$pegawai'";

    // Ambil hari libur dari kolom user
    $user_libur = [];
    $q_libur = $connection->query("SELECT libur FROM user WHERE user_id='$pegawai' LIMIT 1");
    if($q_libur && $row_libur = $q_libur->fetch_assoc()) {
      // Buat array lowercase dan trim
      $user_libur = array_map('trim', explode(',', strtolower($row_libur['libur'])));
    }

    // Cek apakah hari ini termasuk hari libur user
    if(in_array($hari_libur_id, $user_libur)){
      $warna      ='#ffffff';
      $background ='#FF0000';
      $status     = 'Libur';
      $jumlah_libur++;
    }else{
      $query_libur  = "SELECT libur_tanggal,keterangan FROM libur_nasional WHERE libur_tanggal='$tanggal'";
      $result_libur = $connection->query($query_libur);
      if($result_libur->num_rows > 0){
        $data_libur = $result_libur->fetch_assoc();
        $warna='#ffffff';
        $background ='#FF0000';
        $jumlah_libur_nasional++;
        $status     = strip_tags($data_libur['keterangan']);
      }else{
        $warna      = '#111111';
        $background = 'transparent';
        $status     = '-';
      }
    }

 
  $query_absen ="SELECT absen.*,user.nama_lengkap,user.posisi_id,posisi.posisi_nama FROM absen
  INNER JOIN user ON absen.user_id = user.user_id
  INNER JOIN posisi ON user.posisi_id = posisi.posisi_id $filter";
  $result_absen = $connection->query($query_absen);
  if($result_absen->num_rows > 0){
    $data_absen = $result_absen->fetch_assoc();

      if($data_absen['kehadiran']=='Izin'){
        $warna      ='#ffffff';
        $background ='#03acca';
        $status     = 'Izin';
        $jumlah_izin++;
      }


      if($data_absen['status_masuk']=='Tepat Waktu'){
        $status_masuk ='<span class="badge badge-success">'.$data_absen['status_masuk'].'</span>';
      }else{
          $status_masuk ='<span class="badge badge-danger">'.$data_absen['status_masuk'].'</span>';
      }

      if($data_absen['absen_out']=='00:00:00'){
        $status_pulang='';
      }else{
        if($data_absen['status_pulang']=='Tepat Waktu'){
            $status_pulang ='<span class="badge badge-success">'.$data_absen['status_pulang'].'</span>';
        }else{
            $status_pulang ='<span class="badge badge-danger">'.$data_absen['status_pulang'].'</span>';
        }
      }

    if($status == 'Libur'){
      $status = 'Libur';
    }else{
      $status = '<span class="badge badge-info">'.strip_tags($data_absen['kehadiran']).'</span>';
    }

    if($data_absen['latitude_longtitude_in'] =='-' OR $data_absen['latitude_longtitude_in'] ==''){
      $map_in ='';
    }else{
      $map_in = '<a href="https://www.google.com/maps/place/'.$data_absen['latitude_longtitude_in'].'" class="btn btn-success btn-sm" target="_blank"><i class="fas fa-map-marker-alt"></i> IN</a>';
    }

    if($data_absen['latitude_longtitude_out'] =='-' OR $data_absen['latitude_longtitude_out'] ==''){
      $map_out ='';
    }else{
      $map_out = '<a href="https://www.google.com/maps/place/'.$data_absen['latitude_longtitude_out'].'" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-map-marker-alt"></i> OUT</a>';
    }

    if($data_absen['tipe']=='Foto'){
      if(file_exists('../../../sw-content/absen/'.strip_tags($data_absen['foto_in']).'')){
        $foto_masuk = '<a href="../sw-content/absen/'.strip_tags($data_absen['foto_in']).'" class="open-popup-link"><img src="../sw-content/absen/'.strip_tags($data_absen['foto_in']).'" class="imaged w100 rounded" height="50"></a>';
      }else{
        $foto_masuk = '<img src="../sw-content/avatar/avatar.jpg" class="imaged w100 rounded" height="50">';
      }

      if(file_exists('../../../sw-content/absen/'.strip_tags($data_absen['foto_out']).'')){
        if(!$data_absen['foto_out'] ==''){
          $foto_pulang = '<a href="../sw-content/absen/'.strip_tags($data_absen['foto_out']).'" class="open-popup-link"><img src="../sw-content/absen/'.strip_tags($data_absen['foto_out']).'" class="imaged w100 rounded" height="50"></a>';
        }else{
          $foto_pulang = '<img src="../sw-content/avatar/avatar.jpg" class="imaged w100 rounded" height="50">';
        }
      }else{
        $foto_pulang = '<img src="../sw-content/avatar/avatar.jpg" class="imaged w100 rounded" height="50">';
      }

    }else{
      $foto_masuk ='-';
      $foto_pulang ='-';
    }

    if($data_absen['absen_out'] =='00:00:00'){
      $absen_out = 'Belum absen';
    }else{
      $absen_out = $data_absen['absen_out'];
    }

  echo'         
    <tr style="background:'.$background.';color:'.$warna.'">
      <td>'.$d.'</td>
      <td>'.tanggal_ind($tanggal).'<br>'.$data_absen['jam_kerja_in'].' s/d '.$data_absen['jam_kerja_out'].'</td>
      <td>'.strip_tags($data_absen['nama_lengkap']).'</td>
      <td>'.strip_tags($data_absen['posisi_nama']).'</td>
      <td>'.strip_tags($data_absen['jam_kerja_toleransi']).'</td>
      <td class="text-center">'.$foto_masuk.'</td>
      <td>'.$data_absen['absen_in'].' '.$status_masuk.'</td>
      <td class="text-center">'.$foto_pulang.'</td>
      <td>'.$absen_out.' '.$status_pulang.'</td>
      <td>'.$status.'</td>
      <td class="text-center">'.$map_in.' '.$map_out.'</td>
    </tr>';
  }else{
    echo'
    <tr style="background:'.$background.';color:'.$warna.'">
      <td>'.$d.'</td>
      <td>'.tanggal_ind($tanggal).'</td>
      <td>-</td>
      <td>-</td>
      <td>-</td>
      <td class="text-center">-</td>
      <td>-</td>
      <td class="text-center">-</td>
      <td>-</td>
      <td>'.$status.'</td>
      <td class="text-center">-</td>
    </tr>';
  }
  }else{
    echo'Silahkan pilih filter';
  }
}
echo'
</tbody>
</table>';

$filter_jumlah = "MONTH(tanggal)='$bulan' AND year(tanggal)='$tahun' AND user_id='$pegawai'";
$query_hadir  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Hadir'";
$hadir        = $connection->query($query_hadir);

$query_telat  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND status_masuk='Telat'";
$terlambat   = $connection->query($query_telat);


$query_izin  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Izin'";
$izin   = $connection->query($query_izin);

$query_cuti = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Cuti'";
$cuti  = $connection->query($query_cuti);

$alpha = $jumlahhari - $hadir->num_rows - $jumlah_libur - $jumlah_libur_nasional - $jumlah_izin - $cuti->num_rows;


       
echo'
<div class="card-body">
  <div class="row">
      <div class="col-md-2">
        <p>Hadir : <span class="badge badge-success">'.$hadir->num_rows.'</span></p>
      </div>

      <div class="col-md-2">
          <p>Terlambat : <span class="badge badge-info">'.$terlambat->num_rows.'</span></p>
      </div>
        
      <div class="col-md-2">
        <p>Alpha : <span class="badge badge-danger">'.$alpha.'</span></p>
      </div>

      <div class="col-md-2">
        <p>Izin : <span class="badge badge-info">'.$izin->num_rows.'</span></p>
      </div>

      <div class="col-md-2">
        <p>Cuti : <span class="badge badge-info">'.$izin->num_rows.'</span></p>
      </div>
  </div>
</div>';?>
  <script type="text/javascript">
    $(".load-data .datatable").dataTable({
      "iDisplayLength":35,
      "aLengthMenu": [[35, 40, 50, -1], [35, 40, 50, "All"]],
      language: {
          paginate: {
            previous: "<i class='fas fa-angle-left'>",
            next: "<i class='fas fa-angle-right'>"
          }
        },
  });
  $(".open-popup-link").magnificPopup({type:"image"});
  </script>

<?php
break;
}}