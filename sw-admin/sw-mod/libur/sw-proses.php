<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';

switch (@$_GET['action']){
  case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE libur SET active='$active' WHERE libur_id='$id'";
  if($connection->query($update) === false) { 
    echo 'error';
    die($connection->error.__LINE__); 
  }
  else{
    echo'success';
  }
  


/* ---------- ADD  ---------- */
break;
case 'add':
    $error = array();
    $id = anti_injection($_POST['id']);

    if (empty($_POST['id'])) {
      $id = '';
    } else {
      $id = anti_injection($_POST['id']);
    }

    if (empty($_POST['tanggal'])) {
      $error[] = 'Tanggal Libur tidak boleh kosong';
    } else {
      $tanggal = date('Y-m-d', strtotime($_POST['tanggal']));
    }

    if (empty($_POST['keterangan'])) {
      $error[] = 'Keterangan Libur tidak boleh kosong';
    } else {
      $keterangan = anti_injection($_POST['keterangan']);
    }


  if (empty($error)) {

  $query="SELECT libur_tanggal FROM libur_nasional WHERE libur_nasional_id='$id'";
  $result= $connection->query($query) or die($connection->error.__LINE__);
  if(!$result ->num_rows >0){
    
        /* ---- Tambah data ------*/
        $query_libur="SELECT libur_tanggal FROM libur_nasional WHERE libur_tanggal='$tanggal'";
        $result_libur = $connection->query($query_libur);
        if(!$result_libur->num_rows > 0){

        $add ="INSERT INTO libur_nasional(libur_tanggal,
                keterangan) values('$tanggal',
                '$keterangan')";
          if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
          } else{
              echo'success';
          }
          } else{
            echo 'Tanggal '.$tanggal.' sudah ada!';
          }
      }else{
        /* --  Update data -- */
        $update="UPDATE libur_nasional SET libur_tanggal='$tanggal',
                keterangan='$keterangan' WHERE libur_nasional_id='$id'"; 
        if($connection->query($update) === false) { 
            die($connection->error.__LINE__); 
            echo'Data tidak berhasil disimpan!';
        } else{
            echo'success';
        }
    }
  }
  else{           
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
  }

  
/* --------------- Delete ------------*/
    break;
    case 'delete':
        $id       = anti_injection(epm_decode($_POST['id']));
        $deleted = "DELETE FROM libur_nasional WHERE libur_nasional_id='$id'";
        if($connection->query($deleted) === true) {
          echo'success';
        } else { 
          //tidak berhasil
          echo'Data tidak berhasil dihapus.!';
          die($connection->error.__LINE__);
        }


   

break;
}}