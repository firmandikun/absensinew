<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='2' AND level_id='$current_user[level]'";
  $result_role = $connection->query($query_role);
  if($result_role->num_rows > 0){
    $data_role = $result_role->fetch_assoc();

  $query_user = "SELECT user.*, posisi.posisi_nama FROM user 
               LEFT JOIN posisi ON user.posisi_id = posisi.posisi_id 
               ORDER BY user.nama_lengkap ASC";
  $result_user = $connection->query($query_user);

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
            <h6 class="h2 text-white d-inline-block mb-0">Pegawai</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Pegawai</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Pegawai/User</h3>
              <div class="float-right">';
              if($data_role['modifikasi']=='Y'){
                echo'
                <button type="button" class="btn btn-info btn-import"><i class="fas fa-file-import"></i> Import</button>
                <a href="'.$mod.'&op=add"  class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>';
                }else{
                echo'
                <button type="button" class="btn btn-info" disabled><i class="fas fa-file-import"></i> Import</button>
                <button class="btn btn-primary" disabled><i class="fas fa-plus"></i> Tambah</button>';
                }
              echo'
              </div>
            </div>';
            if($data_role['lihat']=='Y'){
              echo'
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-striped datatable-user">
                <thead class="thead-light">
                  <tr>
                    <th width="8">No</th>
                    <th>NIP</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jenis Kelamin</th>
                    <th class="text-center">Posisi/Jabatan</th>
                    <th class="text-center">Status</th> 
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
            echo'
          </div>
        </div>
      </div>
      
      <div class="modal fade modal-import data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Import Data User/Pegawai</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <form class="form-import" action="javascript:void(0);" autocomplete="of">
              <div class="modal-body">
                <div class="form-group">
                  <label>Upload file</label>
                      <input type="file" class="form-control" name="files" accept=".csv" placeholder="Import data" required>
                </div>

                <div class="alert alert-info alert-dismissible fade show" role="alert">
                  <span class="alert-text">Silahkan Import data user/pegawai dengan template dibawah ini</span>
                  <a href="../sw-content/template.csv" class="btn btn-info btn-sm">Download Template</a>
                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                  </button>
                </div>
            

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary"><i class="fas fa-file-import"></i> Import</span></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </form>
            </div>
          </div>
        </div>
        
        <div class="modal fade modal-id-card data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Export Id Card</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
           
              <div class="modal-body">

                <div class="form-group">
                  <label>Posisi</label>
                  <select class="form-control posisi" name="posisi" required>
                  <option value="">Pilih Posisi</option>';
                    $query_posisi ="SELECT * FROM posisi ORDER BY posisi_nama ASC";
                    $result_posisi = $connection->query($query_posisi);
                    while ($data_posisi = $result_posisi->fetch_assoc()) {
                      echo'<option value="'.$data_posisi['posisi_id'].'">'.$data_posisi['posisi_nama'].'</option>';
                    }
                  echo'
                  </select>
                </div>

                <div class="form-group">
                  <label>Pegawai</label>
                  <select class="form-control pegawai" required>
                    <option value="">Pilih Pegawai</option>
                  </select>
                </div>

              </div>
              <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-export-id-card"><i class="fas fa-file-export"></i> Export</span></button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>';


/* -------------- Add -------------- */
break;
case 'add':
    echo'
    <!-- Header -->
<div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
            <h6 class="h2 text-white d-inline-block mb-0">Pegawai</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="./'.$mod.'">Pegawai</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Tambah</li>
                </ol>
              </nav>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">
    <div class="card-wrapper">
    <!-- Form controls -->
    <div class="card">
      <!-- Card header -->
      <div class="card-header">
        <h3 class="mb-0">Tambah Data Pegawai/User</h3>
      </div>
      <!-- Card body -->
      <div class="card-body">';
      if($data_role['modifikasi']=='Y'){
        echo'
        <form class="form-add" role="form" method="post" action="#" autocomplete="off">
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">NIP</label>
                  <input type="text" class="form-control" name="nip" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Nama Lengkap</label>
                  <input type="text" class="form-control" name="nama_lengkap" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Tempat Lahir</label>
                  <input type="text" class="form-control" name="tempat_lahir" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Tanggal Lahir</label>
                  <input type="text" class="form-control datepicker" name="tanggal_lahir" placeholder="'.tanggal_ind($date).'" required>
                </div>


                <div class="form-group">
                  <label class="form-control-label">Jenis Kelamin</label>
                  <select class="form-control" name="jenis_kelamin" required>
                      <option value="Laki-laki">Laki-laki</option>
                      <option value="Perempuan">Perempuan</option>
                  </select>
                </div>


                <div class="form-group">
                  <label class="form-control-label">No. Telp/WhatsApp</label>
                  <input type="number" class="form-control" name="telp" required>
                </div>

                

            </div>

            <!-- Right -->
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">Email</label>
                  <input type="email" class="form-control" name="email" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Password</label>
                  <div class="input-group input-group-merge">
                    <input type="password" class="form-control password" id="password-field"  name="password" required>
                    <div class="input-group-append">
                      <span class="input-group-text"><span toggle="#password-field" class="fas fa-eye toggle-password"></span></span>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Penempatan</label>
                  <select class="form-control" name="lokasi" required>
                      <option value="">==Pilih Lokasi Kankor==</option>';
                      $query_lokasi = "SELECT lokasi_id,lokasi_nama FROM lokasi  ORDER BY lokasi_nama ASC";
                      $result_lokasi = $connection->query($query_lokasi);
                      if($result_lokasi->num_rows > 0){
                      while ($data_lokasi = $result_lokasi->fetch_assoc()){
                      echo'
                      <option value="'.$data_lokasi['lokasi_id'].'">'.strip_tags($data_lokasi['lokasi_nama']).'</option>';
                      }
                    }
                      echo'
                    </select>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Posisi</label>
                  <select class="form-control" name="posisi" required>
                      <option value="">==Pilih Posisi/Jabatan==</option>';
                      $query_posisi = "SELECT posisi_id,posisi_nama FROM posisi ORDER BY posisi_nama ASC";
                      $result_posisi = $connection->query($query_posisi);
                      if($result_posisi->num_rows > 0){
                      while ($data_posisi = $result_posisi->fetch_assoc()){
                      echo'
                      <option value="'.$data_posisi['posisi_id'].'">'.strip_tags($data_posisi['posisi_nama']).'</option>';
                      }
                    }
                      echo'
                    </select>
                </div>


                <div class="form-group">
                  <label class="form-control-label">Alamat Lengkap</label>
                  <textarea class="form-control" name="alamat" rows="3" required></textarea>
                </div>

            </div>
          </div>
            <hr>
            <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
            <a href="./'.$mod.'" class="btn btn-secondary" type="button"><i class="fas fa-undo"></i> Kembali</a>
            
        </form>';
          }else{
            hak_akses();
          }
      echo'
      </div>
    </div>
  </div>';


  /** Update Pegawai/User*/
  break;
  case 'update':
  if(!empty($_GET['id'])){
    $id     =  anti_injection(epm_decode($_GET['id'])); 
    $query_user  ="SELECT * from user WHERE user_id='$id'";
    $result_user = $connection->query($query_user);

  echo'
  <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
            <h6 class="h2 text-white d-inline-block mb-0">Pegawai</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="./'.$mod.'">Pegawai</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Update</li>
                </ol>
              </nav>
            </div>
            
          </div>
        </div>
      </div>
    </div>

    <!-- Page content -->
    <div class="container-fluid mt--6">
    <div class="card-wrapper">
    <!-- Form controls -->
    <div class="card">
      <!-- Card header -->
      <div class="card-header">
        <h3 class="mb-0">Ubah Data Pegawai/User</h3>
      </div>
      <!-- Card body -->
      <div class="card-body">';
      if($result_user->num_rows > 0){
        $data_user  = $result_user->fetch_assoc();
        if($data_role['modifikasi']=='Y'){
          echo'
        <form class="form-update" role="form" method="post" action="#" autocomplete="off">
        <input type="hidden" class="d-none" name="id" value="'.epm_encode($data_user['user_id']).'" required readonly>
          <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">NIP</label>
                  <input type="text" class="form-control" name="nip" value="'.strip_tags($data_user['nip']).'" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Nama Lengkap</label>
                  <input type="text" class="form-control" name="nama_lengkap" value="'.strip_tags($data_user['nama_lengkap']).'" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Tempat Lahir</label>
                  <input type="text" class="form-control" name="tempat_lahir" value="'.strip_tags($data_user['tempat_lahir']).'" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Tanggal Lahir</label>
                  <input type="text" class="form-control datepicker" name="tanggal_lahir" value="'.tanggal_ind($data_user['tanggal_lahir']).'" required>
                </div>


                <div class="form-group">
                  <label class="form-control-label">Jenis Kelamin</label>
                      <select class="form-control" name="jenis_kelamin" required>';
                          if($data_user['jenis_kelamin'] =='Laki-laki'){
                              echo'<option value="Laki-laki" selected>Laki-laki</option>';
                          }else{
                              echo'<option value="Laki-laki">Laki-laki</option>';
                          }
                          if($data_user['jenis_kelamin'] =='Perempuan'){
                              echo'<option value="Perempuan" selected>Perempuan</option>';
                          }else{
                              echo'<option value="Perempuan">Perempuan</option>';
                          }
                          echo'
                      </select>
                </div>


                <div class="form-group">
                  <label class="form-control-label">No. Telp/WhatsApp</label>
                  <input type="number" class="form-control" name="telp" value="'.strip_tags($data_user['telp']).'" required>
                </div>

            </div>

            <!-- Right -->
            <div class="col-md-6">
                <div class="form-group">
                  <label class="form-control-label">Email</label>
                  <input type="email" class="form-control" name="email" value="'.strip_tags($data_user['email']).'" required>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Penempatan</label>
                  <select class="form-control" name="lokasi" required>
                      <option value="">==Pilih Lokasi Kankor==</option>';
                      $query_lokasi = "SELECT lokasi_id,lokasi_nama FROM lokasi  ORDER BY lokasi_nama ASC";
                      $result_lokasi = $connection->query($query_lokasi);
                      if($result_lokasi->num_rows > 0){
                      while ($data_lokasi = $result_lokasi->fetch_assoc()){
                        if($data_user['lokasi_id'] == $data_lokasi['lokasi_id']){
                          echo'
                          <option value="'.$data_lokasi['lokasi_id'].'" selected>'.strip_tags($data_lokasi['lokasi_nama']).'</option>';
                        }else{
                          echo'
                          <option value="'.$data_lokasi['lokasi_id'].'">'.strip_tags($data_lokasi['lokasi_nama']).'</option>';
                        }
                      }
                    }
                      echo'
                    </select>
                </div>

                <div class="form-group">
                  <label class="form-control-label">Posisi</label>
                  <select class="form-control" name="posisi" required>
                      <option value="">==Pilih Posisi/Jabatan==</option>';
                      $query_posisi = "SELECT posisi_id,posisi_nama FROM posisi ORDER BY posisi_nama ASC";
                      $result_posisi = $connection->query($query_posisi);
                      if($result_posisi->num_rows > 0){
                      while ($data_posisi = $result_posisi->fetch_assoc()){
                        if($data_user['posisi_id']== $data_posisi['posisi_id']){
                          echo'
                          <option value="'.$data_posisi['posisi_id'].'" selected>'.strip_tags($data_posisi['posisi_nama']).'</option>';
                        }else{
                          echo'
                          <option value="'.$data_posisi['posisi_id'].'">'.strip_tags($data_posisi['posisi_nama']).'</option>';
                        }
                      }
                    }
                      echo'
                    </select>
                </div>


                <div class="form-group">
                  <label class="form-control-label">Alamat Lengkap</label>
                  <textarea class="form-control" name="alamat" rows="3" required>'.strip_tags($data_user['alamat']).'</textarea>
                </div>

            </div>
          </div>
            <hr>
            <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
            <a href="./'.$mod.'" class="btn btn-secondary" type="button"><i class="fas fa-undo"></i> Kembali</a>
        </form>';
        }else{
          hak_akses();
        }
      }else{
        theme_404();
      }
      echo'
      </div>
    </div>
  </div>';
}

  /** Update Pegawai/User*/
  break;
  case 'profile':
  if(!empty($_GET['id'])){
    $id     =  anti_injection(epm_decode($_GET['id'])); 
    /*$query_user  ="SELECT * from user WHERE user_id='$id'";*/
    $query_user ="SELECT user.*, posisi.posisi_nama, lokasi.lokasi_nama FROM user 
    LEFT JOIN posisi
    ON user.posisi_id = posisi.posisi_id
    LEFT JOIN lokasi ON user.lokasi_id=lokasi.lokasi_id WHERE user.user_id='$id'";
    $result_user = $connection->query($query_user);

  echo'
  <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
            <h6 class="h2 text-white d-inline-block mb-0">Pegawai</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="./'.$mod.'">Pegawai</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Profil</li>
                </ol>
              </nav>
            </div>
            
          </div>
        </div>
      </div>
    </div>';

    if($result_user->num_rows > 0){
      $data_user = $result_user->fetch_assoc();
      echo'
    <!-- Page content -->
    <div class="container-fluid mt--6">
      <div class="row">
        <div class="col-xl-4 order-xl-2">
          <div class="card card-profile">
              <img src="./sw-assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
            <div class="row justify-content-center">
              <div class="col-lg-3 order-lg-2">
                <div class="card-profile-image">
                <input type="hidden" class="id" value="'.$data_user['user_id'].'">
                <input type="file" class="upload foto" name="foto" accept=".jpg, .jpeg, ,gif, .png" capture="camera">';
                  if($data_user['avatar']==''){
                  echo'
                  <img class="rounded-circle img-140 view-image" src="./sw-assets/avatar/avatar.jpg">';
                  }else{
                    if(file_exists('../sw-content/avatar/'.$data_user['avatar'].'')){
                    echo'
                      <img class="rounded-circle img-140 view-image" src="../sw-content/avatar/'.$data_user['avatar'].'">';
                    }else{
                      echo'
                      <img class="rounded-circle img-140 view-image" src="./sw-assets/avatar/avatar.jpg">';
                    }
                  }
                  echo'
                  <span class="button">
                    <i class="fas fa-camera"></i>
                  </span>
              
                </div>
              </div>
            </div>
          
            <div class="card-body pt-0 mt-6">
              <div class="text-center">
                <h5 class="h3">
                '.strip_tags($data_user['nama_lengkap']).'
                </h5>
                <div class="h5 font-weight-300">
                  <i class="ni location_pin mr-2"></i>'.strip_tags($data_user['nip']).'
                </div>

                <div class="h5 font-weight-300">';
                  if($data_user['status']=='Offline'){
                    echo'<span class="badge badge-danger">Offline</span>';
                  }else{
                    echo'<span class="badge badge-info">Online</span>';
                  }
                echo'
                </div>
              </div>
                
                <div class="mt-3">
                  <ul class="list-group list-group-flush">
                    <li class="list-group-item">User Sejak: '.tgl_indo($data_user['tanggal_registrasi']).'</li>
                    <li class="list-group-item">Terakhir Login: '.tgl_indo($data_user['tanggal_login']).'</li>
                    <li class="list-group-item">Ip: '.$data_user['ip'].'</li>
                    <li class="list-group-item">Browser: '.$data_user['browser'].'</li>
                  </ul>
                </div>

            </div>
          </div>
         
     
        </div>
        <div class="col-xl-8 order-xl-1">
          
          <div class="card">
            <div class="card-header">
              <div class="row align-items-center">
                <div class="col-8">
                  <h3 class="mb-0">Profil</h3>
                </div>
                <div class="col-4 text-right">
                  <a href="user&op=update&id='.epm_encode($data_user['user_id']).'" class="btn btn-sm btn-primary">Settings</a>
                </div>
              </div>
            </div>
            <div class="card-body">
              <form>
                <h6 class="heading-small text-muted mb-4">Informasi Profil</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Nama Lengkap</label>
                        <p>'.strip_tags($data_user['nama_lengkap']).'</p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Tempat Lahir</label>
                        <p>'.strip_tags($data_user['tempat_lahir']).'</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Tanggal  Lahir</label>
                        <p>'.tanggal_ind($data_user['tanggal_lahir']).'</p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Jenis Kelamin</label>
                        <p>'.strip_tags($data_user['jenis_kelamin']).'</p>
                      </div>
                    </div>
                  </div>
                </div>
                <hr class="my-4" />
                <!-- Address -->
                <h6 class="heading-small text-muted mb-4">Kontak</h6>
                <div class="pl-lg-4">
                  <div class="row">
                    <div class="col-md-12">
                      <div class="form-group">
                        <label class="form-control-label">Alamat rumah</label>
                        <p>'.strip_tags($data_user['alamat']).'</p>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">Email</label>
                        <p>'.strip_tags($data_user['email']).'</p>
                      </div>
                    </div>
                    <div class="col-lg-6">
                      <div class="form-group">
                        <label class="form-control-label">No. Telp</label>
                        <p>'.strip_tags($data_user['telp']).'</p>
                      </div>
                    </div>

                  </div>
                </div>
                <hr class="my-4" />
                <!-- Description -->
                <h6 class="heading-small text-muted mb-4">Pekerjaan</h6>
                <div class="pl-lg-4">
                  <div class="row">
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label">Penempatan</label>
                          <p>'.strip_tags($data_user['lokasi_nama']).'</p>
                        </div>
                      </div>
                      <div class="col-lg-6">
                        <div class="form-group">
                          <label class="form-control-label">Posisi/Jabatan</label>
                          <p>'.strip_tags($data_user['posisi_nama']).'</p>
                        </div>
                      </div>
                  </div>
                </div>
              </form>
            </div>
      
      </div>
    </div>
  </div>';
}else{
    echo' <div class="container-fluid mt--6">
    <!-- Table -->
    <div class="row">
      <div class="col">
        <div class="card pb-6 pt-6">';
          theme_404();
        echo'</div>
          </div>
    </div>';
  }
}
  break;
}

}else{
  /** Modul tidak ditemukan */
}
}?>

  