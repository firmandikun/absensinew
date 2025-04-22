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

if(!empty($_GET['lokasi']) && !empty($_GET['pegawai']) && !empty($_GET['bulan']) && !empty($_GET['tahun'])){
    $lokasi = strip_tags($_GET['lokasi']);
    $pegawai = strip_tags($_GET['pegawai']);
    $bulan = strip_tags($_GET['bulan']);
    $tahun = strip_tags($_GET['tahun']);
    $filter ="WHERE lokasi_id='$lokasi' AND user_id='$pegawai' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
    
}elseif(!empty($_GET['lokasi']) && !empty($_GET['bulan']) && !empty($_GET['tahun'])){
    $lokasi = strip_tags($_GET['lokasi']);
    $bulan = strip_tags($_GET['bulan']);
    $tahun = strip_tags($_GET['tahun']);
    $filter ="WHERE lokasi_id='$lokasi' AND MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
} else{
    $bulan = strip_tags($_GET['bulan']);
    $tahun = strip_tags($_GET['tahun']);
    $filter = "WHERE MONTH(date) ='$bulan' AND YEAR(date) ='$tahun'";
}


$query_data = "SELECT * FROM uraian_kerja $filter ORDER BY uraian_kerja_id ASC";
$result_data = $connection->query($query_data);
if($result_data->num_rows > 0){

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
    <title>Laporan Uraian Kerja</title>
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

        <div class="mt-3">Uraian Kerja Bulan : '.ambilbulan($bulan).' '.$tahun.'</div>
      </div>

      <div class="col-md-12">
          <table class="datatable mt-3">
              <thead>
                <tr>
                  <th style="width:20px" class="text-center">No</th>
                  <th>Nama</th>
                  <th>Posisi</th>
                  <th>Tanggal</th>
                  <th>Judul</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody>';
              while ($data_kerja = $result_data->fetch_assoc()){$no++;
                $query_pegawai ="SELECT nip,nama_lengkap,posisi_nama FROM user 
                INNER JOIN posisi ON user.posisi_id = posisi.posisi_id WHERE user.user_id='$data_kerja[user_id]'";
                $result_pegawai = $connection->query($query_pegawai);
                $data_pegawai = $result_pegawai->fetch_assoc();
                echo'
                <tr>
                  <td class="text-center">'.$no.'</td>
                  <td>'.strip_tags($data_pegawai['nama_lengkap']).'</td>
                  <td>'.strip_tags($data_pegawai['posisi_nama']).'</td>
                  <td>'.tanggal_ind($data_kerja['tanggal']).'</td>
                  <td>'.strip_tags($data_kerja['nama']).'</td>
                  <td>'.strip_tags($data_kerja['keterangan']).'</td>
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
              <td width="200">Total Uraian Kerja : <span class="badge badge-success">'.$hadir->num_rows.'</span></td>
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