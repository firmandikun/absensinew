<?php 
    require_once'../../sw-library/sw-config.php'; 
    require_once'../../sw-library/sw-function.php';
    include_once'../../sw-library/pdf/autoload.php';
    require_once '../../module/oauth/user.php';

if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND MONTH(tanggal_in) ='$month' AND YEAR(tanggal_in) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND tanggal_in BETWEEN  '$mulai' AND '$selesai'";
}

$query_overtime ="SELECT * FROM overtime WHERE user_id='$data_user[user_id]' $filter";
$result_overtime = $connection->query($query_overtime);
if($result_overtime->num_rows > 0){

  $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-P']);
  ob_start();


echo'
<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex">
    <meta name="author" content="s-widodo.com">
    <meta http-equiv="Copyright" content="s-widodo.com">
    <meta name="copyright" content="s-widodo.com">
    <link rel="icon" href="'.$site_url.'/sw-content/'.$site_favicon.'" sizes="32x32" type="image/png">
    <title>Data Overtime</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative;}
    .container_box h3{padding:10px 0;line-height:25px;font-size:20px;margin:5px 0 0px 0px;padding:0px;
      text-transform: uppercase;}
    p{padding:0px;margin:0px;line-height:25px;}
    .container_box .text-center{text-align:center}.container_box .content_box{position:relative;margin-top:30px;}
    .container_box .content_box .des_info{margin:20px 0;text-align:right}
    table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}.badge-danger,a.badge-danger{background:red!important}.badge-success,a.badge-success{background:#1dcc70!important}.badge-warning,a.badge-warning{background:#ffb400!important;color:#fff}.badge-info,a.badge-info{background:#754aed!important}.badge{font-size:12px;line-height:1em;border-radius:100px;letter-spacing:0;height:22px;min-width:22px;padding:0 6px;display:inline-flex;align-items:center;justify-content:center;font-weight:400;color:#fff}

    .tanda-tangan{
      margin-top:30px;
      float:right;
      margin-left:80%;
    }

    </style>
</head>
<body>
<section class="container_box">';
      if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
        echo'<h3 class="text-center">DATA OVERTIME</h3>
        <p class="text-center">BULAN '.strtoupper(ambilbulan($month)).' '.$year.'</p>';
      }else{
      echo'<h3 class="text-center">DATA OVERTIME</h3>
        <p class="text-center">TANGGAL '.$mulai.' s/d '.$selesai.'</p>';
      }
    echo'
      <div class="content_box">
      <p>Nama : '.$data_user['nama_lengkap'].'</p>
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center">No.</th>
              <th>Tanggal</th>
              <th>Absen Masuk</th>
              <th>Absen Pulang</th>
              <th>Keterangan</th>
              <th>Status</th>
            </tr>
          </thead>
        <tbody>';
        $no=0;
        while ($data_overtime = $result_overtime->fetch_assoc()){$no++;
          
          
          if($data_overtime['status'] ==1){
            $status='<span class="badge badge-primary badge-pill">Panding</span>';
          }elseif($data_overtime['status'] =='Y'){
            $status='<span class="badge badge-warning badge-pill">Disetujui</span>';
          }elseif($data_overtime['status'] =='N'){
            $status='<span class="badge badge-danger badge-pill">Ditotak</span>';
          }else{
            $status='';
          }

       echo'<tr>
              <td class="text-center">'.$no.'</td>
              <td>'.tanggal_ind($data_overtime['tanggal_in']).' / '.tanggal_ind($data_overtime['tanggal_out']).'</td>
              <td>'.strip_tags($data_overtime['absen_in']).'</td>
              <td>'.strip_tags($data_overtime['absen_out']).'</td>
              <td>'.strip_tags($data_overtime['keterangan']).'</td>
              <td class="text-center">'.$status.'</td>
            </tr>';
        }

      echo'<tbody>
      </table>
      
      </div>


        <div class="tanda-tangan">
          <p>'.tgl_indo($date).'<br>Disetujui Oleh<br><br><br><br>___________</p>
        </div>
    </section>
</body>
</html>';
    
    $former = error_reporting(E_ALL ^ E_NOTICE);
    $mpdf->debug = true;
    $mpdf->useSubstitutions=false;
    $mpdf->simpleTables = true;
    $html = ob_get_contents(); 
    ob_end_clean();
    $mpdf->WriteHTML(utf8_encode($html));
    $mpdf->Output("Data-Overtime-$date.pdf" ,'I');
  }else{
    echo'<title>Data Overtime</title>
    <div style="font-size:30px;text-align:center;">Data Overtime tidak ditemukan!</di>';
  }

?>