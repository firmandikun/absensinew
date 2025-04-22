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
    if (empty($_POST['posisi_nama'])) {
      $error[] = 'Nama tidak boleh kosong';
    } else {
      $posisi_nama = anti_injection($_POST['posisi_nama']);
    }


  if (empty($error)) {

  $query="SELECT posisi_id from posisi where posisi_id='$id'";
  $result= $connection->query($query) or die($connection->error.__LINE__);
  if(!$result ->num_rows >0){
        /* ---- Tambah data ------*/
          $query_posisi="SELECT posisi_nama from posisi WHERE posisi_nama='$posisi_nama'";
          $result_posisi = $connection->query($query_posisi);
          if(!$result_posisi->num_rows > 0){

        $add ="INSERT INTO posisi(posisi_nama) values('$posisi_nama')";
          if($connection->query($add) === false) { 
              die($connection->error.__LINE__); 
              echo'Data tidak berhasil disimpan!';
          } else{
              echo'success';
          }
          } else{
            echo 'Posisi/Jabatan '.$posisi_nama.' sudah ada!';
          }
      }else{
        /* --  Update data -- */
        $update="UPDATE posisi SET posisi_nama='$posisi_nama' WHERE posisi_id='$id'"; 
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
        $query_posisi ="SELECT posisi.posisi_id,user.posisi_id FROM posisi
        JOIN user
        ON posisi.posisi_id =user.posisi_id WHERE posisi.posisi_id='$id'";
        $result_posisi = $connection->query($query_posisi);
        if(!$result_posisi->num_rows > 0){
          $row = $result_posisi->fetch_assoc();
            /* Script Delete Data ------------*/
              $deleted = "DELETE FROM posisi WHERE posisi_id='$id'";
              if($connection->query($deleted) === true) {
                echo'success';
              } else { 
                //tidak berhasil
                echo'Data tidak berhasil dihapus.!';
                die($connection->error.__LINE__);
              }

        }else{
          echo 'Data Posisi ini aktif atau digunakan!';
        }
   

break;
}}