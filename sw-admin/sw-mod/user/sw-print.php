<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/user.php';
include_once'../../../sw-library/pdf/autoload.php';

if(!empty($_GET['posisi']) AND !empty($_GET['pegawai'])){
  $posisi    = anti_injection($_GET['posisi']);
  $pegawai   = anti_injection($_GET['pegawai']);
  $filter  = "user.posisi_id='$posisi' AND user.user_id='$pegawai'";
} else{
  $posisi    = anti_injection($_GET['posisi']);
  $filter  = "user.posisi_id='$posisi'";
}

$query_tema ="SELECT foto FROM kartu_nama WHERE active='Y'";
$result_tema = $connection->query($query_tema);
$data_tema = $result_tema->fetch_assoc();


$query_pegawai ="SELECT nip,nama_lengkap,posisi_nama,qrcode,avatar FROM user 
INNER JOIN posisi ON user.posisi_id= posisi.posisi_id AND $filter";
$result_pegawai = $connection->query($query_pegawai);
if($result_pegawai->num_rows > 0){
  
$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L']);
ob_start();

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
    <title>ID Card</title>
    <style>
      body{font-family:Arial,Helvetica,sans-serif}
      .content-box {
        text-align: center;
        float: left;
        width: 500px;
      }

      .thema-id-card{
        position: relative;
        display: inline-block;
        background: #ffffff;
        background-size:100%!important;
        width :200px;
        height:340px;
        border-radius:10px;
        border:solid 1px #cccccc;
        padding: 20px;
        margin:1px;
        text-align:center;
        float:left;
      }
      
      .thema-id-card .logo{
        display: inline-block;
        margin-top:20px;
      }
      
      .thema-id-card .logo img{
        height:30px;
      }
      
      .thema-id-card .qrcode{
        display: inline-block;
        margin-top:20px;
      }
      
      .thema-id-card .qrcode img{
        height:100px;
        width: 100px;
      }
      
      .thema-id-card .description{
        margin-top:10px;
      }
      .thema-id-card .description p{
        font-size: 14px;
        line-height:25px;
        padding: 0px;
        margin: auto;
      }
      
      .thema-id-card .description p.bold{
        font-size:16px!important;
        font-weight: 600;
      }
      
      .thema-id-card .avatar{
        margin-top:40px;
      }

      .thema-id-card .avatar img{
        height:50px;
        width:50px;
      }
      
      .thema-id-card .position{
        position:absolute;
        bottom:55px;
        left:20px;
      }
      

    </style>
</head>
<body>';
    while ($data_pegawai = $result_pegawai->fetch_assoc()){
        echo'
      <div class="content-box">
          <div class="thema-id-card" style="background: url(../../../sw-content/tema/'.$data_tema['foto'].')">
              <div class="logo">
                  <img src="../../../sw-content/'.$site_logo.'" height="30">
              </div>

              <div class="qrcode">
                  <img src="../../../sw-content/qrcode/'.seo_title($data_pegawai['qrcode']).'.jpg" style="height:100px;width: 100px;border:solid 4px #1770ff;">
              </div>

              <div class="description">
                <p class="bold">'.strip_tags($data_pegawai['nama_lengkap']).'</p>
                <p>'.strip_tags($data_pegawai['nip']).'</p>
              </div>

              <table>
                <tr>
                  <td width="150">
                    <div class="position">
                    <p>'.$data_pegawai['posisi_nama'].'</p>
                    </div> 
                  </td>
                  <td><br>
                    <div class="avatar">';
                    if(file_exists('../../../sw-content/avatar/'.$data_pegawai['avatar'].'')){
                        echo'<img src="data:image/gif;base64,'.base64_encode(file_get_contents('../../../sw-content/avatar/'.$data_pegawai['avatar'].'')).'" height="50" style="border:solid 2px #1770ff;">';
                    }else{
                        echo'<img src="../../../sw-content/avatar/avatar.jpg" height="50" style="border:solid 2px #1770ff;">';
                    }
                    echo'
                    </div>
                  </td>
                </tr>
              </table>
          </div>

          <!-- Belakang-->
          <div class="thema-id-card" style="background: url(../../../sw-content/tema/'.$data_tema['foto'].')">
              <div class="logo">
                  <img src="../../../sw-content/'.$site_logo.'" height="30">
              </div>

              <div class="qrcode">
                  <img src="../../../sw-content/qrcode/'.seo_title($data_pegawai['qrcode']).'.jpg" style="height:140px;width: 140px;border:solid 4px #1770ff;">
              </div>
              <div class="description">
                <p class="bold">'.strip_tags($data_pegawai['nama_lengkap']).'</p>
                <p>'.strip_tags($data_pegawai['nip']).'</p>
              </div>
          </div>

      </div>';
    }
echo'
</body>
</html>';
  $former = error_reporting(E_ALL ^ E_NOTICE);
  $mpdf->debug = true;
  $mpdf->useSubstitutions=false;
  $mpdf->simpleTables = true;
  $html = ob_get_contents(); 
  ob_end_clean();
  $mpdf->WriteHTML(utf8_encode($html));
  $mpdf->Output("ID-Card-$date.pdf" ,'I');
}else{
  echo'<div style="font-size:30px;text-align:center;margin-top:30px;">Data tidak ditemukan</div>
  <center><button onclick="window.close();" style="background:#111111;padding:8px 20px;color:#ffffff;border-radius:10px;">KEMBALI</button></center>';
}

}?>