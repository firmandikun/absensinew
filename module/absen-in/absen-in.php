<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:./404');
    echo'404';
}else{
    $query_lokasi  ="SELECT lokasi_id,lokasi_latitude,lokasi_longitude,lokasi_radius FROM lokasi WHERE lokasi_id='$data_user[lokasi_id]'";
    $result_lokasi = $connection->query($query_lokasi);
    
if($result_absen > 0){
    header('location:./');
}else{
echo'
    <!-- Begin page content -->
    <main class="flex-shrink-0 main">
        <div class="container mb-4">
                <div class="card">
                    <div class="card-body text-center">
                    <div class="row header-webcame mb-3">
                        <div class="col text-left">
                            <span class="title font-weight-light"> Selamat '.$salam.'</span>
                            <p class="subtitle text-nowrap text-success" style="width: 8rem;">'.ucfirst($data_user['nama_lengkap']).'</p>
                        </div>
                        <div class="col-auto">
                            <span class="title text-right">'.tgl_ind($date).' </span>
                            <p class="text-right text-success"><span class="clock"></span></p>
                        </div>
                    </div>
                        <hr>';
                            if($data_setting_absen['tipe_absen'] =='qrcode'){
                            echo'
                                <p>Arahkan Kamera ke QRCODE, Hanya bisa Absen dilokasi</p>
                                <div class="webcame text-center">
                                    <span class="latitude d-none"></span>
                                    <div id="reader"></div>
                                    <audio id="my_audio" class="d-none">
                                        <source src="template/vendor/html5-qrcode/audio/beep.mp3" type="audio/mpeg">
                                    </audio>
                                </div>';
                            }else{
                            /** Absen dengan foto  */
                            echo'
                                <div class="webcam-app">
                                    <div class="loading-webcame d-none"></div>
                                    <div class="result-user d-none"></div>
                        
                                        <div class="md-modal md-effect-12">
                                            <div id="app-panel" class="app-panel md-content row p-0 m-0">     
                                                <div id="webcam-container" class="webcam-container col-12 p-0 m-0">
                                                
                                                <video id="webcam" autoplay playsinline width="640" height="480"></video>
                                                    <canvas id="canvas"></canvas>
                                                    <canvas id="reflay" class="overlay"></canvas>
                                                </div>

                                                <a href="javascript:void(0);" class="cameraFlip" title="Flip"><span class="material-icons">cameraswitch</span></a>

                                                <div id="cameraControls" class="cameraControls">

                                                    <a href="javascript:void(0);" class="resume-camera d-none" title="Resume">
                                                        <span class="material-icons">close</span>
                                                    </a>

                                                    <a href="javascript:void(0);" class="take-photo" title="Take Photo"><div class="material-icons"><span class="material-icons">camera</span></div></a>
                                                </div>
                                            </div>        
                                        </div>
                                </div>';
                            }
                    echo'
                    </div>
                </div>
            </div>
        
    </main>';
    }
}?>