<?php  @session_start();ob_start();
/* Admin Panel
* (c) 04 September 2024 S-widodo.com
*/
require_once '../sw-library/sw-config.php';
include_once '../sw-library/sw-function.php';

if(!isset($_COOKIE['ADMIN_KEY'])){
  header('location:./login/');
  exit;
}
else{ 
    require_once'./login/user.php';
}

ob_start("minify_html");

if(!empty($_GET['mod'])){$mod = mysqli_escape_string($connection,@$_GET['mod']);}else {$mod ='home';}
  include_once 'sw-mod/header.php';
  if(file_exists('./sw-mod/'.$mod.'/'.$mod.'.php')){
        include('./sw-mod/'.$mod.'/'.$mod.'.php');
        include_once 'sw-mod/footer.php';
        //theme_foot();
  }else{
        include('./sw-mod/home/home.php');
        include_once './sw-mod/footer.php';
        //theme_foot();
  }
  function theme_404(){
    echo'
    <div class="text-center">
    <h1 class="display-1 mb-20 text-info"><i class="ni ni-spaceship"></i></h1>
    <h1 class="display-1 mb-10 mt-10">404</h1>
     <h4 class="mb-10">Sepertinya Halaman yang anda tidak ditemukan</h4>
     <button type="button" class="btn btn-primary mt-4" onclick="history.back()">Kembali</button>
    </div>';
  }

  function hak_akses(){
    echo'
    <div class="text-center">
    <h1 class="display-1 mb-20 text-info"><i class="ni ni-spaceship"></i></h1>
    <h1 class="display-1 mb-10 mt-10">Oop</h1>
     <h4 class="mb-10">Anda tidak memiliki hak Akses halaman ini</h4>
     <button type="button" class="btn btn-primary mt-4" onclick="history.back()">Kembali</button>
    </div>';
  }
  //}
  //ob_end_flush(); // minify_html
?>