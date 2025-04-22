<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
require_once'../../../sw-library/sw-function.php';
require_once'../../login/user.php';

switch (@$_GET['action']){
    case 'delete':
        $id       = anti_injection(epm_decode($_POST['id']));
      /* Script Delete Data ------------*/
        $deleted = "DELETE FROM tugas WHERE tugas_id='$id'";
        if($connection->query($deleted) === true) {
          echo'success';
        } else { 
          //tidak berhasil
          echo'Data tidak berhasil dihapus.!';
          die($connection->error.__LINE__);
        }

    
break;
}}