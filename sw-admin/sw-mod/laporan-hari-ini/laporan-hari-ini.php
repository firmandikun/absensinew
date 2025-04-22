<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='10' AND level_id='$current_user[level]'";
  $result_role = $connection->query($query_role);
  if($result_role->num_rows > 0){
    $data_role = $result_role->fetch_assoc();

  switch(@$_GET['op']){ 
    default:
echo'

<!-- Header -->
<div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Laporan hari ini</li>
                </ol>
              </nav>
            </div>
            
          </div>
        </div>
      </div>
    </div>
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card pb-3">
            <!-- Card header -->
            <div class="card-header mb-2">
              <h3 class="mt-2 mb-0 text-left float-left">Laporan hari ini</h3>
            </div>

            <div class="card-body">
            <div class="row">
              <div class="col-md-1">
                Filter :
              </div>

              <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control lokasi" name="lokasi" required>
                    <option value="">Semua Lokasi</option>';
                      $query_lokasi ="SELECT lokasi_id,lokasi_nama FROM lokasi ORDER BY lokasi_nama ASC";
                      $result_lokasi = $connection->query($query_lokasi);
                      while ($data_lokasi = $result_lokasi->fetch_assoc()) {
                        echo'<option value="'.$data_lokasi['lokasi_id'].'">'.$data_lokasi['lokasi_nama'].'</option>';
                      }
                    echo'
                  </select>
                </div>
              </div>

              <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control posisi" name="posisi" required>
                    <option value="">Semua Posisi</option>';
                      $query_posisi ="SELECT * FROM posisi ORDER BY posisi_nama ASC";
                      $result_posisi = $connection->query($query_posisi);
                      while ($data_posisi = $result_posisi->fetch_assoc()) {
                        echo'<option value="'.$data_posisi['posisi_id'].'">'.$data_posisi['posisi_nama'].'</option>';
                      }
                    echo'
                    </select>
                </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                    <div class="input-group input-group-merge">
                      <div class="input-group-prepend">
                        <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                      </div>
                      <input class="form-control datepicker tanggal" value="'.tanggal_ind($date).'" placeholder="Tanggal" type="text">
                    </div>
                  </div>
              </div>

              <div class="col-md-3">
                  <button type="button" class="btn btn-outline-default btn-print" data-tipe="pdf"><i class="far fa-file-pdf"></i> PDF</button>
                  <button type="button" class="btn btn-outline-primary btn-print" data-tipe="print"><i class="fas fa-print"></i> PRINT</button>
                  <button type="button" class="btn btn-outline-warning btn-print" data-tipe="excel"><i class="far fa-file-excel"></i> EXCEL</button>
              </div>

            </div>
            </div>

            <div class="table-responsive">';
            if($data_role['lihat']=='Y'){
              echo'
              <div class="load-data"></div>';
            }else{
              hak_akses();
            }
            echo'
            </div>
          </div>
        </div>
      </div>';

  break;
  }
  }else{
    theme_404();
  }
}?>

  