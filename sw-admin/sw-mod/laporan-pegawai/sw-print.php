<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once '../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once '../../login/user.php';
include_once '../../../sw-library/pdf/autoload.php';
$no = 0;
switch (@$_GET['action']){
case 'print':
$warna      = '';
$background = '';
$jumlah_libur = 0;
$jumlah_libur_nasional = 0;
$jumlah_izin = 0;

if(!empty($_GET['bulan']) OR !empty($_GET['tahun'])){
  $bulan    = anti_injection($_GET['bulan']);
  $tahun    = anti_injection($_GET['tahun']);
} else{
  $bulan    = date ("m");
  $tahun    = date("Y");
}


/** Filter Siswa */
if(isset($_GET['pegawai'])){
  $pegawai  = anti_injection($_GET['pegawai']);
} 
else{
  $pegawai = "";
}

$hari       = date("d");
$jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));

$query_pegawai ="SELECT user_id,nama_lengkap FROM user WHERE user_id='$pegawai'";
$result_pegawai = $connection->query($query_pegawai);
if($result_pegawai->num_rows > 0){
   $data_pegawai = $result_pegawai->fetch_assoc();

  if(!empty($_GET['tipe']=='pdf')){
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
    ob_start();
  }

  if(!empty($_GET['tipe']=='excel')){
    header('Content-Type: application/vnd.ms-excel');  
    header('Content-disposition: attachment; filename=Laporan-'.$bulan.'-'.$data_pegawai['nama_lengkap'].'.xls'); 
  }

echo'
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="s-widodo.com">
    <title>Laporan Absensi Pegawai</title>
    <style>

    body{font-family:Arial,Helvetica,sans-serif}
    .text-center{
      text-align: center;
    }
    
    .kop {
      position:relative;
      display:contents;
      margin:20px 0px 20px 0px;
    }

    .kop img{
      width:100%;
      height:auto;
    }

    table.datatable{
      width:100%;
      background-color:#fff;
      border-collapse:collapse;
      border-width:1px;
      border-color:#b3b3b3;
      border-style:solid;
      color:#000;
      margin:10px 0px 0px 0px;
  }
    table.datatable td,table.datatable th{
      border-width:1px;
      border-color:#b3b3b3;
      border-style:solid;
      padding:5px;text-align:left;
      
    }
    table.datatable th{
      background-color:#666666;
      color:#ffffff;
    }
    table.datatable td.text-center,
    table.datatable th.text-center{text-align:center}

    .badge {
      font-weight: 600;
      line-height: 1;
      display: inline-block;
      padding: 0.35rem 0.375rem;
      transition: color .15s ease-in-out, background-color .15s ease-in-out, border-color .15s ease-in-out, box-shadow .15s ease-in-out;
      text-align: center;
      vertical-align: baseline;
      white-space: nowrap;
      border-radius: 0.375rem;
    }
    .badge-success {
      color: #1aae6f;
      background-color: #b0eed3;
    }
    
    .badge-danger {
      color: #f80031;
      background-color: #fdd1da;
    }
    
    .badge-info{
      color: #0080c0;
      background-color: #4aa5ff;
    }

    .rounded {
      border-radius: 0.375rem !important;
    }

    .footer-count{
      position:relative;
      display: inline-block;
    }
    .footer-count p{
      display: inline-block;
      font-size:14px;
      margin-right:10px;
    }

    </style>';
    if(!empty($_GET['tipe']=='print')){
      echo'
      <script>
        window.onafterprint = window.close;
        window.print();
      </script>';
    }
  echo'
</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-12">
        
        <div class="mt-3">
          <p>Nama : '.strip_tags($data_pegawai['nama_lengkap']).'<br>
            Laporan Absensi Bulan : '.ambilbulan($bulan).' '.$tahun.'
          </p>
        </div>
      </div>

      <div class="col-md-12">
          <table class="datatable mt-3">
              <thead>
                <tr>
                  <th class="text-center" width="10">No</th>
                  <th>Tanggal</th>
                  <th>Pegawai</th>
                  <th>Posisi</th>
                  <th>Tolerasi</th>
                  <th>Absen Masuk</th>
                  <th>Absen Pulang</th>
                  <th>Durasi Kerja</th>
                  <th>Status</th>
                  <th>Lokasi Masuk</th>
                  <th>Lokasi Pulang</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>';
                              
      for ($d=1;$d<=$jumlahhari;$d++) {
        $tanggal  = ''.$tahun.'-'.$bulan.'-'.$d.'';
        $hari_libur     = date('D',strtotime($tanggal));

      /** Menentukan Hari Libur Umum */
        $query_sabtu ="SELECT libur_hari FROM libur WHERE libur_hari='Sabtu' AND active='Y'";
        $result_sabtu= $connection->query($query_sabtu);
        if($result_sabtu->num_rows >0 ){
          $sabtu = 'Sat';
        }else{
          $sabtu ='';
        }

        $query_minggu ="SELECT libur_hari FROM libur WHERE libur_hari='Minggu' AND active='Y'";
        $result_minggu = $connection->query($query_minggu);
        if($result_minggu->num_rows >0 ){
          $minggu = 'Sun';
        }else{
          $minggu ='';
        }
    /** End Menentukan Hari Libur Umum */


        if($hari_libur == $sabtu OR $hari_libur == $minggu){
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

      if(isset($_GET['bulan']) OR isset($_GET['tahun']) OR isset($_GET['pegawai'])){
        $pegawai  = anti_injection($_GET['pegawai']);
        $bulan    = anti_injection($_GET['bulan']);
        $tahun    = anti_injection($_GET['tahun']);
        $filter = "WHERE absen.tanggal='$tanggal' AND MONTH(absen.tanggal)='$bulan' AND year(absen.tanggal)='$tahun' AND absen.user_id='$pegawai'";


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

        /** Durasi Kerja */
        if($data_absen['absen_out'] !=='00:00:00'){
          $durasi_mulai = new DateTime(''.$data_absen['tanggal'].' '.$data_absen['absen_in'].'');
          $durasi_selesai = new DateTime(''.$data_absen['tanggal'].' '.$data_absen['absen_out'].'');
          $durasi = $durasi_mulai->diff($durasi_selesai);
          $durasi_kerja  = $durasi->format('%H jam %i menit');
        }else{
          $durasi_kerja = '-';
        }

        if($data_absen['absen_out'] =='00:00:00'){
          $absen_out = '<span class="text-danger">Belum absen</span>';
        }else{
          $absen_out = $data_absen['absen_out'];
        }

        if($data_absen['latitude_longtitude_in'] =='-' OR $data_absen['latitude_longtitude_in'] ==''){
          $map_in ='';
        }else{
          $map_in = '<a href="https://www.google.com/maps/place/'.$data_absen['latitude_longtitude_in'].'" target="_blank">'.$data_absen['latitude_longtitude_in'].'</a>';
        }
        
        if($data_absen['latitude_longtitude_out'] =='-' OR $data_absen['latitude_longtitude_out'] ==''){
          $map_out ='';
        }else{
          $map_out = '<a href="https://www.google.com/maps/place/'.$data_absen['latitude_longtitude_out'].'" target="_blank">'.$data_absen['latitude_longtitude_out'].'</a>';
        }

      echo'        
        <tr style="background:'.$background.';color:'.$warna.'">
          <td>'.$d.'</td>
          <td>'.tanggal_ind($tanggal).'<br>'.$data_absen['jam_kerja_in'].' s/d '.$data_absen['jam_kerja_out'].'</td>
          <td>'.strip_tags($data_absen['nama_lengkap']).'</td>
          <td>'.strip_tags($data_absen['posisi_nama']).'</td>
          <td>'.strip_tags($data_absen['jam_kerja_toleransi']).'</td>
          <td>'.$data_absen['absen_in'].' '.$status_masuk.'</td>
          <td>'.$absen_out.' '.$status_pulang.'</td>
          <td>'.$durasi_kerja.'</td>
          <td>'.$status.'</td>
          <td>'.$map_in.'</td>
          <td>'.$map_out.'</td>
          <td>'.strip_tags($data_absen['keterangan']).'</td>
        </tr>';
        }else{
          echo'
          <tr style="background:'.$background.';color:'.$warna.'">
            <td>'.$d.'</td>
            <td>'.tanggal_ind($tanggal).'</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
            <td>'.$status.'</td>
            <td>-</td>
            <td>-</td>
            <td>-</td>
          </tr>';
        }

        }else{
          echo'Silahkan Pilih Filter';
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

    $query_telat  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND status_masuk='Telat'";
    $terlambat   = $connection->query($query_telat);

    $query_izin  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Izin'";
    $izin   = $connection->query($query_izin);

    $query_cuti = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Cuti'";
    $cuti  = $connection->query($query_cuti);

    $alpha = $jumlahhari - $hadir->num_rows - $jumlah_libur - $jumlah_libur_nasional - $jumlah_izin - $cuti->num_rows;

    echo'
      <table style="margin-top:15px">
        <tr>
          <td>Hadir : <span class="badge badge-success">'.$hadir->num_rows.'</span></td>
          <td>Terlambat : <span class="badge badge-info">'.$terlambat->num_rows.'</span></td>
          <td>Alpha : <span class="badge badge-danger">'.$alpha.'</span></td>
          <td>Izin : <span class="badge badge-info">'.$izin->num_rows.'</span></td>
          <td>Cuti : <span class="badge badge-info">'.$cuti->num_rows.'</span></td>
        </td>
      </table>
   
      </div>
    </div>
  </div>
</body>
</html>';

    if(!empty($_GET['tipe']=='pdf')){
      $former = error_reporting(E_ALL ^ E_NOTICE);
      $mpdf->debug = true;
      $mpdf->useSubstitutions=false;
      $mpdf->simpleTables = true;
      $html = ob_get_contents(); 
      ob_end_clean();
      $mpdf->WriteHTML(utf8_encode($html));
      $mpdf->Output("Laporan-$bulan-".strip_tags($data_pegawai['nama_lengkap']).".pdf" ,'I');
    }

  }else{
    echo'<div style="font-size:30px;text-align:center;margin-top:30px;">Data tidak ditemukan</div>
    <center><button onclick="window.close();" style="background:#111111;padding:8px 20px;color:#ffffff;border-radius:10px;">KEMBALI</button></center>';
  }


break;
}}