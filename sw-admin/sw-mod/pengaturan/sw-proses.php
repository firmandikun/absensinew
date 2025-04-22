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

/** Setting Logo Web  */
  case 'logo':
  $file_name   = $_FILES['file'] ['name'];
  $size        = $_FILES['file'] ['size'];
  $error       = $_FILES['file'] ['error'];
  $tmpName     = $_FILES['file']['tmp_name'];
  $folder      = '../../../sw-content/';
  $valid       = array('jpg','png','gif','jpeg'); 
  if(strlen($file_name)){   
       // Perintah untuk mengecek format gambar
       list($txt,$ext) = explode(".", $file_name);
       $file_ext = substr($file_name, strripos($file_name, '.'));

       if(in_array($ext,$valid)){   
         if($size<500000){   
           // Perintah pengganti nama files
           $site_logo =''.seo_title($file_name).''.$file_ext.'';
           $pathFile       = $folder.$site_logo;

            $query  = "SELECT site_logo FROM setting WHERE site_id='1'"; 
            $result = $connection->query($query);
            $rows   = $result->fetch_assoc();

            if(file_exists("../../../sw-content/".$rows['site_logo']."")){
              unlink ('../../../sw-content/'.$rows['site_logo'].''); 
            }

           $update ="UPDATE setting SET site_logo='$site_logo' WHERE site_id=1";
            if($connection->query($update) === false) { 
               echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
               die($connection->error.__LINE__); 
            } else   {
                echo'success';
                move_uploaded_file($tmpName, $pathFile);
            }
          }
         else{ // Jika Gambar melebihi size 
              echo'File terlalu besar maksimal files 5MB.!';  
           }         
       }
       else{
          echo 'File yang di unggah tidak sesuai dengan format, File harus jpg, jpeg, gif, png.!';
        }
     }  

     /** Setting Favicon */
     break;
     case 'favicon':
     $file_name   = $_FILES['file'] ['name'];
     $size        = $_FILES['file'] ['size'];
     $error       = $_FILES['file'] ['error'];
     $tmpName     = $_FILES['file']['tmp_name'];
     $folder      = '../../../sw-content/';
     $valid       = array('jpg','png','gif','jpeg'); 

     if(strlen($file_name)){   
          // Perintah untuk mengecek format gambar
          list($txt,$ext) = explode(".", $file_name);
          $file_ext = substr($file_name, strripos($file_name, '.'));
   
          if(in_array($ext,$valid)){   
            if($size<500000){   
              // Perintah pengganti nama files
              $site_favicon =''.seo_title($file_name).''.$file_ext.'';
              $pathFile       = $folder.$site_favicon;
   
                $query = "SELECT site_favicon FROM setting WHERE site_id='1'"; 
                $result = $connection->query($query);
                $rows= $result->fetch_assoc();

                if(file_exists("../../../sw-content/".$rows['site_favicon']."")){
                    unlink ('../../../sw-content/'.$rows['site_favicon'].''); 
                }

              $update ="UPDATE setting SET site_favicon='$site_favicon' WHERE site_id=1";
               if($connection->query($update) === false) { 
                  echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
                  die($connection->error.__LINE__); 
               } else   {
                   echo'success';
                   move_uploaded_file($tmpName, $pathFile);
               }
             }
            else{ // Jika Gambar melebihi size 
                 echo'File terlalu besar maksimal files 5MB.!';  
              }         
          }
          else{
             echo 'File yang di unggah tidak sesuai dengan format, File harus jpg, jpeg, gif, png.!';
           }
        } 

