<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{
echo'
<main class="flex-shrink-0 main has-footer">  
    
<!-- page content start -->
        <div class="container-fluid px-0">
            <div class="card overflow-hidden">
                <div class="card-body p-0 h-150">
                    <div class="background">
                        <img src="template/img/image10.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid top-70 text-center mb-4">
            <div class="avatar avatar-140 rounded-circle mx-auto shadow">
                <div class="background avatar-upload">';
                    if($data_user['avatar'] == NULL OR $data_user['avatar']=='avatar.jpg'){
                        echo'<img src="./sw-content/avatar/avatar.jpg" id="output" class="imaged w100 rounded" height="100">';
                        }else{
                         if(file_exists('./sw-content/avatar/'.$data_user['avatar'].'')){
                            echo'<img src="data:image/gif;base64,'.base64_encode(file_get_contents('./sw-content/avatar/'.$data_user['avatar'].'')).'" id="output" class="imaged w100 rounded" height="100">';
                         }else{
                            echo'<img src="./sw-content/avatar/avatar.jpg" id="output" class="imaged w100 rounded" height="100">';
                         }
                    }
                 echo'
                </div>
                <span class="button">
                    <input type="file" class="upload" name="file" id="avatar" accept=".jpg, .jpeg, ,gif, .png" style="opacity:0">
                    <span class="material-icons">photo_camera</span>
                </span>
            </div>
        </div>

        <div class="container mb-4 text-center text-white">
            <h6 class="mb-1">'.strip_tags($data_user['nama_lengkap']).'</h6>
            <p class="mb-1">'.strip_tags($data_user['email']).'</p>
            <p>NIP: '.strip_tags($data_user['nip']).'</p>
        </div>

    <div class="main-container">
        <div class="container">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">Pengaturan</h6>
                    </div>
                    <div class="card-body px-0 pt-0">
                        <div class="list-group list-group-flush border-top border-color">
                            <a href="profile" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">person</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Profile</h6>
                                        <p class="text-secondary">Ubah/setting profil</p>
                                    </div>
                                </div>
                            </a>

                           

                            <a href="recognition" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">face_5</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Daftarkan Wajah</h6>
                                        <p class="text-secondary">Verifikasi Absen Selfie</p>
                                    </div>
                                </div>
                            </a>

                            <a href="keamanan" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">lock_open</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Keamaan</h6>
                                        <p class="text-secondary">Ubah Email & Password</p>
                                    </div>
                                </div>
                            </a>
                            <a href="./jam-kerja" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">pending_actions</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Jam Kerja</h6>
                                        <p class="text-secondary">Pengaturan jam kerja</p>
                                    </div>
                                </div>
                            </a>
                            <a href="javascript:void(0);" class="list-group-item list-group-item-action border-color colorsettings">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-default-light text-default rounded">
                                            <span class="material-icons">palette</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Tampilan</h6>
                                        <p class="text-secondary">Ubah tampilan warna</p>
                                    </div>
                                </div>
                            </a>
                            
                            <a href="./logout" class="list-group-item list-group-item-action border-color">
                                <div class="row">
                                    <div class="col-auto">
                                        <div class="avatar avatar-50 bg-danger-light text-danger rounded">
                                            <span class="material-icons">power_settings_new</span>
                                        </div>
                                    </div>
                                    <div class="col align-self-center pl-0">
                                        <h6 class="mb-1">Logout</h6>
                                        <p class="text-secondary">Keluar dari aplikasi</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</main>';


}?>