<?php session_start();
if(!isset($_COOKIE['ADMIN_KEY']) && !isset($_COOKIE['KEY'])){
  header('location:./login');
  exit;
}
else{
require_once'../../../sw-library/sw-config.php';
include('../../../sw-library/sw-function.php');
require_once'../../login/user.php';

  if(htmlspecialchars($_GET['id']) == 1){
    echo'
    <form class="form-setting" role="form" method="post" action="javascript:void(0)" autocomplete="off">
    <div class="form-group">
        <h4>PENGATURAN WEB</h4>
    </div>

            <div class="form-group row">
            <label class="col-md-2 col-form-label form-control-label">Nama Web</label>
            <div class="col-md-6">
              <input type="text" class="form-control" name="site_name"  value="'.strip_tags($site_name).'" required>
            </div>
          </div>

          <div class="form-group row">
            <label class="col-md-2 col-form-label form-control-label">Owner</label>
            <div class="col-md-6">
              <input type="text" class="form-control" name="site_owner"  value="'.strip_tags($site_owner).'" required>
            </div>
          </div>

        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">No. Telp</label>
          <div class="col-md-6">
            <input type="number" class="form-control telp" name="site_phone"  value="'.strip_tags($site_phone).'" required>
          </div>
        </div>
        
        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">Email</label>
          <div class="col-md-6">
            <input type="email" class="form-control email" name="site_email"  value="'.strip_tags($site_email).'" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">Alamat</label>
          <div class="col-md-6">
            <textarea class="form-control alamat" rows="3" name="site_address" required>'.strip_tags($site_address).'</textarea>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">Domain/Url Web</label>
          <div class="col-md-6">
            <input type="url" class="form-control" name="site_url"  value="'.strip_tags($site_url).'" required>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">Logo Web</label>
          <div class="col-md-4">
              <div class="card" style="border:solid 1px #eee;">
                  <div class="card-body text-center">';
                  if(file_exists("../../../sw-content/$site_logo")){
                    echo'
                    <img src="../sw-content/'.$site_logo.'" class="img-responsive img-logo" height="60">';
                  }else{
                    echo'<img src="./sw-assets/img/media.png" class="img-responsive img-logo" height="60">';
                  }
                  echo'
                  </div>
                  <div class="card-footer">
                  <div class="input-group-prepend">
                    <button class="btn btn-outline-success btn-file btn-block"><i class="fa fa-refresh"></i> Change Logo Web <input type="file" class="upload logo" name="file" accept=".jpg, .jpeg, ,gif, .png">
                    </button>
                  </div>
                  </div>
              </div>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">Favicon</label>
          <div class="col-md-4">
              <div class="card border-1" style="border:solid 1px #eee;">
                  <div class="card-body text-center">';
                  if(file_exists("../../../sw-content/$site_favicon")){
                    echo'
                    <img src="../sw-content/'.$site_favicon.'" class="img-responsive img-favicon" height="60">';
                  }else{
                    echo'<img src="./sw-assets/img/media.png" class="img-responsive img-favicon" height="60">';
                  }
                  echo'
                  </div>
                  <div class="card-footer">
                      <div class="input-group-prepend">
                    <button class="btn btn-outline-success btn-file btn-block"><i class="fa fa-refresh"></i> Change Favicon <input type="file" class="upload favicon" name="file" accept=".jpg, .jpeg, ,gif, .png">
                    </button>
                  </div>
                  </div>
              </div>
          </div>
        </div>
        
      <hr>
        <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
        <button class="btn btn-secondary" type="reset"><i class="fas fa-undo"></i> Reset</button>
    </form>';
  }

  /** Pengaturan Absen */
  elseif(htmlspecialchars($_GET['id']) == 2){
    $query_timezone = "SELECT * FROM setting_absen WHERE setting_absen_id=1";
    $result_timezone = $connection->query($query_timezone);
    if($result_timezone->num_rows > 0){
      $data_timezone = $result_timezone->fetch_assoc();
    echo'
    <form class="form-setting-absensi" role="form" method="post" action="javascript:void(0)" autocomplete="off">
          <div class="form-group">
              <h4>PENGATURAN ABSENSI</h4>
          </div>

          <div class="form-group row">
            <label class="col-md-2 col-form-label form-control-label">Timezone</label>
            <div class="col-md-6">
              <select class="form-control valid" name="timezone" required="">';
                $query_time = "SELECT * FROM lain_lain WHERE tipe='timezone' ORDER BY lain_lain_id ASC";
                $result_time = $connection->query($query_time);
                while ($data = $result_time->fetch_assoc()){
                  if($data['nama'] == $data_timezone['timezone']){
                    echo'<option value="'.strip_tags($data['nama']).'" selected>'.strip_tags($data['nama']).'</option>';
                  }else{
                    echo'<option value="'.strip_tags($data['nama']).'">'.strip_tags($data['nama']).'</option>';
                  }
                }
                echo'
              </select>
            </div>
          </div>


        <div class="form-group row">
          <label class="col-md-2 col-form-label form-control-label">Tipe Absensi</label>
          <div class="col-md-2">
              <div class="card border-1" style="border:solid 1px #eee;margin-bottom:0px">
                  <div class="card-body text-center">
                    <i class="fas fa-portrait fa-3x"></i>
                  </div>
                  <div class="card-footer text-center" style="padding:10px">
                      <div class="custom-control custom-radio">';
                        if($data_timezone['tipe_absen'] == 'selfie'){
                          echo '<input name="tipe_absen" value="selfie" class="custom-control-input" id="selfie" type="radio" checked>';
                        }else{
                          echo'<input name="tipe_absen" value="selfie" class="custom-control-input" id="selfie" type="radio">';
                        }
                        echo'
                        
                        <label class="custom-control-label" for="selfie"><b>Selfie</b></label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-md-2">
              <div class="card border-1" style="border:solid 1px #eee;margin-bottom:0px">
                  <div class="card-body text-center">
                    <i class="fas fa-user-shield fa-3x"></i>
                  </div>
                  <div class="card-footer text-center" style="padding:10px">
                      <div class="custom-control custom-radio">';
                        if($data_timezone['tipe_absen'] == 'recognition'){
                          echo '<input name="tipe_absen" value="recognition" class="custom-control-input" id="recognition" type="radio" checked>';
                        }else{
                          echo'<input name="tipe_absen" value="recognition" class="custom-control-input" id="recognition" type="radio">';
                        }
                        echo'
                        
                        <label class="custom-control-label" for="recognition"><b>Recognition</b></label>
                      </div>
                  </div>
              </div>
          </div>

          <div class="col-md-2">
          <div class="card border-1" style="border:solid 1px #eee;margin-bottom:0px">
                  <div class="card-body text-center">
                    <i class="fas fa-qrcode fa-3x"></i>
                  </div>
                  <div class="card-footer text-center" style="padding:10px">
                      <div class="custom-control custom-radio">';
                      if($data_timezone['tipe_absen'] == 'qrcode'){
                        echo'<input name="tipe_absen" value="qrcode" class="custom-control-input" id="qrcode" type="radio" checked>';
                      }else{
                        echo'<input name="tipe_absen" value="qrcode" class="custom-control-input" id="qrcode" type="radio">';
                      }
                      echo'
                        
                        <label class="custom-control-label" for="qrcode"><b>QRIS</b></label>
                      </div>
                  </div>
              </div>
          </div>

      </div>

      <div class="form-group row">
        <label class="col-md-2 col-form-label form-control-label">Radius</label>
        <div class="col-md-6">
          <label class="custom-toggle custom-toggle-primary mt-2">';
            if($data_timezone['radius'] =='Y'){
              echo'<input type="checkbox" name="radius" value="Y" checked>';
            }else{
              echo'<input type="checkbox" name="radius" value="Y">';
            }
            echo'
            <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
          </label>
        </div>
      </div>

      <h3>PENGATURAN ABSENSI SCANNER DI SCREEN</h3>
      <p>Pengaturan ini berlaku untuk absensi menggunakan layar atau screen,<br>
        Melalui Alat Scanner QRCODE</p>

       <div class="form-group row">
        <label class="col-md-2 col-form-label form-control-label">Mulai Absen Masuk</label>
        <div class="col-md-6">
          <input type="text" class="form-control timepicker" name="mulai_absen_masuk" value="'.$data_timezone['mulai_absen_masuk'].'" required>
        </div>
      </div>

      <div class="form-group row">
        <label class="col-md-2 col-form-label form-control-label">Mulai Absen Pulang</label>
        <div class="col-md-6">
            <input type="text" class="form-control timepicker" name="mulai_absen_pulang" value="'.$data_timezone['mulai_absen_pulang'].'" required>
        </div>
      </div>

  </div>
        
      <hr>
        <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
        <button class="btn btn-secondary" type="reset"><i class="fas fa-undo"></i> Reset</button>
    </form>';?>

<script>
    $('.timepicker').timepicker({
      showInputs: false,
      showMeridian: false,
      use24hours: true,
      format :'HH:mm'
    });
  </script>


<?PHP }else{
        echo '<div class="text-center mt-5">FORM TIDAK DITEMUKAN</div>';
      }
  }


  /** Pengaturan Server Gmail */
  elseif(htmlspecialchars($_GET['id']) == 3){
    echo'
    <form class="form-setting-server" role="form" method="post" action="javascript:void(0)" autocomplete="off">
	<fieldset class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<h4>PENGATURAN SERVER EMAIL</h4>
			</div>

        <div class="form-group">
            <label>Email/Username</label>
            <input type="email" class="form-control" name="gmail_username" value="'.$gmail_username.'" required>
        </div>

		    <div class="form-group">
		        <label>Password SMTP</label>
		        <input type="text" class="form-control" name="gmail_password" placeholder="Password" value="'.$gmail_password.'" required>
		    </div>

		   	<div class="form-group">
		        <label>Host Mail Server</label>
		        <input type="text" class="form-control" name="gmail_host" value="'.$gmail_host.'" readonly required>
		    </div>

		   	<div class="form-group">
		        <label>Mail Server Port</label>
		        <input type="number" class="form-control col-md-4" name="gmail_port" value="'.$gmail_port.'" readonly required>
		    </div>

        <div class="form-group">
            <label class="custom-toggle custom-toggle-primary">';
              if($gmail_active =='Y'){
                echo'<input type="checkbox" name="gmail_active" value="Y" checked>';
              }else{
                echo'<input type="checkbox" name="gmail_active" value="Y">';
              }
              echo'
              <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
            </label>
            </div>


    	</div>
        <!-- End col-lg-6 -->

        <div class="col-lg-6">
            <div class="form-group">
                <h4>PENGATURAN API LOGIN GOOGLE</h4>
            </div>

            <div class="form-group">
                <label>Client ID</label>
                <input type="text" class="form-control" name="google_client_id" value="'.$google_client_id.'">
            </div>

            <div class="form-group">
                <label>Google Secret</label>
                <input type="text" class="form-control" name="google_client_secret" value="'.$google_client_secret.'">
                <label class="custom-toggle custom-toggle-primary mt-3">';
                  if($google_client_active =='Y'){
                    echo'<input type="checkbox" name="google_client_active" value="Y" checked>';
                  }else{
                    echo'<input type="checkbox" name="google_client_active" value="Y">';
                  }
                  echo'
                  <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                </label>
            </div>

        </div>
    </fieldset>

    <hr>
        <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
        <button class="btn btn-secondary" type="reset"><i class="fas fa-undo"></i> Reset</button>
  </form>';

}
  /** Pengarutan Api Wa */
  elseif(htmlspecialchars($_GET['id']) == 4){
    $query_wa = "SELECT * FROM whatsapp_api WHERE whatsapp_api_id=1";
    $result_wa = $connection->query($query_wa);
    if($result_wa->num_rows > 0){
      $data_wa = $result_wa->fetch_assoc();

    echo'
    <form class="form-setting-whatsapp" role="form" method="post" action="javascript:void(0)" autocomplete="off">
        <fieldset class="row">
          <div class="col-lg-8">
            <div class="form-group">
              <h4>PENGATURAN API WHATAPP</h4>
            </div>

            <div class="form-group">
                <label>No. WhatsApp</label>
                <input type="text" class="form-control" name="phone" value="'.strip_tags($data_wa['phone']).'">
            </div>


            <div class="form-group">
                <label>API key <b>Wablas</b> (<a href="https://wablas.com" target="_blank">Wablas</a>)</label>
                <input type="text" class="form-control" name="token" value="'.strip_tags($data_wa['token']).'">
            </div>

            <div class="form-group">
                <label>Domain Server <b>Wablas</b></label>
                <input type="text" class="form-control" name="domain_server" value="'.strip_tags($data_wa['domain_server']).'">
                <small>Examle:https://kudus.wablas.com/<span class="text-danger">api/v2/send-message</span></small>
            </div>

            <div class="form-group">
                <label class="custom-toggle custom-toggle-primary">';
                if($data_wa['active'] =='Y'){
                  echo'<input type="checkbox" name="active" value="Y" checked>';
                }else{
                  echo'<input type="checkbox" name="active" value="Y">';
                }
                echo'
                <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
                </label>
            </div>
            <div class="alert alert-info">Aktifkan salah satu Api WhatsApp supaya notifikasinya jalan</div>

            </div>
           
             
          </fieldset>

          
        <hr>
            <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
            <button class="btn btn-secondary" type="reset"><i class="fas fa-undo"></i> Reset</button>
      </form>';
    }else{
      echo'<div class="text-center">FORM TIDAK DITEMUKAN</div>';
    }
}



/** Api WhatsApp V.2 */
elseif(htmlspecialchars($_GET['id']) == 5){
  $query_wa = "SELECT * FROM whatsapp_api WHERE whatsapp_api_id=2";
  $result_wa = $connection->query($query_wa);
  if($result_wa->num_rows > 0){
    $data_wa = $result_wa->fetch_assoc();

  echo'
  <form class="form-setting-whatsapp-v2" role="form" method="post" action="javascript:void(0)" autocomplete="off">
      <fieldset class="row">
        <div class="col-lg-8">
          <div class="form-group">
            <h4>PENGATURAN API WHATAPP</h4>
            <p>Untuk Api WhatsApp V.2 Bisa menggunakan Penyedia Api WhatsApp selain Wablas</p>
          </div>

          <div class="form-group">
              <label>No. WhatsApp</label>
              <input type="text" class="form-control" name="phone" value="'.strip_tags($data_wa['phone']).'">
          </div>


          <div class="form-group">
              <label>API Key</label>
              <input type="text" class="form-control" name="token" value="'.strip_tags($data_wa['token']).'">
          </div>

          <div class="form-group">
              <label>Domain Server</label>
              <input type="text" class="form-control" name="domain_server" value="'.strip_tags($data_wa['domain_server']).'">
          </div>

          <div class="form-group">
              <label class="custom-toggle custom-toggle-primary">';
              if($data_wa['active'] =='Y'){
                echo'<input type="checkbox" name="active" value="Y" checked>';
              }else{
                echo'<input type="checkbox" name="active" value="Y">';
              }
              echo'
              <span class="custom-toggle-slider rounded-circle" data-label-off="No" data-label-on="Yes"></span>
              </label>
          </div>
          <div class="alert alert-info">Aktifkan salah satu Api WhatsApp supaya notifikasinya jalan</div>
          
          <hr>
          <div class="form-group">
            <button class="btn btn-primary btn-save" type="submit"><i class="far fa-save"></i> Simpan</button>
            <button class="btn btn-secondary" type="reset"><i class="fas fa-undo"></i> Reset</button>
          </div>


          </div>
            <!-- End col-lg-6 -->
        </fieldset>
    </form>';
  }else{
    echo'<div class="text-center">FORM TIDAK DITEMUKAN</div>';
  }
}

/** Backup  */
elseif(htmlspecialchars($_GET['id']) == 6){
echo'<form class="form-setting-absensi" role="form" method="post" action="javascript:void(0)" autocomplete="off">
      <div class="form-group">
          <h4>BACKUP APLIKASI ABSENSI</h4>
      </div>

      <div class="form-group row mb-2">
        <label class="col-md-2 col-form-label form-control-label">Backup Database</label>
        <div class="col-md-6">
          <a href="./sw-mod/pengaturan/sw-proses.php?action=backup-database" class="btn btn-primary btn-save" type="submit"><i class="fas fa-database"></i> Backup Datatabse</a>
        </div>
      </div>

</form>';

}

}