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
/* ---------- ADD  ---------- */
case 'add':
    $error = array();
    $id = anti_injection($_POST['id']);
    
    if (empty($_POST['posisi'])) {
      $error[] = 'Posisi tidak boleh kosong';
    } else {
      $posisi  = anti_injection($_POST['posisi']);
    }

    if (empty($_POST['jumlah'])) {
      $error[] = 'Hak Cuti tidak boleh kosong';
    } else {
      $jumlah    = anti_injection($_POST['jumlah']);
    }


  if (empty($error)) {

  $query="SELECT hak_cuti_id FROM hak_cuti WHERE hak_cuti_id='$id'";
  $result= $connection->query($query);
  if(!$result ->num_rows >0){

        /* ---- Tambah data ------*/
        $query_hak_cuti="SELECT hak_cuti_id FROM hak_cuti WHERE posisi_id='$posisi'";
        $result_hak_cuti = $connection->query($query_hak_cuti);
        if(!$result_hak_cuti->num_rows > 0){

          $add ="INSERT INTO hak_cuti(posisi_id,
                jumlah,
                active) values('$posisi',
                '$jumlah',
                'Y')";
            if($connection->query($add) === false) { 
                die($connection->error.__LINE__); 
                echo'Data tidak berhasil disimpan!';
            } else{
                echo'success';
            }
        } else{
          echo 'Hak cuti yang Anda tambah sudah ada!';
        }
      }else{
        /* --  Update data -- */
        $update="UPDATE hak_cuti SET posisi_id='$posisi', jumlah='$jumlah' WHERE hak_cuti_id='$id'"; 
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

  

  /** Set Active Slider */
break;
case 'active':
  $id = htmlentities($_POST['id']);
  $active = htmlentities($_POST['active']);
  $update="UPDATE hak_cuti SET active='$active' WHERE hak_cuti_id='$id'";
  if($connection->query($update) === false) { 
    echo 'error';
    die($connection->error.__LINE__); 
  }
  else{
    echo'success';
  }


/* --------------- Delete ------------*/
    break;
    case 'delete':
        $id       = anti_injection(epm_decode($_POST['id']));
        $deleted = "DELETE FROM hak_cuti WHERE hak_cuti_id='$id'";
        if($connection->query($deleted) === true) {
          echo'success';
        } else { 
          //tidak berhasil
          echo'Data tidak berhasil dihapus.!';
          die($connection->error.__LINE__);
        }

break;
}}