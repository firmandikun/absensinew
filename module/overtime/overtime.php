<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="card shadow-default mb-4">
        <div class="card-body">
                <div class="row align-items-center">
                    <div class="col text-left">
                        <p>Selamat '.$salam.'<br>
                        <b>'.ucfirst($data_user['nama_lengkap']).'</b></p>
                    </div>
                    <div class="col pl-0 text-right">
                        <p>'.$hari_ini.'<br>';
                        if($result_jam_kerja->num_rows > 0){
                            $data_jam_kerja = $result_jam_kerja->fetch_assoc();
                            echo''.strip_tags($data_jam_kerja['jam_masuk']).' - '.strip_tags($data_jam_kerja['jam_pulang']).'';
                        }else{
                            echo'Tidak ada';
                        }
                        echo'</p>
                    </div>
                </div>
            <hr>
            <!-- Swiper -->
            <div class="text-center">
                <p>Overtime</p>
                <div class="row mt-2 mb-2">
                    <div class="col-6 align-self-center">';
                    if (isset($_COOKIE['overtime'])){
                        echo'
                        <button type="submit" class="btn btn-block btn-check-in btn-outline-secondary disabled">Check In</button>';
                    }else{
                        echo'
                        <button type="submit" class="btn btn-block btn-check-in btn-primary btn-checkin">Check In</button>';
                    }
                    echo'
                    </div>

                    <div class="col-6 align-self-center">';
                    if (isset($_COOKIE['overtime'])){
                        echo'
                        <button type="submit" class="btn btn-block btn-primary btn-checout">Check Out</button>';
                    }else{
                        echo'
                        <button type="submit" class="btn btn-block btn-outline-secondary disabled">Check Out</button>';
                    }
                    echo'
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

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
                                    <input type="text" class="form-control mulai search datepicker" value="'.tanggal_ind($date).'" placeholder="Mulai">
                                </div>

                            </div>

                            <div class="col-md-6">
                                <div class="form-group float-label position-relative mb-1">
                                    <div class="bottom-right ">
                                        <a href="#" class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">calendar_month</i></a>
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
                    <h6 class="subtitle mb-2">Overtime</h6>
                </div>

                <div class="load-overtime postList"></div>
                
            </div>
    </div>

    <!-- Modal Overtime -->
        <div class="modal fade modal-overtime" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-sm modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                            <form class="form-profile" role="form" method="post" action="#" autocomplete="off">
                            <div class="form-group">
                                <label class="form-control-label">Keterangan</label>
                                <input type="text" class="form-control" name="nip" value="'.strip_tags($data_user['nip']).'" required>
                            </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Check In</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="btn-floating">
            <button type="submit" class="btn btn-info btn-print"><span class="material-icons">print</span></button>
        </div>
</main>';
}?>