<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='8' AND level_id='$current_user[level]'";
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
            <h6 class="h2 text-white d-inline-block mb-0">Izin</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Izin</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Izin</h3>
              <div class="float-right">';
              if($data_role['modifikasi']=='Y'){
                echo'
                <button class="btn btn-primary btn-add"><i class="fas fa-plus"></i> Tambah</button>';
                echo'<button class="btn btn-primary btn-create-lainlain"><i class="fas fa-plus"></i> Tambah Data Lain-lain</button>';

                
              }else{
                echo'
                <button class="btn btn-primary" disabled><i class="fas fa-plus"></i> Tambah</button>';
                echo'<button class="btn btn-primary" disabled><i class="fas fa-plus"></i> Tambah Data Lain-lain</button>';
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
                    <th>Tanggal Izin</th>
                    <th>Jenis Izin</th>
                    <th>Keterangan</th>
                    <th class="text-center">Foto</th>
                    <th>Tanggal Terbit</th>
                    <th class="text-center">Status</th>
                    <th class="text-center"  width="6">Aksi</th>
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
<div class="modal fade modal-create-lainlain" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form class="form-create-lainlain" action="proses_tambah_lainlain.php" method="POST" autocomplete="off">
        <div class="modal-header">
          <h5 class="modal-title" id="modalCreateLainlainTitle">Tambah Data Lain-lain</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label for="nama">Nama <span class="text-danger">*</span></label>
            <input type="text" name="nama" class="form-control" id="nama" placeholder="Masukkan nama" required>
          </div>

          <div class="form-group">
            <label for="tipe">Tipe <span class="text-danger">*</span></label>
            <input type="text" name="tipe" class="form-control" id="tipe" placeholder="Masukkan tipe (contoh: izin, cuti, sakit)" required>
          </div>

        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Simpan</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
</div>

      

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
                          $query_pegawai = "SELECT user_id,nama_lengkap FROM user ORDER BY nama_lengkap ASC";
                          $result_pegawai = $connection->query($query_pegawai);
                          while ($data_pegawai = $result_pegawai->fetch_assoc()) {
                            echo'<option value="'.$data_pegawai['user_id'].'">'.strip_tags($data_pegawai['nama_lengkap']).'</option>';
                          }
                        echo'
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Mulai Izin</label>
                        <input type="text" class="form-control datepicker tanggal" name="tanggal" value="" placeholder="'.tanggal_ind($date).'" required>
                    </div>

                    <div class="form-group">
                        <label>Sampai Tanggal</label>
                        <input type="text" class="form-control datepicker tanggal-selesai" name="tanggal_selesai" value="" placeholder="'.tanggal_ind($date).'" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Status izin</label>
                        <select class="form-control jenis" name="jenis" required>';
                            $query_status_izin="SELECT * FROM lain_lain WHERE tipe='izin'";
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
                        <label>Keterangan</label>
                        <textarea class="form-control keterangan" name="keterangan" rows="3" required></textarea>
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

  