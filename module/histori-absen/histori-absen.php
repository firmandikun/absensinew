<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="main-container">
            <div class="container mb-4">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group float-label position-relative mb-1">
                                    <div class="bottom-right ">
                                        <a href="#" class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">calendar_month</i></a>
                                    </div>

                                    <input type="text" class="form-control mulai datepicker" placeholder="Mulai">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group float-label position-relative mb-1">
                                    <div class="bottom-right ">
                                        <a href="#" class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">calendar_month</i></a>
                                    </div>

                                    <input type="text" class="form-control selesai datepicker" placeholder="Berakhir">
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        
            <div class="container mb-4">
                <div class="col">
                    <h6 class="subtitle mb-2">Kehadiran</h6>
                </div>
                <div class="load-histori postList">
                </div>
            </div>
    </div>


        <div class="modal fade modal-absen" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                <form class="form-absen" role="form" method="post" action="#" autocomplete="off">
                    
                    <div class="modal-header">
                        <h5 class="modal-title"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" class="form-control id d-none" name="id" readonly>
                        <div class="form-group">
                            <label class="form-control-label">Keterangan</label>
                            <textarea class="form-control keterangan" name="keterangan" rows="3" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-close">Close</button>
                        <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>

    <div class="btn-floating">
        <button type="submit" class="btn btn-info btn-print"><span class="material-icons">print</span></button>
    </div>


    <div class="modal fade modal-details" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title details-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body result-details">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade modal-map" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-sm modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title title-map"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="iframe-map">
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">Close</button>
                </div>
            </div>
        </div>
    </div>

</main>';
}?>