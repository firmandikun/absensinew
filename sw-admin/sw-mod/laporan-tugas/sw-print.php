<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:../login');
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

if(!empty($_GET['pegawai']) && !empty($_GET['bulan']) && !empty($_GET['tahun'])){
  $pegawai = strip_tags($_GET['pegawai']);
  $bulan = strip_tags($_GET['bulan']);
  $tahun = strip_tags($_GET['tahun']);
  $filter ="WHERE user_id='$pegawai' AND MONTH(tanggal) ='$bulan' AND YEAR(tanggal) ='$tahun'";
} else{
  $bulan = strip_tags($_GET['bulan']);
  $tahun = strip_tags($_GET['tahun']);
  $filter = "WHERE MONTH(tanggal) ='$bulan' AND YEAR(tanggal) ='$tahun'";
}

$query_data = "SELECT * FROM tugas $filter ORDER BY tugas_id ASC";
$result_data = $connection->query($query_data);
if($result_data->num_rows > 0){

  if(!empty($_GET['tipe']=='pdf')){
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
    ob_start();
  }

  if(!empty($_GET['tipe']=='excel')){
    header('Content-Type: application/vnd.ms-excel');  
    header('Content-disposition: attachment; filename=Laporan-tugas-'.$date.'.xls'); 
  }

echo'
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <title>Laporan Tugas</title>
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

    .badge-default {
      color: #eeeeee;
      background-color: #cccccc;
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

    .text-danger{
      color: #f80031;
    }
    .text-success{
      color: #1aae6f;
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

        <div class="mt-3">Laporan Tugas Bulan : '.ambilbulan($bulan).' '.$tahun.'</div>
      </div>

      <div class="col-md-12">
          <table class="datatable mt-3">
              <thead>
                <tr>
                  <th style="width:20px" class="text-center">No</th>
                  <th>Pegawai</th>
                  <th>Tugas</th>
                  <th>Jawaban Tugas</th>
                  <th>Tanggal</th>
                </tr>
              </thead>
              <tbody>';
              while ($data = $result_data->fetch_assoc()){$no++;
                $query_pegawai ="SELECT nama_lengkap FROM user WHERE user_id='$data[user_id]'";
                $result_pegawai = $connection->query($query_pegawai);
                $data_pegawai = $result_pegawai->fetch_assoc();

                $query_tugas = "SELECT uraian_kerja_id,tanggal,keterangan,files FROM uraian_kerja WHERE tugas_id='$data[tugas_id]' AND user_id='$data[user_id]'";
                $result_tugas = $connection->query($query_tugas);
                if($result_tugas->num_rows > 0) { 

                    $data_tugas = $result_tugas->fetch_assoc();
                    $jawaban_tugas = strip_tags($data_tugas['keterangan']);
                    $tanggal_tugas = tanggal_ind($data_tugas['tanggal']);

                    if(file_exists('../../../sw-content/tugas/'.$data_tugas['files'].'')){
                    $foto = '<a class="open-popup-link" href="data:image/gif;base64,'.base64_encode(file_get_contents('../../../sw-content/tugas/'.$data_tugas['files'].'')).'">
                        <img src="data:image/gif;base64,'.base64_encode(file_get_contents('../../../sw-content/tugas/'.$data_tugas['files'].'')).'" height="40">
                    </a>';
                    }else{
                        $foto='<img src="../sw-content/thumbnail.jpg" height="30">';
                    }

                }else{
                    $jawaban_tugas = '<span class="text-danger">X</span>';
                    $foto = '<span class="text-danger">X</span>';
                    $tanggal_tugas ='<span class="text-danger">X</span>';

                }

                echo'
                <tr>
                  <td class="text-center">'.$no.'</td>
                  <td>'.strip_tags($data_pegawai['nama_lengkap']).'</td>
                  <td>'.strip_tags($data['keterangan']).'</td>
                  <td>'.$jawaban_tugas.'</td>
                  <td>'.$tanggal_tugas.'</td>
                </tr>';
                }
              echo'
              </tbody>
          </table>';
          if(!empty($_GET['pegawai'])){
            $query_hadir  = "SELECT uraian_kerja_id FROM uraian_kerja $filter AND status='Y'";
            $hadir        = $connection->query($query_hadir);

            echo'
            <table style="margin-top:10px">
              <tr>
              <td width="200">Total Tugas : <span class="badge badge-success">'.$hadir->num_rows.'</span></td>
            </table>';
          }
         echo' 
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