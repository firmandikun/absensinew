<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='9' AND level_id='$current_user[level]'";
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
            <h6 class="h2 text-white d-inline-block mb-0">Cuti</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Cuti</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Cuti</h3>
              <div class="float-right">';
              if($data_role['modifikasi']=='Y'){
                echo'
                <button class="btn btn-primary btn-add"><i class="fas fa-plus"></i> Tambah</button>';
              }else{
                echo'
                <button class="btn btn-primary" disabled><i class="fas fa-plus"></i> Tambah</button>';
              }
              echo'
              </div>
            </div>';

        echo'
            <div class="table-responsive">';
            if($data_role['lihat']=='Y'){
              echo'
              <table class="table align-items-center table-striped datatable">
                <thead class="thead-light">
                  <tr>
                    <th class="text-center" width="5">No</th>
                    <th>Nama</th>
                    <th>Tanggal Cuti</th>
                    <th>Alasan Cuti</th>
                    <th class="text-center">Jumlah Cuti</th>
                    <th class="text-center">Sisa Cuti</th>
                    <th>Keterangan</th>
                    <th class="text-center">Foto</th>
                    <th>Tanggal Terbit</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" width="6">Aksi</th>
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


      if($data_role['modifikasi']=='Y'){
      echo'

      <!-- Modal Add -->
      <div  class="modal fade modal-add" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <form class="form-add" role="form" action="#" autocomplete="off">
                    <input type="hidden" class="form-control id d-none" name="id" readonly>
                    <div class="modal-header">
                        <h5 class="modal-title">Modal Title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <div class="modal-body" style="overflow-y: scroll; max-height:450px;">
                    
                      <div class="form-group">
                          <label>User</label>
                          <select name="user_id" class="form-control user"  required>';
                            $is_atasan = (isset($_SESSION['level']) && $_SESSION['level'] == 3) || (isset($current_user['level']) && $current_user['level'] == 3);
                            $admin_id = isset($_SESSION['admin_id']) ? $_SESSION['admin_id'] : (isset($current_user['admin_id']) ? $current_user['admin_id'] : null);
                            if($is_atasan && $admin_id){
                              $query_pegawai = "SELECT user_id,nama_lengkap FROM user WHERE atasan_id = '".$admin_id."' ORDER BY nama_lengkap ASC";
                            }else{
                              $query_pegawai = "SELECT user_id,nama_lengkap FROM user ORDER BY nama_lengkap ASC";
                            }
                            $result_pegawai = $connection->query($query_pegawai);
                            while ($data_pegawai = $result_pegawai->fetch_assoc()) {
                              echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
                            }
                          echo'
                          </select>
                      </div>

                      <div class="form-group">
                          <label>Atasan</label>
                          <select name="atasan_id" class="form-control" required>
                            <option value="">==Pilih Atasan==</option>';
                            $query_atasan = "SELECT admin_id, fullname FROM admin WHERE level = 3";
                            $result_atasan = $connection->query($query_atasan);
                            while ($data_atasan = $result_atasan->fetch_assoc()) {
                              echo '<option value="'.$data_atasan['admin_id'].'">'.strip_tags($data_atasan['fullname']).'</option>';
                            }
                          echo'
                          </select>
                      </div>

                      <div class="form-group">
                          <label class="form-control-label">Jenis Cuti</label>
                          <select class="form-control jenis" name="jenis" required>
                              <option value="">...</option>';
                              $query_status_izin="SELECT * FROM lain_lain WHERE tipe='cuti'";
                              $result_status_izin= $connection->query($query_status_izin);
                              if ($result_status_izin->num_rows > 0) {
                              while ($data_status = $result_status_izin->fetch_assoc()){
                                  echo'<option value="'.strip_tags($data_status['nama']).'">'.strip_tags($data_status['nama']).'</option>';
                              }
                              }else{
                                  echo'<option value="0">Data tidak ditemukan</option>';
                              }
                      echo'
                          </select>
                      </div>


                      <div class="form-group">
                          <label>Alasan Cuti</label>
                          <textarea class="form-control keterangan" name="keterangan" rows="3" required></textarea>
                      </div>

                    <div class="form-group">
                        <label class="form-control-label">Periode Cuti</label>
                          <div class="form-row">
                              <div class="col">
                                  
                                  <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text">
                                              <i class="ni ni-calendar-grid-58"></i>
                                          </span>
                                      </div>
                                      <input type="text" class="form-control tanggal-mulai datepicker" name="tanggal_mulai" value="'.tanggal_ind($date).'" required>
                                      
                                  </div>

                              </div>
                              <div class="col">
                                  <div class="input-group mb-3">
                                      <div class="input-group-prepend">
                                          <span class="input-group-text">
                                              <i class="ni ni-calendar-grid-58"></i>
                                          </span>
                                      </div>
                                      <input type="text" class="form-control tanggal-selesai datepicker" name="tanggal_selesai" value="'.tanggal_ind($date).'" required>
                                  </div>
                              </div>
                          </div>
                      </div>

                      <div class="form-group">
                          <label class="form-control-label">Jumlah Cuti</label>
                          <input type="number" class="form-control jumlah" name="jumlah" required>
                      </div>

                        <div class="form-group">
                        <label>Upload Foto</label>
                        <div class="file-upload">
                            <div class="image-upload-wrap">
                            <input class="file-upload-input fileInput" type="file" name="files" onchange="readURL(this);" accept="image/*">
                                <div class="drag-text">
                                <i class="lni lni-cloud-upload"></i>
                                <h3>Drag and drop files here</h3>
                                </div>
                            </div>
                            <div class="file-upload-content">
                                <img class="file-upload-image" src="sw-assets/img/media.png" alt="Upload" height="150">
                                <div class="image-title-wrap">
                                    <button type="button" onclick="removeUpload()" class="btn btn-danger btn-sm">Ubah<span class="image-title"></span></button>
                                </div>
                            </div>
                        </div>
                        <p class="text-danger">Format foto harus JPG, JPEG, Kosongkan saja jika tidak ingin Upload foto</p>
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

  