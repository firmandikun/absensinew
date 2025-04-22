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

if(!empty($_GET['lokasi']) AND !empty($_GET['posisi']) AND !empty($_GET['tanggal'])){
  $lokasi   = anti_injection($_GET['lokasi']);
  $posisi   = anti_injection($_GET['posisi']);
  $tanggal  = date('Y-m-d', strtotime($_GET['tanggal']));
  $filter   = "user.lokasi_id='$lokasi' AND user.posisi_id='$posisi' AND absen.tanggal='$tanggal'";

}elseif(!empty($_GET['lokasi']) AND !empty($_GET['tanggal'])){
  $lokasi   = anti_injection($_GET['lokasi']);
  $tanggal  = date('Y-m-d', strtotime($_GET['tanggal']));
  $filter   = "user.lokasi_id='$lokasi' AND absen.tanggal='$tanggal'";

}elseif(!empty($_GET['posisi']) AND !empty($_GET['tanggal'])){
  $posisi   = anti_injection($_GET['posisi']);
  $tanggal  = date('Y-m-d', strtotime($_GET['tanggal']));
  $filter   = "user.posisi_id='$posisi' AND absen.tanggal='$tanggal'";

}elseif(!empty($_GET['tanggal'])) {
  $tanggal = date('Y-m-d', strtotime($_GET['tanggal']));
  $filter = "absen.tanggal='$tanggal'";

}else{
  $tanggal = $date;
  $filter = "absen.tanggal='$date'";
}

$query_absen ="SELECT tanggal,absen_in,absen_out,status_masuk,status_pulang,latitude_longtitude_in,latitude_longtitude_out,nip,nama_lengkap,posisi_id FROM absen
INNER JOIN user ON absen.user_id = user.user_id WHERE $filter ORDER BY absen.tanggal ASC";
$result_absen = $connection->query($query_absen);
if($result_absen->num_rows > 0){


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
    <meta name="description" content="s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <title>Laporan Absensi hari ini</title>
    <style>

    body{font-family:Arial,Helvetica,sans-serif}
    .text-center{
      text-align: center;
    }
    
    .kop {
      position:relative;
      display:contents;
      margin:0px 0px 20px 0px;
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

    .rounded {
      border-radius: 0.375rem !important;
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
        <div class="kop text-center">
          <img src="../../../sw-content/kop.jpg" class="imaged w100 rounded">
        </div>

        <div class="mt-3">Laporan Absensi Tanggal : '.format_hari_tanggal($tanggal).'</div>
      </div>

      <div class="col-md-12">
          <table class="datatable mt-3">
              <thead>
                <tr>
                  <th style="width:20px" class="text-center">No</th>
                  <th>NIP</th>
                  <th>Nama</th>
                  <th>Posisi</th>
                  <th class="text-center">Absen Masuk</th>
                  <th class="text-center">Absen Pulang</th>
                  <th>Lokasi Masuk</th>
                  <th>Lokasi Pulang</th>
                </tr>
              </thead>
              <tbody>';
                while ($data_absen = $result_absen->fetch_assoc()){$no++;
                  $query_posisi ="SELECT * FROM posisi WHERE posisi_id='$data_absen[posisi_id]'";
                  $result_posisi = $connection->query($query_posisi);
                  $data_posisi = $result_posisi->fetch_assoc();

                  if($data_absen['status_masuk']=='Tepat Waktu'){
                    $status_masuk ='<span class="badge badge-success">'.$data_absen['status_masuk'].'</span>';
                  }else{
                      $status_masuk ='<span class="badge badge-danger">'.$data_absen['status_masuk'].'</span>';
                  }
      
                  if($data_absen['status_pulang']=='Tepat Waktu'){
                      $status_pulang ='<span class="badge badge-success">'.$data_absen['status_pulang'].'</span>';
                  }else{
                      $status_pulang ='<span class="badge badge-danger">'.$data_absen['status_pulang'].'</span>';
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
                <tr>
                  <td class="text-center">'.$no.'</td>
                  <td>'.strip_tags($data_absen['nip']).'</td>
                  <td>'.strip_tags($data_absen['nama_lengkap']).'</td>
                  <td>'.strip_tags($data_posisi['posisi_nama']).'</td>
                  <td class="text-center">'.$data_absen['absen_in'].''.$status_masuk.'</td>
                  <td class="text-center">'.$data_absen['absen_out'].''.$status_pulang.'</td>
                  <td>'.$map_in.'</td>
                  <td>'.$map_out.'</td>
                </tr>';
                }
              echo'
              </tbody>
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
    $mpdf->Output("Laporan-hari-ini-$date.pdf" ,'I');
  }

}else{
  echo'<div style="font-size:30px;text-align:center;margin-top:30px;">Data tidak ditemukan</div>
  <center><button onclick="window.close();" style="background:#111111;padding:8px 20px;color:#ffffff;border-radius:10px;">KEMBALI</button></center>';
}


break;
}}