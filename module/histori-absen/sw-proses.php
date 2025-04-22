<?php if(!isset($_COOKIE['USER_KEY'])){
  //header('location:../../404');
  echo'Not Found';
}else{
  require_once'../../sw-library/sw-config.php';
  require_once'../../sw-library/sw-function.php';
  require_once'../../sw-library/csrf.php';
  require_once'../../module/oauth/user.php';

switch (@$_GET['action']){
case 'data-histori':

if (empty($_GET['mulai']) OR empty($_GET['selesai'])){ 
  $filter = "AND MONTH(tanggal) ='$month' AND YEAR(tanggal) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_GET['mulai']));
  $selesai = date('Y-m-d', strtotime($_GET['selesai']));
  $filter = "AND tanggal BETWEEN  '$mulai' AND '$selesai'";
}
$query_histori ="SELECT absen_id,tanggal,absen_in,absen_out,status_masuk,kehadiran,latitude_longtitude_in,latitude_longtitude_out,keterangan FROM absen WHERE user_id='$data_user[user_id]' $filter ORDER BY absen_id DESC LIMIT 15";
$result_histori = $connection->query($query_histori);
if($result_histori->num_rows > 0){
    while ($data_histori= $result_histori->fetch_assoc()) {
      $absen_id =  htmlentities($data_histori['absen_id']);
        if($data_histori['kehadiran'] == 'Hadir'){
            if($data_histori['status_masuk']=='Telat'){
                $status ='<button class="btn btn-danger btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['status_masuk']).'"><i class="material-icons">sentiment_dissatisfied</i></button>';
            }else{
                $status ='<button class="btn btn-default btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['status_masuk']).'"><i class="material-icons">mood</i></button>';
            }
        }else{
            $status ='<button class="btn btn-warning btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['kehadiran']).'"><i class="material-icons">sentiment_dissatisfied</i></button>';
        }

        if($data_histori['kehadiran'] == 'Hadir'){
            $kehadiran = '';
          }else{
            $kehadiran = '<span class="badge badge-info">'.strip_tags($data_histori['kehadiran']).'</span>';
        }

        echo'
        <div class="card border-0 mb-2">
            <div class="card-body">
                <div class="row">
                    <div class="col">
                        <p class="text-secondary">'.format_hari_tanggal($data_histori['tanggal']).' '.$kehadiran.'</p>
                    </div> 
                </div>

                <div class="row align-items-center">
                    <div class="col-5 align-self-center">
                        <small class="text-secondary">CHECK IN</small>
                        <p class="text-info">'.strip_tags($data_histori['absen_in']).'</p>
                    </div>
                    <div class="col align-self-center">
                        <small class="text-secondary">CHECK OUT</small>
                        <p class="text-success">';
                        if($data_histori['absen_out']=='00:00:00'){
                          echo'-';
                        }else{
                          echo''.strip_tags($data_histori['absen_out']).'';
                        }
                        echo'
                        </p>
                    </div>
                    <div class="col-auto">
                        '.$status.'
                        <a href="#" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['absen_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['absen_id'].'">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                    </div>
                </div>
                <!-- Collapse -->
                    <div class="collapse mt-1" id="collapse'.$data_histori['absen_id'].'">
                      <div class="row align-items-center">
                          <div class="col-8 align-self-center">
                              <p class="text-secondary">'.strip_tags($data_histori['keterangan']).'</p>
                          </div>
                          <div class="col text-right">
                              <div class="pull-right">';
                              if(!$data_histori['latitude_longtitude_in'] ==''){
                                echo'
                                <a href="javascript:void(0);" class="btn-link text-dark btn-map mr-1" data-toggle="tooltip" data-placement="top" title="Absen Masuk" data-map="'.strip_tags($data_histori['latitude_longtitude_in']).'" data-title="Masuk">
                                  <i class="fas fa-map-marker-alt"></i></i>
                                </a>';
                              }

                              if(!$data_histori['latitude_longtitude_out'] ==''){
                              echo'
                                <a href="javascript:void(0);" class="btn-link text-danger btn-map mr-1" data-toggle="tooltip" data-placement="top" title="Absensi Pulang" data-map="'.strip_tags($data_histori['latitude_longtitude_out']).'" data-title="Pulang">
                                  <i class="fas fa-map-marker-alt"></i></i>
                                </a>';
                              }
                              echo'
                                <a href="javascript:void(0);" class="btn-link text-dark btn-view mr-2" data-id="'.convert("encrypt",$data_histori['absen_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal']).'" data-toggle="tooltip" data-placement="top" title="Tampilkan">
                                  <i class="fas fa-eye"></i>
                                </a>

                                 <a href="javascript:void(0);" class="btn-link text-dark btn-update mr-1" data-id="'.convert("encrypt",$data_histori['absen_id']).'" data-keterangan="'.strip_tags($data_histori['keterangan']).'"  data-tanggal="'.tanggal_ind($data_histori['tanggal']).'" data-toggle="tooltip" data-placement="top" title="Beri keterangan">
                                  <i class="fa-solid fa-pencil"></i>
                                </a>
                              </div>
                          </div>
                      </div>
                    </div>
            </div>
        </div>';
    }
      echo'
      <div class="text-center show_more_main'.$absen_id.' mt-4">
          <button data-id="'.$absen_id.'" class="btn btn-light rounded load-more">Show more</button>
      </div>';
  }else{
      echo'<div class="alert alert-secondary mt-3">Saat ini data Absensi masih kosong</div>';
  }


  /** Load More data absensi */
break;
case 'data-histori-load':
$id = anti_injection($_POST['id']);

if (empty($_POST['mulai']) OR empty($_POST['selesai'])){ 
  $filter = "AND MONTH(tanggal) ='$month' AND YEAR(tanggal) ='$year'";
} else { 
  $mulai = date('Y-m-d', strtotime($_POST['mulai']));
  $selesai = date('Y-m-d', strtotime($_POST['selesai']));
  $filter = "AND tanggal BETWEEN '$mulai' AND '$selesai'";
}


$query_count    ="SELECT COUNT(absen_id) AS total FROM absen WHERE user_id='$data_user[user_id]' $filter AND absen_id < $id ORDER BY absen_id DESC";
$result_count   = $connection->query($query_count);
$data_count     = $result_count->fetch_assoc();
$totalRowCount  = $data_count['total'];

$showLimit = 15;
$query_histori ="SELECT absen_id,tanggal,absen_in,absen_out,status_masuk,kehadiran,latitude_longtitude_in,latitude_longtitude_out,keterangan FROM absen WHERE user_id='$data_user[user_id]' $filter AND absen_id < $id ORDER BY absen_id DESC LIMIT $showLimit";
$result_histori = $connection->query($query_histori);


if($result_histori->num_rows > 0){
    while ($data_histori= $result_histori->fetch_assoc()) {
      $absen_id =  htmlentities($data_histori['absen_id']);
        if($data_histori['kehadiran'] == 'Hadir'){
          if($data_histori['status_masuk']=='Telat'){
              $status ='<button class="btn btn-danger btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['status_masuk']).'"><i class="material-icons">sentiment_dissatisfied</i></button>';
          }else{
              $status ='<button class="btn btn-default btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['status_masuk']).'"><i class="material-icons">mood</i></button>';
          }
        }else{
            $status ='<button class="btn btn-warning btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['kehadiran']).'"><i class="material-icons">sentiment_dissatisfied</i></button>';
        }

        if($data_histori['kehadiran'] == 'Hadir'){
            $kehadiran = '';
          }else{
            $kehadiran = '<span class="badge badge-info">'.strip_tags($data_histori['kehadiran']).'</span>';
        }
        echo'
        <div class="card border-0 mb-2">
            <div class="card-body">
                <div class="row">
                  <div class="col-5 align-self-center">
                      <p class="text-secondary">'.format_hari_tanggal($data_histori['tanggal']).'  '.$kehadiran.'</p>
                  </div> 
                </div>

                <div class="row align-items-center">
                    <div class="col-5 align-self-center">
                        <small class="text-secondary">CHECK IN</small>
                        <p class="text-info">'.strip_tags($data_histori['absen_in']).'</p>
                    </div>
                    <div class="col align-self-center">
                        <small class="text-secondary">CHECK OUT</small>
                        <p class="text-success">';
                        if($data_histori['absen_out']=='00:00:00'){
                          echo'-';
                        }else{
                          echo''.strip_tags($data_histori['absen_out']).'';
                        }
                        echo'
                        </p>
                    </div>
                    <div class="col-auto">
                        '.$status.'
                        <a href="#" class="btn btn-sm btn-link text-dark" data-toggle="collapse" data-target="#collapse'.$data_histori['absen_id'].'" aria-expanded="false" aria-controls="collapse'.$data_histori['absen_id'].'">
                          <i class="fas fa-ellipsis-v"></i>
                        </a>
                    </div>
                </div>
                <!-- Collapse -->
                    <div class="collapse mt-1" id="collapse'.$data_histori['absen_id'].'">
                      <div class="row align-items-center">
                          <div class="col-8 align-self-center">
                              <p class="text-secondary">'.strip_tags($data_histori['keterangan']).'</p>
                          </div>
                          <div class="col text-right">
                              <div class="pull-right">';
                              if(!$data_histori['latitude_longtitude_in'] ==''){
                                echo'
                                <a href="javascript:void(0);" class="btn-link text-dark btn-map mr-1" data-toggle="tooltip" data-placement="top" title="Absen Masuk" data-map="'.strip_tags($data_histori['latitude_longtitude_in']).'" data-title="Masuk">
                                  <i class="fas fa-map-marker-alt"></i></i>
                                </a>';
                              }

                              if(!$data_histori['latitude_longtitude_out'] ==''){
                              echo'
                                <a href="javascript:void(0);" class="btn-link text-danger btn-map mr-1" data-toggle="tooltip" data-placement="top" title="Absensi Pulang" data-map="'.strip_tags($data_histori['latitude_longtitude_out']).'" data-title="Pulang">
                                  <i class="fas fa-map-marker-alt"></i></i>
                                </a>';
                              }
                              echo'
                                <a href="javascript:void(0);" class="btn-link text-dark btn-view mr-2" data-id="'.convert("encrypt",$data_histori['absen_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal']).'" data-toggle="tooltip" data-placement="top" title="Tampilkan">
                                  <i class="fas fa-eye"></i>
                                </a>
                                 <a href="javascript:void(0);" class="btn-link text-dark btn-update mr-1" data-id="'.convert("encrypt",$data_histori['absen_id']).'" data-tanggal="'.tanggal_ind($data_histori['tanggal']).'" data-keterangan="'.strip_tags($data_histori['keterangan']).'" data-toggle="tooltip" data-placement="top" title="Beri keterangan">
                                  <i class="fa-solid fa-pencil"></i>
                                </a>
                              </div>
                          </div>
                      </div>
                    </div>
            </div>
        </div>';
    }
    
    if($totalRowCount > $showLimit){
      echo'
      <div class="text-center show_more_main'.$absen_id.' mt-4">
          <button data-id="'.$absen_id.'" class="btn btn-light rounded load-more">Show more</button>
      </div>';
    }
  }else{
      echo'<div class="alert alert-secondary mt-3">Saat ini data kehdarian tidak ada</div>';
  }

  

/** Update Keterangan */
break;
case 'update':
  $error = array();
      if (empty($_POST['id'])) {
        $error[] = 'NIP tidak boleh kosong';
      } else {
        $id = htmlentities(convert("decrypt",$_POST['id']));
      }
  
      if (empty($_POST['keterangan'])) {
          $error[] = 'Keterangan tidak boleh kosong';
        } else {
          $keterangan = anti_injection($_POST['keterangan']);
      }

      
    if (empty($error)) {
      $update="UPDATE absen SET keterangan='$keterangan' WHERE absen_id='$id' AND user_id='$data_user[user_id]'"; 
      if($connection->query($update) === false) { 
          die($connection->error.__LINE__); 
          echo'Data tidak berhasil disimpan!';
      } else{
          echo'success';
      }

    }else{
      foreach ($error as $key => $values) {            
        echo"$values\n";
      }
    }


/** View Details Absen */
break;
case 'details':
if(!empty($_POST['id'])){
  $id = htmlentities(convert("decrypt",$_POST['id']));
}else{
  $id = '';
}

$query_absen ="SELECT absen_id,tanggal,absen_in,absen_out,foto_in,foto_out,keterangan,latitude_longtitude_in,latitude_longtitude_out,tipe FROM absen WHERE user_id='$data_user[user_id]' AND absen_id='$id'";
$result_absen = $connection->query($query_absen);
if($result_absen->num_rows > 0){
  $data_absen= $result_absen->fetch_assoc();

  if($data_absen['latitude_longtitude_in'] =='-' OR $data_absen['latitude_longtitude_in'] ==''){
    $map_in ='';
  }else{
    $map_in = '<a href="https://www.google.com/maps/place/'.$data_absen['latitude_longtitude_in'].'" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-map-marker-alt"></i> IN</a>';
  }

  if($data_absen['latitude_longtitude_out'] =='-' OR $data_absen['latitude_longtitude_out'] ==''){
    $map_out ='';
  }else{
    $map_out = '<a href="https://www.google.com/maps/place/'.$data_absen['latitude_longtitude_out'].'" class="btn btn-info btn-sm" target="_blank"><i class="fas fa-map-marker-alt"></i> OUT</a>';
  }

  if($data_absen['tipe']=='Foto'){
      echo'
      <div class="row">
        <div class="col-md-6">
          <div class="card border-0 mb-4 overflow-hidden">
            <div class="position-relative">';
              if(file_exists('../../sw-content/absen/'.$data_absen['foto_in'].'')){
                echo'<img src="./sw-content/absen/'.$data_absen['foto_in'].'" height="180" width="100%">';
              }else{
                  echo'<img src="./sw-content/thumbnail.jpg" height="180" width="100%">';
              }
              echo'
              <div class="bottom-left m-2">
                  <button class="btn btn-sm btn-default rounded">'.$data_absen['absen_in'].'</button>
              </div>
            </div>
            <div class="card-body text-center">
                <p class="mt-1">Masuk</p>
            
            </div>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card border-0 mb-4 overflow-hidden">
              <div class="position-relative">';
                if(file_exists('../../sw-content/absen/'.$data_absen['foto_out'].'')){
                  if(!$data_absen['foto_out']==''){
                    echo'<img src="./sw-content/absen/'.$data_absen['foto_out'].'" height="180" width="100%">';
                  }else{
                    echo'<img src="./sw-content/thumbnail.jpg" height="180" width="100%">';
                  }
                }else{
                    echo'<img src="./sw-content/thumbnail.jpg" height="180" width="100%">';
                }

                if(!$data_absen['absen_out']=='00:00:00'){
                echo'
                <div class="bottom-left m-2">
                  <button class="btn btn-sm btn-default rounded">'.$data_absen['absen_out'].'</button>
                </div>';
                }
                echo'
              </div>
              <div class="card-body text-center">
                  <p class="mt-1">Pulang</p>
                  
              </div>
            </div>
        </div>

        <div class="col-md-12">
          <p>'.strip_tags($data_absen['keterangan']).'</p>
        </div>

      </div>';
  }else{
    echo'
    <div class="row">
      <div class="col-md-12">
        <table class="table table-bordered">
          <tr>
            <td width="30%">Tipe Absen</td>
            <td>'.ucfirst($data_absen['tipe']).'</td>
          </tr>
          <tr>
            <td>Keterangan</td>
            <td>'.strip_tags($data_absen['keterangan']).'</td>
          </tr>
          <tr>
            <td>Lokasi Absen</td>
            <td>'.$map_in.' '.$map_out.'</td>
          </tr>
        </table>
      </div>
    </div>';
  }

}else{
  echo'<div class="alert alert-info">Data tidak ditemukan!</div>';
}



/** buka Map */
break;
case'map':
$lokasi = htmlentities($_GET['lokasi']);
echo'
<link rel="stylesheet" href="../../template/vendor/leatfet/leaflet.css">
<script src="https://npmcdn.com/leaflet@0.7.7/dist/leaflet.js"></script>
<style>
  iframe{
    border:0px!important;
  }
</style>
<div id="peta" style="height:500px; width: 100%"></div>';?>
  <script type="text/javascript">
    var mymap = L.map('peta').setView([<?php echo $lokasi;?>], 13);
          L.tileLayer('https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1Ijoid2lkb2RvMTk5MSIsImEiOiJja3AzcG5zYW0xamVnMm9xaWNnamI1ODRpIn0.wr-0_-8cP9KfDPiesVdoPw', {
            maxZoom: 18,
            attribution:'',
            id: 'mapbox/streets-v11',
            tileSize: 512,
            zoomOffset:-1,
            accessToken: 'pk.eyJ1IjoiZmF1emF5eXkiLCJhIjoiY2tqZng3OWw5MDlmejJ0cW9vbWg1bXlvMCJ9.zn3d3ptHQ38xKp4yM_55SQ'
          }).addTo(mymap);
        L.marker([<?php echo $lokasi;?>]).addTo(mymap);
        L.circle([<?php echo $lokasi;?>], 10, {
        color: 'red',
        fillColor: '#f03',
        fillOpacity: 0.5
    }).addTo(mymap).bindPopup("Lokasi Saya").openPopup();
    
    var popup = L.popup();
    function onMapClick(e) {
      popup
      .setLatLng(e.latlng)
      .setContent(""+ e.latlng.toString())
      .openOn(mymap);
    }
    mymap.on('click', onMapClick);
  </script>

<?php 
break;
  }
}