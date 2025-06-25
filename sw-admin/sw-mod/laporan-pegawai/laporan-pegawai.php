<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='10' AND level_id='$current_user[level]'";
  $result_role = $connection->query($query_role);

  // Tambahkan pengecekan khusus untuk level 3 (atasan)
  if($result_role->num_rows > 0 || $current_user['level'] == 3){
    if($result_role->num_rows > 0){
      $data_role = $result_role->fetch_assoc();
    } else {
      // Default: atasan (level 3) bisa lihat, tidak bisa modifikasi/hapus
      $data_role = ['lihat'=>'Y','modifikasi'=>'N','hapus'=>'N'];
    }

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
                  <li class="breadcrumb-item active" aria-current="page">Laporan Absensi Pegawai</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Laporan Absensi Pegawai</h3>
              <div class="float-right">
                  <button type="button" class="btn btn-outline-default btn-print" data-tipe="pdf"><i class="far fa-file-pdf"></i> PDF</button>
                  <button type="button" class="btn btn-outline-primary btn-print" data-tipe="print"><i class="fas fa-print"></i> PRINT</button>
                  <button type="button" class="btn btn-outline-warning btn-print" data-tipe="excel"><i class="far fa-file-excel"></i> EXCEL</button>
              </div>
            </div>

            <div class="card-body">';
            
             
            echo'
            <div class="row">

            <div class="col-md-3">
                <div class="form-group">
                  <select class="form-control lokasi" name="lokasi" required>
                    <option value="">Pilih Lokasi</option>';
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
                  <select class="form-control pegawai" required>
                    <option value="">Pilih Pegawai</option>
                  </select>
                </div>
              </div>

              <div class="col-md-2">
                  <div class="form-group">
                      <select class="form-control bulan" required>';
                      $bulan_nama =array(1=>"Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember");
                      for($bulan=1; $bulan<=12; $bulan++){
                        if($bulan<=$month ) {
                          echo'<option value="'.$bulan.'" selected>'.$bulan_nama[$bulan].'</option>';
                        }else { 
                          echo'<option value="'.$bulan.'">'.$bulan_nama[$bulan].'</option>'; 
                        }
                      }
                       echo'
                      </select>
                  </div>
              </div>


              <div class="col-md-2">
                  <div class="form-group">
                      <select class="form-control tahun" required>';
                      $mulai= date('Y') - 1;
                      for($i = $mulai;$i<$mulai + 50;$i++){
                          $sel = $i == date('Y') ? ' selected="selected"' : '';
                          echo '<option value="'.$i.'"'.$sel.'>'.$i.'</option>';
                      }
                      echo'
                    </select>
                  </div>
              </div>
              
              <div class="col-md-2">
                <button type="button" class="btn btn-outline-default btn-filter"><i class="fas fa-search"></i> Filter</button>
              </div>
        
            </div>
            </div>

            <div class="table-responsive" style="overflow:auto">';
            if($data_role['lihat']=='Y'){
              echo'<div class="load-data"></div>';
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

