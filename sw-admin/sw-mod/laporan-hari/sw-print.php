<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once '../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
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

if(isset($_GET['bulan']) OR isset($_GET['tahun'])){
  $bulan    = anti_injection($_GET['bulan']);
  $tahun    = anti_injection($_GET['tahun']);
} else{
  $bulan    = date ("m");
  $tahun    = date("Y");
}


if(!empty($_GET['lokasi']) AND !empty($_GET['posisi']) AND !empty($_GET['pegawai'])){
  $pegawai    = anti_injection($_GET['pegawai']);
  $lokasi     = anti_injection($_GET['lokasi']);
  $posisi     = anti_injection($_GET['posisi']);
  $filter_pegawai     = "WHERE user.lokasi_id='$lokasi' AND user.posisi_id='$posisi' AND user.user_id='$pegawai'";

}elseif(!empty($_GET['lokasi']) AND !empty($_GET['posisi'])){
  $lokasi        = anti_injection($_GET['lokasi']);
  $posisi     = anti_injection($_GET['posisi']);
  $filter_pegawai       = "WHERE user.lokasi_id='$lokasi' AND user.posisi_id='$posisi'";


}elseif(!empty($_GET['lokasi']) AND !empty($_GET['pegawai'])){
  $pegawai    = anti_injection($_GET['pegawai']);
  $lokasi     = anti_injection($_GET['lokasi']);
  $filter_pegawai      = "WHERE user.lokasi_id='$lokasi' AND user.user_id='$pegawai'";

}elseif(!empty($_GET['lokasi'])){
  $lokasi        = anti_injection($_GET['lokasi']);
  $filter_pegawai       = "WHERE user.lokasi_id='$lokasi'";
}else{
  $filter_pegawai     = "";
}

