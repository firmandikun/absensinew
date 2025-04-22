<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

/*$filename = "./sw-content/labeled-images/".seo_title(strtolower($data_user["nama_lengkap"]))."/";
if (file_exists($filename)) {
   
} else {
    mkdir("./sw-content/labeled-images/".seo_title(strtolower($data_user["nama_lengkap"]))."", 0777);
    //echo 'The directory '.seo_title(strtolower($data_user["nama_lengkap"])).' was successfully created.';
}*/

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="main-container">
        <div class="container">
            <div class="card mb-4">
            <div class="card-header">
                <h6 class="subtitle mb-0">
                    <div class="avatar avatar-40 bg-default-light text-default rounded mr-2">
                        <span class="material-icons">photo_library</span>
                    </div>
                    Photo Wajah
                </h6>
            </div>
            <div class="card-body">
                <p>Silahkan tambah foto 1 wajah saja</p>
                <div class="data-recognition mt-3"></div>
            </div>
        </div>
    </div>

    <!-- Modal Add  -->
        <div class="modal fade modalbox modal-add" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                        <div class="modal-body text-center">
                            <b>Patikan wajah jelas dan ditempat cahaya yang cukup</b>
                                <div class="webcam-app">
                                    <div class="md-modal md-effect-12">
                                        <div id="app-panel" class="app-panel md-content row p-0 m-0">     
                                            <div id="webcam-container" class="webcam-container col-12 p-0 m-0">
                                            
                                            <video id="webcam" autoplay playsinline width="640" height="480"></video>
                                                <canvas id="canvas"></canvas>
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
                                </div>
                        </div>
                </div>
            </div>
        </div>

        <div class="btn-floating">
            <button type="submit" class="btn btn-add btn-primary"><span class="material-icons">add_circle</span></button>
        </div>

</main>';
}?>