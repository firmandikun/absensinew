<?php ob_start(); session_start();
  include_once 'sw-library/sw-config.php';
  include_once 'sw-library/sw-function.php';
  ob_start("minify_html");
 
  $website_url        = $row_site['site_url'];
  $website_name       = $row_site['site_name'];
  $website_phone      = $row_site['site_phone'];
  $website_addres     = $row_site['site_address'];
  $website_logo       = $row_site['site_logo'];
  $website_email      = $row_site['site_email'];

 
  
  if(isset($_COOKIE['USER_KEY']) && isset($_COOKIE['TOKEN_KEY'])){
    require_once './module/oauth/user.php';
    /** Jam Kerja */
    $query_jam_kerja ="SELECT user_jam_kerja.user_jam_kerja_id,jam_kerja_master.jam_kerja_master_id,jam_kerja.jam_masuk,jam_kerja.jam_pulang FROM user_jam_kerja
    LEFT JOIN jam_kerja_master ON user_jam_kerja.jam_kerja_master_id=jam_kerja_master.jam_kerja_master_id
    LEFT JOIN jam_kerja ON jam_kerja_master.jam_kerja_master_id=jam_kerja.jam_kerja_master_id
    WHERE user_jam_kerja.user_id='$data_user[user_id]' AND hari='$hari_ini' AND jam_kerja.active='Y' AND user_jam_kerja.active='Y'";
    $result_jam_kerja = $connection->query($query_jam_kerja);
    
    /** Absensi hari ini */
    $query_absen = "SELECT absen_in,absen_out FROM absen WHERE tanggal='$date' AND user_id='$data_user[user_id]'";
    $result_absen  = $connection->query($query_absen);
    if($result_absen->num_rows > 0){
      $response = array();
      $response["data_absensi"] = array();
      while ($data_absen = $result_absen->fetch_assoc()){
        $data_row['absen_in'] = $data_absen['absen_in'];
        $data_row['absen_out'] = $data_absen['absen_out'];
        array_push($response['data_absensi'], $data_row);
      }
        $response = json_encode($response);
        $data_absen = json_decode($response, true);
        $result_absen = $result_absen->num_rows;
    }else{
        $response["message"]="tidak ada data";
        $result_absen ='';
    }
  }
$mod = "home";
if(!empty($_GET['mod'])){
  $mod = mysqli_escape_string($connection,@$_GET['mod']);
}
else {$mod ='home';}

require_once 'module/sw-header.php';
if(file_exists("module/$mod/$mod.php")){
    require_once("module/$mod/$mod.php");
}else{
    echo '404';
}
require_once 'module/sw-footer.php';



?>