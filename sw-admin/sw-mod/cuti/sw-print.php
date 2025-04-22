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
if(!empty($_GET['id'])){
  $id = anti_injection(epm_decode($_GET['id']));
}else{
  $id ='';
}

$query_cuti ="SELECT cuti.*,user.nip,posisi.posisi_nama FROM cuti 
INNER JOIN user ON user.user_id=cuti.user_id
INNER JOIN posisi ON posisi.posisi_id=user.posisi_id WHERE cuti.cuti_id='$id'";
$result_cuti = $connection->query($query_cuti);
if($result_cuti->num_rows > 0){
  $data_cuti = $result_cuti->fetch_assoc();
  $tanggal_kerja = date('Y-m-d', strtotime($data_cuti['tanggal_selesai']. ' + 1 days'));

  $query_menyetujui ="SELECT fullname FROM admin WHERE admin_id='$data_cuti[atasan]'";
  $result_menyetujui = $connection->query($query_menyetujui);
  $data_menyetujui = $result_menyetujui->fetch_assoc();

$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
ob_start();

echo'
<!DOCTYPE html>
<html>
  <head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Print Cuti '.strip_tags($data_cuti['nama_lengkap']).'</title>
  
  <meta name="description" content="s-widodo.com">
  <meta name="author" content="s-widodo.com">
  <meta name="robots" content="noindex">
  <meta name="googlebot" content="noindex">
    
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
      border-style:solid;
      color:#000;
      margin:10px 0px 0px 0px;
  }
    table.datatable td,table.datatable th{
      border-color:#b3b3b3;
      border-style:solid;
      padding:10px 0px 10px 0px;
      text-align:left;
      
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
      </div>

      <div class="col-md-12">
        <p>Yth. HRD '.$site_company.'<br>
          di tempat<p>

          <p style="margin:20px 0px 10px 0px;">Dengan hormal,<br>
          yang bertanda tangan di bawah ini:<p>

          <table class="table datatable">
            <tbody>
              <tr>
                <td width="200">Nama</td>
                <td>: '.strip_tags($data_cuti['nama_lengkap']).'</td>
              </tr>

              <tr>
                <td width="200">NIP</td>
                <td>: '.strip_tags($data_cuti['nip']).'</td>
              </tr>

              <tr>
                <td width="200">Jabatan</td>
                <td>: '.strip_tags($data_cuti['posisi_nama']).'</td>
              </tr>

            </tbody>
          </table>

          <p style="margin-top:20px;">Bermaksud mengajukan cuti tahunan selama '.$data_cuti['jumlah'].' hari, yaitu pada <b>'.tgl_ind($data_cuti['tanggal_mulai']).' sampai '.tgl_ind($data_cuti['tanggal_selesai']).'</b>, saya akan mulai bekerja kembali pada <b>'.tgl_indo($tanggal_kerja).'</b></p>

          <p style="margin-top:20px;">Demikian permohonan cuti ini saya ajukan. Terimakasih atas perhatian Bapak/Ibu.</p>
          
          <p>Cuti yang telah diambil dalam tahun yang bersangkutan:</p>
          <table class="table datatable">
          <tbody>
            <tr>
              <td width="200">Cuti '.strip_tags($data_cuti['jenis']).'</td>
              <td>: '.$data_cuti['jumlah'].' Hari</td>
            </tr>

            <tr>
              <td width="200">Keterangan</td>
              <td>: '.strip_tags($data_cuti['keterangan']).'</td>
            </tr>
          </tbody>
        </table>


          <p style="margin-top:50px">Tanggal '.tgl_indo($date).'</p>
          <center>
            <table class="table customTable" style="margin-top:10px;">
              <tbody>
                <tr>
                  <td class="text-center">Pemohon<br><br><br><br><br><b>'.$data_cuti['nama_lengkap'].'</b></td>
                  <td width="400"></td>
                  <td class="text-center">Menyetujui<br><br><br><br><br><b>'.strip_tags($data_menyetujui['fullname']).'</b></td>
                </tr>
              </tbody>
            </table>
          </center>

    
      </div>
    </div>
  </div>
</body>
</html>';

    $former = error_reporting(E_ALL ^ E_NOTICE);
    $mpdf->debug = true;
    $mpdf->useSubstitutions=false;
    $mpdf->simpleTables = true;
    $html = ob_get_contents(); 
    ob_end_clean();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output("Cuti-$date.pdf" ,'I');

}else{
  echo'<div style="font-size:30px;text-align:center;margin-top:30px;">Data tidak ditemukan</div>
  <center><button onclick="window.close();" style="background:#111111;padding:8px 20px;color:#ffffff;border-radius:10px;">KEMBALI</button></center>';
}


break;
}}