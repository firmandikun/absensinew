<?PHP
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
  $query_role ="SELECT lihat,modifikasi,hapus FROM role WHERE modul_id='4' AND level_id='$current_user[level]'";
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
            <h6 class="h2 text-white d-inline-block mb-0">Jam Kerja</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Jam Kerja</li>
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
              <h3 class="mt-2 mb-0 text-left float-left">Jam Kerja</h3>
              <div class="float-right">';
              if($data_role['modifikasi']=='Y'){
                echo'
                <a href="'.$mod.'&op=add"  class="btn btn-primary"><i class="fas fa-plus"></i> Tambah</a>';
              }else{
                echo'<button class="btn btn-primary btn-error"><i class="fas fa-plus"></i> Tambah</button>';
              }
              echo'
              </div>
            </div>';
            if($data_role['modifikasi']=='Y'){
        echo'
            <div class="table-responsive">
              <table class="table align-items-center table-flush table-striped datatable">
                <thead class="thead-light">
                  <tr>
                    <th width="5">No</th>
                    <th>Nama</th>
                    <th>Hari Kerja</th>
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

      <!-- Modal Details-->
      <div class="modal fade modal-details data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Jam Kerja <span class="modal-title-name text-info"></span></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
              <div class="modal-body">
                <div class="load-data-jam"></div>
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
            <h6 class="h2 text-white d-inline-block mb-0">Jam Kerja</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="./'.$mod.'">Jam Kerja</a></li>
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
        <h3 class="mb-0">Tambah Jam kerja</h3>
      </div>
      <!-- Card body -->
      <div class="card-body">';
      if($data_role['modifikasi']=='Y'){
        echo'
        <form class="form-add" role="form" method="post" action="#" autocomplete="off">
        <div class="form-group row">
          <label for="example-text-input" class="col-md-2 col-form-label form-control-label">Nama</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="nama" value="" required>
          </div>
        </div>
        <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th scope="col">Hari</th>
              <th scope="col">Masuk</th>
              <th scope="col">Batas Telat</th>
              <th scope="col">Pulang</th>
              <th scope="col">Aktif</th>
            </tr>
          </thead>
          <tbody class="list">';
          $nama_hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
          $i = 0;
          foreach($nama_hari as $values){$i++;
            echo'
            <tr>
              <td scope="row">
                <input class="d-none" type="input" style="display:none" name="item[]" value="'.anti_injection($values).'" required readonly>
                '.$values.'
              </td>
              <td class="budget">
                <input class="form-control timepicker" type="text" name="jam_masuk[]" value="7:30:00" placeholder="Masuk">
              </td>
              <td>
                <input class="form-control timepicker" type="text" name="jam_telat[]" placeholder="Batas Telat">
              </td>
              <td>
                <input class="form-control timepicker" type="text" name="jam_pulang[]" placeholder="Pulang">
              </td>
              <td>
                  <div class="selectdiv">
                  <label>
                      <select class="form-control" name="active[]">
                          <option value="Y">Aktif</option>
                          <option value="N">Tidak Aktif</option>
                      </select>
                  </label>
                </div>
              </td>
            </tr>';
          }
          echo' 
          </tbody>
        </table>
        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="far fa-clock"></i></span>
        <span class="alert-text">Silahkan aktifkan sesuai hari dan jam kerja</span>
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


  /** Update Lokasi/User*/
  break;
  case 'update':
  if(!empty($_GET['id'])){
    $id     =  anti_injection(epm_decode($_GET['id'])); 
    $query_master  ="SELECT * from jam_kerja_master WHERE jam_kerja_master_id='$id'";
    $result_master = $connection->query($query_master);
    if($result_master->num_rows > 0){
      $data_master  = $result_master->fetch_assoc();
  echo'
  <div class="header bg-primary pb-6">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
            <h6 class="h2 text-white d-inline-block mb-0">Lokasi</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item"><a href="./"><i class="fas fa-home"></i> Dashboard</a></li>
                  <li class="breadcrumb-item"><a href="./'.$mod.'">Jam Kerja</a></li>
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
        <h3 class="mb-0">Ubah Jam Kerja <span class="text-primary">'.$data_master['nama'].'</span></h3>
      </div>
      <!-- Card body -->
      <div class="card-body">';
      if($data_role['modifikasi']=='Y'){
        echo'
        <form class="form-update" role="form" method="post" action="#" autocomplete="off">
        <div class="form-group row">
          <label for="example-text-input" class="col-md-2 col-form-label form-control-label">Nama</label>
          <div class="col-md-10">
            <input class="form-control" type="text" name="nama" value="'.strip_tags($data_master['nama']).'" required>
            <input class="d-none" type="hidden" name="master_id" value="'.epm_encode($data_master['jam_kerja_master_id']).'" required>
          </div>
        </div>
        <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th scope="col">Hari</th>
              <th scope="col">Masuk</th>
              <th scope="col">Batas Telat</th>
              <th scope="col">Pulang</th>
              <th scope="col">Aktif</th>
            </tr>
          </thead>
          <tbody class="list">';
          $nama_hari = ["Minggu","Senin","Selasa","Rabu","Kamis","Jumat","Sabtu"];
          $i = 0;
          foreach($nama_hari as $values){$i++;
            $hari = anti_injection($values);
            $query_jam_kerja  ="SELECT * from jam_kerja WHERE jam_kerja_master_id='$data_master[jam_kerja_master_id]' AND hari='$hari'";
            $result_jam_kerja = $connection->query($query_jam_kerja);
            $data_jam_kerja  = $result_jam_kerja->fetch_assoc();
            
            if($data_jam_kerja['active'] == 'Y'){
              $active ='<label class="custom-toggle" style="display:inline-block">
              <input type="checkbox" class="btn-active" name="active[]" value="Y" checked>
              <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>';
            }else{
              $active = '<label class="custom-toggle" style="display:inline-block">
              <input type="checkbox" class="btn-active" name="active[]" value="Y">
              <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>';
            }
            echo'
            <tr>
              <td scope="row">
                <input class="d-none" type="hidden" name="id[]" value="'.htmlentities($data_jam_kerja['jam_kerja_id']).'" readonly required>
                <input class="d-none" type="input" style="display:none" name="item[]" value="'.anti_injection($values).'" required readonly>
                '.$values.'
              </td>
              <td class="budget">
                <input class="form-control timepicker" type="text" name="jam_masuk[]" value="'.$data_jam_kerja['jam_masuk'].'" placeholder="Masuk">
              </td>
              <td>
                <input class="form-control timepicker" type="text" name="jam_telat[]" value="'.$data_jam_kerja['jam_telat'].'" placeholder="Batas Telat">
              </td>
              <td>
                <input class="form-control timepicker" type="text" name="jam_pulang[]" value="'.$data_jam_kerja['jam_pulang'].'" placeholder="Pulang">
              </td>
              <td>
                <div class="selectdiv">
                  <label>
                      <select class="form-control" name="active[]">';
                      if($data_jam_kerja['active'] == 'Y'){
                        echo'<option value="Y" selected>Aktif</option>';
                      }else{
                        echo'<option value="Y">Aktif</option>';
                      }
                      if($data_jam_kerja['active'] == 'N'){
                        echo'<option value="N" selected>Tidak Aktif</option>';
                      }else{
                        echo'<option value="N">Tidak Aktif</option>';
                      }
                      echo'
                      </select>
                  </label>
                </div>
            </td>
            </tr>';
          }
          echo' 
          </tbody>
        </table>
        <div class="alert alert-secondary alert-dismissible fade show" role="alert">
        <span class="alert-icon"><i class="far fa-clock"></i></span>
        <span class="alert-text">Silahkan aktifkan sesuai hari dan jam kerjanya</span>
        </button>
      </div
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
    }else{
      theme_404();
    }
}
  break;
  }
}else{
  theme_404();
}
}?>

  