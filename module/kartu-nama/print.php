<?php 
require_once'../../sw-library/sw-config.php'; 
require_once'../../sw-library/sw-function.php';
include_once'../../sw-library/pdf/autoload.php';
require_once'../../module/oauth/user.php';

$query_tema ="SELECT foto FROM kartu_nama WHERE active='Y'";
$result_tema = $connection->query($query_tema);
$data_tema = $result_tema->fetch_assoc();
$query_posisi = "SELECT posisi_nama FROM posisi WHERE posisi_id ='$data_user[posisi_id]'";
$result_posisi = $connection->query($query_posisi);
if($result_posisi->num_rows > 0){
    $data_posisi = $result_posisi->fetch_assoc();
    $posisi = strip_tags($data_posisi['posisi_nama']);
}else{
    $posisi ='-';
}


echo'
<!DOCTYPE html>
<html>
<head>
    <meta name="robots" content="noindex">
    <meta name="author" content="s-widodo.com">
    <meta http-equiv="Copyright" content="s-widodo.com">
    <meta name="copyright" content="s-widodo.com">
    <link rel="icon" href="'.$site_url.'/sw-content/'.$site_favicon.'" sizes="32x32" type="image/png">
    <title>ID Card '.$data_user['nama_lengkap'].'</title>
    <style>
      body{font-family:Arial,Helvetica,sans-serif}.container_box{position:relative;}
      .container_box h3{padding:10px 0;line-height:25px;font-size:20px;margin:5px 0 0px 0px;padding:0px;
        text-transform: uppercase;}
      p{padding:0px;margin:0px;line-height:25px;}
      .container_box .text-center{text-align:center}.container_box .content_box{position:relative;margin-top:30px;}
      .container_box .content_box .des_info{margin:20px 0;text-align:right}

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
        margin-top:0px;
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
     <script>
        window.onafterprint = window.close;
        window.print();
      </script>
</head>
<body>
    <section class="container_box">
      <div class="content_box">
          <div class="thema-id-card" style="background: url(./sw-content/tema/'.$data_tema['foto'].')">
              <div class="logo">
                  <img src="./sw-content/'.$site_logo.'" height="30">
              </div>

              <div class="qrcode">
                  <img src="./sw-content/qrcode/'.seo_title($data_user['qrcode']).'.jpg" style="height:100px;width: 100px;border:solid 4px #1770ff;">
              </div>
              <div class="description">
                <p class="bold">'.strip_tags($data_user['nama_lengkap']).'</p>
                <p>'.strip_tags($data_user['nip']).'</p>
              </div>

              <table>
                <tr>
                  <td width="150">
                    <div class="position">
                    <p>'.$posisi.'</p>
                    </div> 
                  </td>
                  <td><br>
                    <div class="avatar">';
                    if(file_exists('../../sw-content/avatar/'.$data_user['avatar'].'')){
                        echo'<img src="data:image/gif;base64,'.base64_encode(file_get_contents('../../sw-content/avatar/'.$data_user['avatar'].'')).'" height="50" style="border:solid 2px #1770ff;">';
                    }else{
                        echo'<img src="./sw-content/avatar/avatar.jpg" height="50" style="border:solid 2px #1770ff;">';
                    }
                    echo'
                    </div>
                  </td>
                </tr>
              </table>
          </div>

          <!-- Belakang-->
          <div class="thema-id-card" style="background: url(./sw-content/tema/'.$data_tema['foto'].')">
              <div class="logo">
                  <img src="./sw-content/'.$site_logo.'" height="30">
              </div>

              <div class="qrcode">
                  <img src="./sw-content/qrcode/'.seo_title($data_user['qrcode']).'.jpg" style="height:140px;width: 140px;border:solid 4px #1770ff;">
              </div>
              <div class="description">
                <p class="bold">'.strip_tags($data_user['nama_lengkap']).'</p>
                <p>'.strip_tags($data_user['nip']).'</p>
              </div>

          </div>

      </div>
    </section>
</body>
</html>';
?>