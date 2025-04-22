<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='3' AND level_id='$current_user[level]'";
  $result_role = $connection->query($query_role);
  if($result_role->num_rows > 0){
    $data_role = $result_role->fetch_assoc();

switch(@$_GET['op']){ 
default:

if(!empty($_GET['user'])){
  $user = epm_decode($_GET['user']);
  $filter ="WHERE user_id='$user'";
}else{
  $filter ='';
}

echo'
<span class="modifikasi d-none">'.$data_role['modifikasi'].'</span>
<span class="hapus d-none">'.$data_role['hapus'].'</span>

<!-- Header -->
<div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
            <h6 class="h2 text-white d-inline-block mb-0">Laporan Tugas</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="./tugas">Tugas</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Laporan Tugas</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Laporan Tugas</h3>
              <div class="float-right">
                <button type="button" class="btn btn-outline-default btn-print" data-tipe="pdf"><i class="far fa-file-pdf"></i> PDF</button>
                <button type="button" class="btn btn-outline-primary btn-print" data-tipe="print"><i class="fas fa-print"></i> PRINT</button>
                <button type="button" class="btn btn-outline-warning btn-print" data-tipe="excel"><i class="far fa-file-excel"></i> EXCEL</button>
              </div>
            </div>';
        if($data_role['lihat']=='Y'){
        echo'
        <div class="card-body">
        <div class="row">
              <div class="col-md-1">
                Filter :
              </div>
              <div class="col-md-3">
                <div class="form-group">
                    <select class="form-control pegawai" data-toggle="select" name="pegawai" required>';
                      $query_pegawai = "SELECT user_id,nama_lengkap FROM user $filter ORDER BY user_id ASC";
                      $result_pegawai = $connection->query($query_pegawai);
                      if($result_pegawai->num_rows > 0) {
                        while($data_pegawai = $result_pegawai->fetch_assoc()){
                          echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
                        }
                      }else{
                        echo'<option value="">Data tidak ditemukan</option>';
                      }
                    echo'
                    </select>
                </div>
              </div>

              <div class="col-md-3">
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

              <div class="col-md-3">
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
            
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-striped datatable" style="width:100%">
                <thead class="thead-light">
                  <tr>
                    <th width="5">No</th>
                    <th>Pegawai</th>
                    <th>Tugas</th>
                    <th class="text-center">Foto</th>
                    <th>Jawaban Tugas</th>
                    <th>Tanggal</th>
                    <th class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
              </table>
            </div>';
            }else{
              hak_akses();
            }
          echo '
          </div>
        </div>
      </div>';


    if($data_role['modifikasi']=='Y'){
      echo'
      <!-- Modal ADD -->
      <div  class="modal fade modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <form class="form-add" role="form" action="#">
                    <input type="hidden" class="form-control id d-none" name="id" readonly>
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Title <span class="modal-title-name text-info"></span></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label>User</label>
                            <select name="user_id" class="form-control user"  required>';
                              $query_pegawai = "SELECT user_id,nama_lengkap FROM user ORDER BY nama_lengkap ASC";
                              $result_pegawai = $connection->query($query_pegawai);
                              while ($data_pegawai = $result_pegawai->fetch_assoc()) {
                                echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
                              }
                            echo'
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Tanggal</label>
                            <input type="text" class="form-control datepicker tanggal" name="tanggal" placeholder="'.tanggal_ind($date).'" value="'.tanggal_ind($date).'" required>
                        </div>

                        <div class="form-group">
                            <label>Tugas</label>
                            <textarea name="keterangan" class="form-control keterangan" rows="3" required="required"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn  btn-primary btn-save">Simpan</button>
                        <button type="button" class="btn  btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </form>
                </div>
            </div>
        </div>';
    }
  break;
  }
}else{
  theme_404();
}
}?>

  