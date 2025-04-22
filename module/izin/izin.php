<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

$notifikasi ="UPDATE notifikasi SET status='Y' WHERE link='izin' AND tipe='2' AND user_id='$data_user[user_id]'"; 
$connection->query($notifikasi);

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
                <h6 class="subtitle mb-2">Izin</h6>
            </div>

            <div class="load-izin postList"></div>
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
                            <label class="form-control-label">Mulai izin</label>
                            <input type="text" class="form-control tanggal-mulai datepicker" name="tanggal_mulai" value="'.tanggal_ind($date).'" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Selesai izin</label>
                            <input type="text" class="form-control tanggal-selesai datepicker" name="tanggal_selesai" required>
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
                            <label class="form-control-label">Keterangan</label>
                            <textarea class="form-control keterangan" name="keterangan" rows="3" required></textarea>
                        </div>

                        <div class="form-group">
                          <label>Upload bukti/surat izin</label>
                          <div class="file-upload">
                              <div class="image-upload-wrap">
                                <input class="file-upload-input fileInput files" type="file" name="files" onchange="readURL(this);" accept="image/*" required>
                                  <div class="drag-text">
                                    <i class="lni lni-cloud-upload"></i>
                                    <h3>Drag and drop files here</h3>
                                  </div>
                              </div>
                                <div class="file-upload-content">
                                  <img class="file-upload-image" src="template/img/sw-small.jpg" alt="Upload" height="150">
                                    <div class="image-title-wrap">
                                      <button type="button" onclick="removeUpload()" class="btn btn-danger btn-sm"><i class="fas fa-undo"></i> Ubah<span class="image-title"></span></button>
                                    </div>
                                </div>
                            </div>
                            <small class="text-danger">Silahkan upload foto dengan format JPG,JPEG maksimal 2M</small>
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

        <div class="btn-floating">
            <button type="submit" class="btn btn-add btn-primary"><span class="material-icons">add_circle</span></button>
            <button type="submit" class="btn btn-info btn-print"><span class="material-icons">print</span></button>
        </div>

</main>';
}?>