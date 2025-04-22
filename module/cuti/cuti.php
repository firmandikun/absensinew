<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

    $notifikasi ="UPDATE notifikasi SET status='Y' WHERE link='cuti' AND tipe='2' AND user_id='$data_user[user_id]'"; 
    $connection->query($notifikasi);

    $query_hak_cuti ="SELECT jumlah FROM hak_cuti WHERE posisi_id='$data_user[posisi_id]' AND active='Y'";
    $result_hak_cuti = $connection->query($query_hak_cuti);
    if($result_hak_cuti->num_rows > 0){
        $data_hak_cuti = $result_hak_cuti->fetch_assoc();
        $jumlah_hak_cuti = $data_hak_cuti['jumlah'];

        $query_cuti ="SELECT SUM(jumlah) AS Totalcuty FROM cuti WHERE user_id='$data_user[user_id]' AND status='Y'";
        $result_cuti = $connection->query($query_cuti);
        $data_cuti = $result_cuti->fetch_assoc();

        $sisa_cuti = $jumlah_hak_cuti - $data_cuti['Totalcuty'];

        $message = 'Total hak cuti Anda saat ini tinggal <b class="badge badge-warning">'.$sisa_cuti.' hari</b><br>dari 
        <b class="badge badge-danger">'.$jumlah_hak_cuti.'</b> hak cuti';
    }else{
        $message ='';
    }

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="card shadow-default mb-3">
        <div class="card-body">
                <div class="row align-items-center">
                    <div class="col text-left">
                        <p>Selamat '.$salam.'<br>
                        <b>'.ucfirst($data_user['nama_lengkap']).'</b></p>
                    </div>
                    <div class="col pl-0 text-right">
                        <p>'.$hari_ini.'<br>'.tgl_ind($date).'</p>
                    </div>
                </div>
            <hr>
           
            <div class="text-left">
               <p>'.$message.'</p>
            </div>
        </div>
    </div>

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
                    <h6 class="subtitle mb-2">Cuti</h6>
                </div>
                <div class="load-cuty postList"></div>
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
                            <label class="form-control-label">Jenis Cuti</label>
                            <select class="form-control jenis" name="jenis" required>
                                <option value="">...</option>';
                                $query_status_izin="SELECT * FROM lain_lain WHERE tipe='cuti'";
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
                        <label class="form-control-label">Periode Cuti</label>
                            <div class="form-row">
                                <div class="col">
                                    
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control tanggal-mulai datepicker" name="tanggal_mulai" value="'.tanggal_ind($date).'" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="material-icons">calendar_month</span>
                                            </span>
                                        </div>
                                    </div>

                                </div>
                                <div class="col">
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control tanggal-selesai datepicker" name="tanggal_selesai" value="'.tanggal_ind($date).'" required>
                                        <div class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span class="material-icons">calendar_month</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Alasan Cuti</label>
                            <textarea class="form-control keterangan" name="keterangan" rows="2" required></textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Atasan Langsung</label>
                            <select class="form-control atasan" name="atasan" required>
                                <option value="">...</option>';
                                $query_atasan="SELECT admin_id,fullname FROM admin WHERE level='3'";
                                $result_atasan= $connection->query($query_atasan);
                                if ($result_atasan->num_rows > 0) {
                                while ($data_atasan = $result_atasan->fetch_assoc()){
                                    echo'<option value="'.strip_tags($data_atasan['admin_id']).'">'.strip_tags($data_atasan['fullname']).'</option>';
                                }
                                }else{
                                    echo'<option value="0">Data tidak ditemukan</option>';
                                }
                        echo'
                            </select>
                        </div>

                        <div class="form-group">
                          <label>Upload bukti/surat cuti</label>
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