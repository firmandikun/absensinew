<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="main-container">
    <div class="container mb-4">
                <div class="card shadow-default">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group float-label position-relative mb-1">
                                    <div class="bottom-right ">
                                        <span class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">calendar_month</i></span>
                                    </div>
                                    <input type="text" class="form-control mulai search datepicker" placeholder="Mulai">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group float-label position-relative mb-1">
                                    <div class="bottom-right ">
                                        <span class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">calendar_month</i></span>
                                    </div>
                                    <input type="text" class="form-control selesai search datepicker" placeholder="Berakhir">
                                </div>

                            </div>


                        </div>
                    </div>
                </div>
            </div>

        <div class="container mb-4">
            <div class="col">
                <h6 class="subtitle mb-2">Kunjungan</h6>
            </div>

            <div class="load-kunjungan postList"></div>
        </div>
    </div>

    <!-- Modal Add  -->
        <div class="modal fade modalbox modal-add" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-md modal-dialog-centered" role="document">
                <div class="modal-content">
                <form class="form-add" role="form" method="post" action="javascript:;" enctype="multipart/form-data" autocomplete="off">
                <input type="hidden" class="d-none id" name="id" value="" readonly required>
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="form-group">
                            <label class="form-control-label">Lokasi</label>
                            <input type="text" class="form-control lokasi" name="lokasi" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Keterangan</label>
                            <textarea class="form-control keterangan" name="keterangan" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                          <div class="file-upload">
                              <div class="image-upload-wrap">
                                  <div class="drag-text btn-kamera">
                                    <i class="lni lni-cloud-upload"></i>
                                    <h3>Ambil Foto</h3>
                                  </div>
                              </div>
                                <div class="file-upload-content">
                                 <input type="hidden" class="d-none foto" readonly>
                                  <img class="file-upload-image" src="template/img/sw-small.jpg" alt="Upload" height="150">
                                    <div class="image-title-wrap">
                                      <button type="button" onclick="removeUpload()" class="btn btn-danger btn-sm"><i class="fas fa-undo"></i> Ubah<span class="image-title"></span></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                            <button type="button" class="btn btn-secondary btn-close">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>


        <!-- Modal Kamera -->
        <div class="modal fade modalbox modal-kamera" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                <div class="modal-content modal-lg">
                    <div class="modal-header">
                        <button type="button" class="close btn-close-kamera" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                        <div class="modal-body text-center">
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
            <button type="submit" class="btn btn-info btn-print"><span class="material-icons">print</span></button>
        </div>

</main>';
}?>