/** Setting web  */
  break;
  case 'setting-web':
  $error = array();
  if (empty($_POST['site_name'])) { 
        $error[] = 'Nama web tidak boleh kosong';
    } else {
      $site_name    = htmlspecialchars(ucfirst($_POST['site_name']));
    }

  if (empty($_POST['site_owner'])) { 
        $error[] = 'Pemilik web tidak boleh kosong';
    } else { 
      $site_owner = anti_injection($_POST['site_owner']); 
  }

  if (empty($_POST['site_phone'])) { 
    $error[] = 'No. Telp tidak boleh kosong';
  } else {
    $site_phone = anti_injection($_POST['site_phone']);
  }

  
  if (empty($_POST['site_email'])) {
    $error[] = 'Email tidak boleh kosong';
  } else {
      if (!filter_var($_POST['site_email'], FILTER_VALIDATE_EMAIL)) {
        $error[] = "Email yang Anda masukan tidak valid"; 
      }else{
        $site_email = htmlentities(strip_tags($_POST['site_email']));
      }
  }

  if (empty($_POST['site_address'])) { 
    $error[] = 'Alamat tidak boleh kosong';
  } else {
    $site_address = htmlentities(htmlspecialchars($_POST['site_address']));
  }

  if (empty($_POST['site_url'])) { 
        $error[] = 'Domain/Url Web tidak boleh kosong';
    } else {
      $site_url = htmlentities(strip_tags($_POST['site_url']));
  }

if (empty($error)) { 
    $update="UPDATE setting SET site_name='$site_name',
                site_phone='$site_phone',
                site_address='$site_address',
                site_owner='$site_owner',
                site_url='$site_url',
                site_email='$site_email' WHERE site_id=1";
          if($connection->query($update) === false) { 
             echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
             die($connection->error.__LINE__); 
          } else   {
            echo'success';
          }
        }
        else{        
          foreach ($error as $key => $values) {            
            echo"$values\n";
          }
      }


/** Setting Absensi */
  break;
  case 'setting-absensi':
  $error = array();
  if (empty($_POST['timezone'])) { 
        $error[] = 'Timezone tidak boleh kosong';
    } else {
      $timezone  = htmlspecialchars(ucfirst($_POST['timezone']));
    }

  if (empty($_POST['tipe_absen'])) { 
        $error[] = 'Tipe Absen tidak boleh kosong';
    } else { 
      $tipe_absen = anti_injection($_POST['tipe_absen']); 
  }

  if (empty($_POST['radius'])) { 
    $radius  = 'N';
  } else {
    $radius = 'Y';
  }

  if (empty($_POST['mulai_absen_masuk'])) { 
    $error[] = 'Absen masuk tidak boleh kosong';
  } else {
    $mulai_absen_masuk  = htmlspecialchars($_POST['mulai_absen_masuk']);
  }

  if (empty($_POST['mulai_absen_pulang'])) { 
    $error[] = 'Absen Pulang tidak boleh kosong';
  } else {
    $mulai_absen_pulang  = htmlspecialchars($_POST['mulai_absen_pulang']);
  }

if (empty($error)) { 
    $update="UPDATE setting_absen SET timezone='$timezone',
                tipe_absen='$tipe_absen',
                radius='$radius',
                mulai_absen_masuk='$mulai_absen_masuk',
                mulai_absen_pulang='$mulai_absen_pulang' WHERE setting_absen_id=1";
          if($connection->query($update) === false) { 
             echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
             die($connection->error.__LINE__); 
          } else   {
            echo'success';
          }
        }
        else{        
          foreach ($error as $key => $values) {            
            echo"$values\n";
          }
  }


  /** Setting Server */
  break;
  case 'setting-server':
  $error = array();

  if (empty($_POST['gmail_host'])) {
    $error[] = 'Host Email tidak boleh kosong';
  } else {
    $gmail_host = htmlentities(strip_tags($_POST['gmail_host']));
  }

  if (empty($_POST['gmail_username'])) { 
        $error[] = 'Username/Email tidak boleh kosong';
    } else { 
      if (!filter_var($_POST['gmail_username'], FILTER_VALIDATE_EMAIL)) {
        $error[] = "Email yang Anda masukan tidak valid"; 
      }else{
        $gmail_username = htmlentities(strip_tags($_POST['gmail_username']));
      }
  }

  if (empty($_POST['gmail_password'])) { 
    $error[] = 'No. Telp tidak boleh kosong';
  } else {
    $gmail_password = anti_injection($_POST['gmail_password']);
  }

  if (empty($_POST['gmail_port'])) { 
    $error[] = 'Alamat tidak boleh kosong';
  } else {
    $gmail_port= htmlentities(htmlspecialchars($_POST['gmail_port']));
  }

  if (empty($_POST['google_client_id'])) { 
        $error[] = 'Client ID tidak boleh kosong';
    } else {
      $google_client_id = htmlentities(strip_tags($_POST['google_client_id']));
  }

  if (empty($_POST['google_client_secret'])) { 
      $error[] = 'Secret tidak boleh kosong';
  } else {
    $google_client_secret = htmlentities(strip_tags($_POST['google_client_secret']));
  }

  if (empty($_POST['gmail_active'])) { 
    $gmail_active = 'N';
  } else {
    $gmail_active = 'Y';
  }

  if (empty($_POST['google_client_active'])) { 
    $google_client_active = 'N';
  } else {
    $google_client_active = 'Y';
  }
