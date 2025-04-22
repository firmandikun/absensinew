<?php 
    require_once'../../sw-library/sw-config.php'; 
    require_once'../../sw-library/sw-function.php';
    include_once'../../sw-library/pdf/autoload.php';
    require_once'../../module/oauth/user.php';

if(!empty($_GET['from']) OR !empty($_GET['to'])){
  $from = date('Y-m-d', strtotime($_GET['from']));
  $to   = date('Y-m-d', strtotime($_GET['to']));
  $filter ="tanggal BETWEEN '$from' AND '$to'";
} 
else{
  $filter = "MONTH(tanggal) ='$month' AND YEAR(tanggal) ='$year'";
}


$query_absen ="SELECT tanggal,foto_in,foto_out,absen_in,absen_out,status_masuk,status_pulang,kehadiran,tipe,radius FROM absen WHERE user_id='$data_user[user_id]' AND $filter ORDER BY absen_id DESC";
  $result_absen = $connection->query($query_absen);
  if($result_absen->num_rows > 0){
    
echo'
<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex">
    <meta name="author" content="s-widodo.com">
    <meta http-equiv="Copyright" content="s-widodo.com">
    <meta name="copyright" content="s-widodo.com">
    <link rel="icon" href="'.$site_url.'/sw-content/'.$site_favicon.'" sizes="32x32" type="image/png">
    <title>Daftar Kehadiran</title>
    <style>
    body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative;}
    .container_box h3{padding:10px 0;line-height:25px;font-size:20px;margin:5px 0 0px 0px;padding:0px;
      text-transform: uppercase;}
    p{padding:0px;margin:0px;line-height:23px;}
    .container_box .text-center{text-align:center}.container_box .content_box{position:relative;margin-top:30px;}
    .container_box .content_box .des_info{margin:20px 0;text-align:right}
    table.customTable{width:100%;background-color:#fff;border-collapse:collapse;border-width:1px;border-color:#b3b3b3;border-style:solid;color:#000}table.customTable td,table.customTable th{border-width:1px;border-color:#b3b3b3;border-style:solid;padding:5px;text-align:left}table.customTable thead{background-color:#f6f3f8}.text-center{text-align:center}.badge-danger,a.badge-danger{background:red!important}.badge-success,a.badge-success{background:#1dcc70!important}.badge-warning,a.badge-warning{background:#ffb400!important;color:#fff}.badge-info,a.badge-info{background:#754aed!important}.badge{font-size:14px;line-height:1em;border-radius:100px;letter-spacing:0;height:22px;min-width:22px;padding:0 6px;display:inline-flex;align-items:center;justify-content:center;font-weight:400;color:#fff}
    .text-center{text-align:center}
    .tanda-tangan{
      margin-top:30px;
      float:right;
      margin-left:80%;
    }
    </style>
</head>
  <script>
    window.onafterprint = window.close;
    window.print();
  </script>
<body>
<section class="container">';
      if(!empty($_GET['from']) OR !empty($_GET['to'])){
         echo'<h3 class="text-center">DAFTAR KEHADIRAN</h3>
         <p class="text-center">Per Tanggal: '.tanggal_ind($from).' S/D '.tanggal_ind($to).'</p>';
      }else{
        echo'<h3 class="text-center">DAFTAR KEHADIRAN</h3>
        <p class="text-center">Bulan: '.ambilbulan($month).' '.$year.'</p>';
      }
    echo'
      <div class="content">
        <p>Nama : '.$data_user['nama_lengkap'].'</p>
        <table class="customTable">
          <thead>
            <tr>
              <th class="text-center" width="50">No.</th>
              <th>Tanggal</th>
              <th>Waktu Masuk</th>
              <th>Waktu Pulang</th>
              <th class="text-center">Tipe Absen</th>
            </tr>
          </thead>
        <tbody>';
        $no=0;
        while ($data_absen = $result_absen->fetch_assoc()){$no++;
          if($data_absen['status_masuk']=='Telat'){
            $status=' <span class="badge badge-danger" style="font-size:12px">Telat</span>';
          }
          elseif ($data_absen['status_masuk']='Tepat Waktu') {
            $status='<span class="badge badge-success" style="font-size:12px">Tepat Waktu</span>';
          }
          else{
            $status='';
          }
    
          if($data_absen['absen_out']=='00:00:00'){
            $status_pulang='<small class="badge badge-warning" style="font-size:12px">Belum Absen</small>';
          }
          elseif($data_absen['status_pulang']=='Pulang Cepat'){
            $status_pulang='<small class="badge badge-danger" style="font-size:12px">Pulang Cepat</small>';
          }
          else{
            $status_pulang='';
          }


       echo'<tr>
              <td class="text-center">'.$no.'</td>
              <td>'.tanggal_ind($data_absen['tanggal']).'</td>
              <td>'.$data_absen['absen_in'].' '.$status.'</td>
              <td>'.$data_absen['absen_out'].' '.$status_pulang.'</td>
              <td class="text-center">'.strip_tags($data_absen['tipe']).'</td>
            </tr>';
        }

      echo'<tbody>
      </table>';
          $query_in = "SELECT absen_id FROM absen WHERE kehadiran='1' AND user_id='$data_user[user_id]' AND $filter";
          $absen_in = $connection->query($query_in);

          $query_out = "SELECT absen_id FROM absen WHERE absen_out>0 AND kehadiran='1' AND user_id='$data_user[user_id]' AND $filter";
          $absen_out = $connection->query($query_out);

          $query_telat = "SELECT absen_id FROM absen WHERE status_masuk='Telat' AND kehadiran='1' AND user_id='$data_user[user_id]' AND $filter";
          $absen_telat = $connection->query($query_telat);
          echo'
            <p>Masuk : <span class="badge badge-success">'.$absen_in->num_rows.'</span> |  Pulang : <span class="badge badge-danger">'.$absen_out->num_rows.'</span> | Terlambat : <span class="badge badge-info">'.$absen_telat->num_rows.'</span></p>
      </div>
        

        <div class="tanda-tangan">
          <p>'.tgl_indo($date).'<br>Disetujui<br><br><br><br>___________</p>
        </div>
    </section>
</body>
</html>';

  }else{
    echo'
    <title>Daftar Kehadiran</title>
    <div style="font-size:30px;text-align:center;">Data Kehadiran tidak ditemukan!</di>';
  }
?>