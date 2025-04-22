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
<span class="modifikasi d-none">'.$data_role['modifikasi'].'</span>
<span class="hapus d-none">'.$data_role['hapus'].'</span>
<!-- Header -->
<div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Kunjungan</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Kunjungan</h3>
              <div class="float-right">
                <button type="button" class="btn btn-outline-default btn-print" data-tipe="pdf"><i class="far fa-file-pdf"></i> PDF</button>
                <button type="button" class="btn btn-outline-primary btn-print" data-tipe="print"><i class="fas fa-print"></i> PRINT</button>
                <button type="button" class="btn btn-outline-warning btn-print" data-tipe="excel"><i class="far fa-file-excel"></i> EXCEL</button>
              </div>
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
                    <select class="form-control pegawai" data-toggle="select" name="pegawai" required>
                      <option value="">Semua Pegawai</option>
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

            </div>
            </div>

            <div class="table-responsive">';
            if($data_role['lihat']=='Y'){
              echo'
              <table class="table align-items-center table-flush table-striped datatable" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center" width="5">No</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Lokasi Kunjungan</th>
                    <th>Keterangan</th>
                    <th class="text-center">Foto</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>';
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

  