if (empty($error)) { 
    $update="UPDATE setting SET gmail_host='$gmail_host',
                gmail_username='$gmail_username',
                gmail_password='$gmail_password',
                gmail_port='$gmail_port',
                gmail_active='$gmail_active',
                google_client_id='$google_client_id',
                google_client_secret='$google_client_secret',
                google_client_active='$google_client_active' WHERE site_id=1";
          if($connection->query($update) === false) { 
             echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
             die($connection->error.__LINE__); 
          } else   {
            echo'success';
          }
        }
        else{        
          foreach ($error as $key => $values) {            
            echo"$values\n";
          }
      }



/** Setting Api WhatsApp */
break;
case 'setting-whatsapp':
  $error = array();

  if (empty($_POST['phone'])) {
    $error[] = 'No. WhatsApp tidak boleh kosong';
  } else {
    $phone = htmlentities(strip_tags($_POST['phone']));
  }

  if (empty($_POST['token'])) { 
    $error[] = 'Api key/Token tidak boleh kosong';
  } else {
    $token = anti_injection($_POST['token']);
  }

  if (empty($_POST['domain_server'])) { 
    $error[] = 'Domain Server tidak boleh kosong';
  } else {
    $domain_server = htmlentities(htmlspecialchars($_POST['domain_server']));
  }

  if (empty($_POST['active'])) { 
    $active = 'N';
  } else {
    $active = 'Y';
  }
  if (empty($error)) { 
    $update="UPDATE whatsapp_api SET phone='$phone',
                token='$token',
                domain_server='$domain_server',
                active='$active' WHERE whatsapp_api_id=1";
          if($connection->query($update) === false) { 
            echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
            die($connection->error.__LINE__); 
          } else   {
            echo'success';
          }
        }
        else{        
          foreach ($error as $key => $values) {            
            echo"$values\n";
          }
      }



/** Setting Api WhatsApp */
break;
case 'setting-whatsapp-v2':
  $error = array();

  if (empty($_POST['phone'])) {
    $error[] = 'No. WhatsApp tidak boleh kosong';
  } else {
    $phone = htmlentities(strip_tags($_POST['phone']));
  }

  if (empty($_POST['token'])) { 
    $error[] = 'Api key/Token tidak boleh kosong';
  } else {
    $token = anti_injection($_POST['token']);
  }

  if (empty($_POST['domain_server'])) { 
    $error[] = 'Domain Server tidak boleh kosong';
  } else {
    $domain_server = htmlentities(htmlspecialchars($_POST['domain_server']));
  }

  if (empty($_POST['active'])) { 
    $active = 'N';
  } else {
    $active = 'Y';
  }
  if (empty($error)){ 
    /** Update V.1 */

    $update="UPDATE whatsapp_api SET phone='$phone',
            token='$token',
            domain_server='$domain_server',
            active='$active' WHERE whatsapp_api_id=2";
      if($connection->query($update) === false) { 
        echo'Pengaturan tidak dapat disimpan, coba ulangi beberapa saat lagi.!';
        die($connection->error.__LINE__); 
      } else   {
        echo'success';
        
      }
    }
    else{        
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
  }