$query_pegawai ="SELECT user.user_id,user.nama_lengkap,posisi.posisi_nama FROM user
INNER JOIN posisi  ON user.posisi_id = posisi.posisi_id $filter_pegawai ORDER BY user.user_id ASC";
$result_pegawai = $connection->query($query_pegawai);
if($result_pegawai->num_rows > 0){


$hari       = date("d");
$jumlahhari = date("t",mktime(0,0,0,$bulan,$hari,$tahun));

if(!empty($_GET['tipe']=='pdf')){
  $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
  ob_start();
}

if(!empty($_GET['tipe']=='excel')){
  header('Content-Type: application/vnd.ms-excel');  
  header('Content-disposition: attachment; filename=Laporan-hari-ini-'.$date.'.xls'); 
}

echo'
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="robots" content="noindex">
    <meta name="googlebot" content="noindex">
    <meta name="description" content="s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <title>Laporan Absensi/Bulan</title>
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
      font-size: 66%;
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

    .badge-warning{
      color: #ff3709;
      background-color: #fee6e0;
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
          <p>Laporan Absensi Bulan : '.ambilbulan($bulan).' '.$tahun.'</p>
        </div>
      </div>

      <div class="col-md-12">
      <table class="datatable mt-3">
      <thead>
        <tr>
          <th rowspan="2" width="40" class="text-center" style="vertical-align: middle;">No</th>
          <th rowspan="2" style="vertical-align: middle;">Nama</th>
          <th rowspan="2" style="vertical-align: middle;">Jabatan</th>
          <th rowspan="2" style="vertical-align: middle;">Status</th>
          <th class="text-center" colspan="'.$jumlahhari.'">'.ambilbulan($bulan).'</th>
          <th class="text-center" colspan="5">Keterangan</th>
        </tr>
        <tr>';
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
                    $warna      = '#FFFFFF';
                    $background = '#666666';
                    $status     = '-';
                  }
                }
              echo'
                <th width="50" class="text-center" style="background:'.$background.';color:'.$warna.'">'.date('D', strtotime($tanggal)).'<br>'.$d.'</th>';
            }
              echo'
                <th width="50" class="text-center">H</th>
                <th width="50" class="text-center">T</th>
                <th width="50" class="text-center">A</th>
                <th width="50" class="text-center">I</th>
                <th width="50" class="text-center">C</th>
              </tr>
          </thead>
        <tbody>';
                          
          while ($data_pegawai = $result_pegawai->fetch_assoc()){$no++;

            echo'
            <tr>
              <td rowspan="2" class="text-center">'.$no.'</td>
              <td rowspan="2" width="150">'.strip_tags($data_pegawai['nama_lengkap']).'</td>
              <td rowspan="2" width="150">'.strip_tags($data_pegawai['posisi_nama']).'</td>
              <td width="60">Masuk</td>';
              for ($d=1;$d<=$jumlahhari;$d++){
                $tanggal  = ''.$tahun.'-'.$bulan.'-'.$d.'';
                $filter = "WHERE tanggal='$tanggal' AND MONTH(tanggal)='$bulan' AND year(tanggal)='$tahun' AND user_id='$data_pegawai[user_id]'";
      
                $query_absen ="SELECT tanggal,absen_in,absen_out,status_masuk,status_pulang,kehadiran FROM absen $filter";
                $result_absen = $connection->query($query_absen);
                  if($result_absen->num_rows > 0){
                    $data_absen = $result_absen->fetch_assoc();
      
                    if($data_absen['status_masuk']=='Tepat Waktu'){
                      $status_masuk ='<span class="badge badge-success">'.$data_absen['status_masuk'].'</span>';
                    }elseif($data_absen['status_masuk']=='Telat'){
                        $status_masuk ='<span class="badge badge-danger">'.$data_absen['status_masuk'].'</span>';
                    }else{
                      if($data_absen['kehadiran'] =='Izin' OR $data_absen['kehadiran'] =='Cuti'){
                        $status_masuk ='<span class="badge badge-warning">'.$data_absen['kehadiran'].'</span>';
                      } else{
                        $status_masuk ='';
                      }
                    }
          
                  echo'
                    <td class="text-center">'.$data_absen['absen_in'].'<br>'.$status_masuk.'</td>';
                  }else{
                    echo'
                    <td class="text-center">X</td>';
                  }
                }

                $filter_jumlah = "MONTH(tanggal)='$bulan' AND year(tanggal)='$tahun' AND user_id='$data_pegawai[user_id]'";

                $query_hadir  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Hadir'";
                $hadir        = $connection->query($query_hadir);

                $query_telat  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND status_masuk='Telat'";
                $terlambat   = $connection->query($query_telat);

                $query_izin  = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Izin'";
                $izin   = $connection->query($query_izin);

                $query_cuti = "SELECT absen_id FROM absen WHERE $filter_jumlah AND kehadiran='Cuti'";
                $cuti  = $connection->query($query_cuti);

                $alpha = $jumlahhari  - $hadir->num_rows - $izin->num_rows - $cuti->num_rows - $jumlah_libur - $jumlah_libur_nasional;

                echo'
                <td width="50" rowspan="2" class="text-center">'.$hadir->num_rows.'</td>
                <td width="50" rowspan="2" class="text-center">'.$terlambat->num_rows.'</td>
                <td width="50" rowspan="2" class="text-center">'.$alpha.'</td>
                <td width="50" rowspan="2" class="text-center">'.$izin->num_rows.'</td>
                <td width="50" rowspan="2" class="text-center">'.$cuti->num_rows.'</td>
            </tr>
              
            <tr>
              <td width="60">Pulang</td>';
              for ($d=1;$d<=$jumlahhari;$d++){
                $tanggal  = ''.$tahun.'-'.$bulan.'-'.$d.'';
                $filter = "WHERE tanggal='$tanggal' AND MONTH(tanggal)='$bulan' AND year(tanggal)='$tahun' AND user_id='$data_pegawai[user_id]'";
      
                $query_absen ="SELECT tanggal,absen_out,status_pulang,kehadiran FROM absen $filter";
                $result_absen = $connection->query($query_absen);
                if($result_absen->num_rows > 0){
                    $data_absen = $result_absen->fetch_assoc();
                    if($data_absen['absen_out']=='00:00:00'){
                      if($data_absen['kehadiran'] =='Izin' OR $data_absen['kehadiran'] =='Cuti'){
                        $status_pulang ='<span class="badge badge-warning">'.$data_absen['kehadiran'].'</span>';
                      } else{
                        $status_pulang ='';
                      }
                    }else{
                      if($data_absen['status_pulang']=='Tepat Waktu'){
                          $status_pulang ='<span class="badge badge-success">'.$data_absen['status_pulang'].'</span>';
                      }else{
                          $status_pulang ='<span class="badge badge-danger">'.$data_absen['status_pulang'].'</span>';
                      }
                    }
              echo'
                <td class="text-center">'.$data_absen['absen_out'].'<br>'.$status_pulang.'</td>';
              }else{
              echo'
                <td class="text-center">X</td>';
              }
            }
            echo'
            </tr>';
          }
        echo'
          </tbody>
        </table>
        
        <div style="margin-top:10px;">
          <span class="badge badge-info">H: Hadir</span>
          <span class="badge badge-warning">T: Terlambat</span>
          <span class="badge badge-danger">A: Alpha</span>
          <span class="badge badge-success">I: Izin</span>
          <span class="badge badge-warning">C: Cuti</span>
        </div>
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
        $mpdf->Output("Laporan-Absensi-bulan-$bulan-$date.pdf" ,'I');
      }

  }else{
    echo'<div style="font-size:30px;text-align:center;margin-top:30px;">Data tidak ditemukan</div>
    <center><button onclick="window.close();" style="background:#111111;padding:8px 20px;color:#ffffff;border-radius:10px;">KEMBALI</button></center>';     
  }

break;
}}