/** Backup Database */
break;
case 'backup-database':
$host = DB_HOST;
$root = DB_USER;
$pass = DB_PASSWD;
$db_name = DB_NAME;
$mysqli = new mysqli($host,$root,$pass,$db_name); 
$mysqli->select_db($db_name); 
$mysqli->query("SET NAMES 'utf8'");
//get table list
$queryTables    = $mysqli->query('SHOW TABLES'); 
while($row = $queryTables->fetch_row()) 
{ 
    $target_tables[] = $row[0]; 
}   
//get table structure
foreach($target_tables as $table)
{
$result = $mysqli->query('SELECT * FROM '.$table);
$fields_amount = $result->field_count;
$rows_num=$mysqli->affected_rows;
$res = $mysqli->query('SHOW CREATE TABLE '.$table);
$TableMLine =$res->fetch_row();
$content =(!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n";
for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0)
{
   while($row = $result->fetch_row())
   { //when started (and every after 100 command cycle):
   if ($st_counter%100 == 0 || $st_counter == 0 )
   {
     $content .= "\nINSERT INTO ".$table." VALUES";
   }
   $content .= "\n(";
   for($j=0; $j<$fields_amount; $j++)
   {
     $row[$j] = str_replace(array("\r\n\r\n","\n\r\n","\r\n","\n\n","\n"),array("\\r\\n","\\r\\n","\\r\\n","\\r\\n","\\r\\n"), addslashes($row[$j]) );
     if (isset($row[$j]))
     {
        $content .= '"'.$row[$j].'"' ;
     }
     else
     {
        $content .= '""';
     }
     if ($j<($fields_amount-1))
     {
        $content.= ',';
     }
   }
   $content .=")";
   //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
   if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num)
   {
     $content .= ";";
   }
   else
   {
     $content .= ",";
   }
   $st_counter=$st_counter+1;
   }
}
}
// save as .sql file
//give additional description
$content_="\n-- Database Backup --\n";
$content_ .="-- Ver. : 1.0.1\n";
$content_ .="-- Host : 127.0.0.1\n";
$content_ .="-- Generating Time : ".date("M d").", ".date("Y")." at ".date("H:i:s:").date("A")."\n";
$content_ .=$content;
//save the file
$backup_file_name = $db_name." ".date("Y-m-d H-i-s").".sql";
$fp = fopen($backup_file_name ,'w+');
$result = fwrite($fp, $content_);
fclose($fp);
//download file directly from browser
$file_path = $backup_file_name;
if(!empty($file_path) && file_exists($file_path)){
header("Pragma:public");
header("Expired:0");
header("Cache-Control:must-revalidate");
header("Content-Control:public");
header("Content-Description: File Transfer");
header("Content-Type: application/octet-stream");
header("Content-Disposition:attachment; filename=\"".basename($file_path)."\"");
header("Content-Transfer-Encoding:binary");
header("Content-Length:".filesize($file_path));
flush();
readfile($file_path);
unlink($file_path);
exit();
}


/** Backup Homepage */
break;
case 'backup-homepage':
  $file_folder = "."; // folder to load files
    $zipname = 'adcs.zip';
    $zip = new ZipArchive;
    $zip->open($zipname, ZipArchive::CREATE);
    if ($handle = opendir('.')) {
      while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != ".." && !strstr($entry,'.php')) {
            $zip->addFile($entry);
        }
      }
      closedir($handle);
    }

    $zip->close();

    header('Content-Type: application/zip');
    header("Content-Disposition: attachment; filename='adcs.zip'");
    header('Content-Length: ' . filesize($zipname));
    header("Location: adcs.zip");

break;
}}