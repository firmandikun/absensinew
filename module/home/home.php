<?php if ($mod ==''){
    header('location:../404');
}else{
if(!isset($_COOKIE['USER_KEY'])){
echo'
<!-- Begin page content -->
    <main class="flex-shrink-0 main has-footer">
            <!-- Fixed navbar -->
            <header class="header">
              
            </header>
            
        <form class="form-login" role="form" method="post" action="javascript:;" autocomplete="off">
            <div class="container h-100 text-white">
                <div class="row h-100">
                    <div class="col-12 align-self-center mb-4 mt-4">
                        <div class="row justify-content-center">
                            <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                                <h6 class="mb-5 mt-3 text-center">Masuk ke akun Anda</h6>
                                <div class="form-group float-label">
                                    <input type="email" name="username" class="form-control text-white" required>
                                    <label class="form-control-label email text-white">Email</label>
                                </div>

                                <div class="form-group float-label position-relative">
                                    <input type="password" name="password" id="password" class="form-control password text-white">
                                    <label class="form-control-label text-white">Password</label>
                                    <div class="input-group-append">
                                        <span class="input-group-custom input-group-text">
                                            <span toggle="#password" class="fas fa-eye toggle-password">
                                            </span>
                                        </span>
                                    </div>
                                </div>  
                                <p class="text-right"><a href="forgot" class="text-white">Forgot Password?</a></p>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </main>

                <!-- footer-->
                <div class="footer no-bg-shadow py-3">
                        <div class="row justify-content-center">
                        <div class="col-11 col-sm-7"></div>
                            <div class="col-11 col-sm-7"></div>
    <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4">
        <button type="submit" class="btn btn-default rounded btn-block btn-login"><i class="fas fa-sign-in-alt"></i> Login</button>
    </div>
    </div>
                        </div>
                </div>
        </form>';
}else{

echo'
    <main class="flex-shrink-0 main has-footer">
            <div class="container mt-2 mb-4 text-center">
                <h4 class="text-white">'.strip_tags($data_user['nama_lengkap']).'</h4>
                <p class="text-white">'.strip_tags($data_user['posisi_nama']).'</p>
            </div>

                <div class="card bg-default-secondary shadow-default mb-4">
                    <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col text-left">
                                    <b>'.format_hari_tanggal($date).'</b>
                                </div>
                                <div class="col pl-0 text-right">';
                                    if($result_jam_kerja->num_rows > 0){
                                        $data_jam_kerja = $result_jam_kerja->fetch_assoc();
                                        echo'<p>'.strip_tags($data_jam_kerja['jam_masuk']).' - '.strip_tags($data_jam_kerja['jam_pulang']).'</p>';
                                      }else{
                                        echo'Tidak ada jadwal';
                                      }
                                    echo'
                                </div>
                        </div>
                        <hr>
                        <!-- Menu -->

            <div class="row">
                <div class="col-6 col-md-6">';
                    if($result_absen == ''){
                    echo'<a href="absen-in">';
                    }else{
                    echo'<a href="javascript:void(0);">';
                    }
                    echo'
                    <div class="card border-0">
                        <div class="card-body button-absent" style="padding:15px 5px">
                                <div class="col align-self-center">
                                    <span class="material-icons text-primary">login</span>
                                    <p class="mb-0">IN</p>';
                                        if($result_absen == ''){
                                            echo'
                                            <p class="small text-secondary">Belum absen</p>';
                                        }else{
                                            foreach ($data_absen['data_absensi'] as $row) {
                                            echo'
                                            <p class="small text-secondary">'.$row['absen_in'].'</p>';
                                            }
                                        }
                                echo'
                                </div>
                        </div>
                    </div>
                    </a>
                </div>
                
                <div class="col-6 col-md-6">';
                if($result_absen == ''){
                    echo'<a href="javascript:void(0);">';
                    }else{
                        foreach ($data_absen['data_absensi'] as $row) {
                            if($row['absen_out'] == '00:00:00'){
                                echo'<a href="absen-out">';
                            }else{
                                echo'<a href="javascript:void(0);">';
                            }
                        }
                    }
                    echo'
                    <div class="card border-0">
                        <div class="card-body button-absent" style="padding:15px 5px">
                                <div class="col align-self-center">
                                    <span class="material-icons text-warning">logout</span>
                                    <p class="mb-0">OUT</p>';
                                    if($result_absen == ''){
                                        echo'
                                        <p class="small text-secondary">Belum absen</p>';
                                    }else{
                                        foreach ($data_absen['data_absensi'] as $row) {
                                            if($row['absen_out'] == '00:00:00'){
                                            echo'<p class="small text-secondary">Belum absen</p>';
                                            }else{
                                            echo'
                                            <p class="small text-secondary">'.$row['absen_out'].'</p>';
                                            }
                                        }
                                    }
                                echo'
                                </div>
                        </div>
                    </div>
                    </a>
                </div>
            </div>

        </div>
    </div>';

    if($data_user['alamat']==''){
    echo'<div class="card mb-2 bg-warning">
        <div class="card-body  text-center">
            <p>Sebelum melakukan absensi kehadiran, Silahkan Lengkapi profile Bapak/Ibu ke menu <b class="text-danger">Akun</b></p>
        </div>
        </div>';
    }
    echo'        
    <div class="main-container">
         
        <div class="container mb-4">
                <div class="card">
                    <div class="card-body text-center ">
                        <div class="row justify-content-equal no-gutters">
                            <div class="col-4 col-md-2 mb-3">
                                <a href="./izin">
                                    <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">receipt_long</span></div>
                                    <p class="text-secondary"><small>Izin</small></p>
                                </a>
                            </div>

                            <div class="col-4 col-md-2 mb-3">
                                <a href="./cuti">
                                    <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">edit_calendar</span></div>
                                    <p class="text-secondary"><small>Cuti</small></p>
                                </a>
                            </div>


                          <div class="col-4 col-md-2">
                                <a href="./blog">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">wb_incandescent</span></div>
                                <p class="text-secondary"><small>Informasi</small></p>
                                </a>
                            </div>

                            
                             <div class="col-4 col-md-2 mb-3">
                                <a href="./jam-kerja">
                                <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">schedule</span></div>
                                <p class="text-secondary"><small>Jam Kerja</small></p>
                                </a>
                            </div>
                            
                            <div class="col-4 col-md-2 mb-3">
                                <a href="./histori-absen">
                                    <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">fact_check</span>
                                    </div>
                                <p class="text-secondary"><small>Rekap Absensi</small></p>
                                </a>
                            </div>
                               <div class="col-4 col-md-2">
                                <a href="./profile">
                                    <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">assignment_ind</span></div>
                                    <p class="text-secondary"><small>Profile</small></p>
                                </a>
                            </div>
                        </div>

                        <button class="btn btn-sm btn-outline-secondary rounded" id="more-expand-btn">Show more <span class="ml-2 small material-icons">expand_more</span></button>
                        <div class="row justify-content-equal no-gutters" id="more-expand">

                            <div class="col-4 col-md-2">
                                <a href="./setting">
                                    <div class="icon icon-50 rounded-circle mb-1 bg-default-light text-default"><span class="material-icons">manage_accounts</span></div>
                                    <p class="text-secondary"><small>Setting</small></p>
                                </a>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            
            
            <div class="container">';
                $query_slide = "SELECT * FROM slider WHERE active ='Y'";
                $result_slide = $connection->query($query_slide);
                if($result_slide->num_rows > 0){
                    echo'
                    <!-- Swiper intro -->
                    <div class="swiper-container introduction text-white mb-4">
                        <div class="swiper-wrapper">';
                        while ($data_slide = $result_slide->fetch_assoc()){
                            echo'
                            <div class="swiper-slide slider overflow-hidden text-center">
                                <a href="'.strip_tags($data_slide['slider_url']).'">
                                    <div class="align-self-center">';
                                    if(file_exists('../../sw-content/slider/'.$data_slide['foto'].'')){
                                        echo'
                                        <img src="template/img/sw-big.jpg" alt="'.strip_tags($data_slide['slider_nama']).'" class="mw-100">';
                                    }else{
                                        if($data_slide['foto']==''){
                                        echo'
                                        <img src="template/img/sw-big.jpg" alt="'.strip_tags($data_slide['slider_nama']).'" class="mw-100">';
                                        }else{
                                        echo'
                                        <img src="sw-content/slider/'.$data_slide['foto'].'" alt="'.strip_tags($data_slide['slider_nama']).'" class="mw-100">';
                                        }
                                    }
                                    echo'
                                    </div>
                                </a>
                            </div>';
                        }
                    echo'   
                        </div>
                        <!-- Add Pagination -->
                        <div class="swiper-pagination"></div>
                    </div>';
                }
        
        /** Artikel Terbaru */
        echo'
        <div class="row mb-3">
            <div class="col">
                <h6 class="subtitle mb-0">Artikel</h6>
            </div>
            <div class="col-auto"><a href="blog" class="float-right small">View All</a></div>
        </div>';
            $query_artikel="SELECT artikel_id,judul,domain,foto,deskripsi,kategori,date FROM artikel WHERE active='Y' ORDER BY artikel_id DESC LIMIT 5";
            $result_artikel = $connection->query($query_artikel);
            if($result_artikel->num_rows > 0){
            echo'
            <div class="swiper-container swiper-home-article text-center mb-4">
                <div class="swiper-wrapper mb-2">';
                    
                    while ($data_artikel = $result_artikel->fetch_assoc()){
                        $judul = strip_tags($data_artikel['judul']);
                        if(strlen($judul ) >30)$judul= substr($judul,0,30).'..';
                        echo'
                        <div class="card border-0 mb-4 overflow-hidden swiper-slide">
                            <div class="card-body h-150 position-relative">
                                <a href="./blog-'.strip_tags($data_artikel['artikel_id']).'-'.strip_tags($data_artikel['domain']).'" class="background" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_artikel['judul']).'">';
                                    if(file_exists('./sw-content/artikel/thumbnail/'.$data_artikel['foto'].'')){
                                        echo'<img src="./sw-content/artikel/thumbnail/'.$data_artikel['foto'].'" height="150">';
                                    }else{
                                        echo'<img src="./sw-content/thumbnail.jpg" height="150">';
                                    }
                            echo' 
                                </a>
                            </div>
                            <div class="card-body ">
                                <p class="mb-0"><small class="text-secondary">'.$data_artikel['date'].'</small></p>
                                <a href="./blog-'.strip_tags($data_artikel['artikel_id']).'-'.strip_tags($data_artikel['domain']).'" title="'.strip_tags($data_artikel['judul']).'">
                                    <p class="mb-0">'.$judul.'</p>
                                </a>
                            </div>
                        </div>';}     
                echo'
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>';
            }else{
                echo'<div class="alert alert-info">Saat ini belum ada artikel</div>';
            }

            echo'
            <!-- Absensi selama 1 minggu -->
            <div class="container mb-4">
                <div class="row">
                    <div class="col">
                        <h6 class="subtitle mb-3">Absensi Terbaru</h6>
                    </div>
                    <div class="col-auto">
                        <a href="./histori-absen" class="float-right small">View all</a>
                    </div>
                </div>';
                $query_histori ="SELECT tanggal,absen_in,absen_out,status_masuk,kehadiran FROM absen WHERE YEARWEEK(tanggal)=YEARWEEK(NOW()) AND user_id='$data_user[user_id]' ORDER BY absen_id DESC LIMIT 5";
                $result_histori = $connection->query($query_histori);
                if($result_histori->num_rows > 0){
                    while ($data_histori= $result_histori->fetch_assoc()) {

                        if($data_histori['kehadiran'] == 'Hadir'){
                            if($data_histori['status_masuk']=='Telat'){
                                $status ='<button class="btn btn-danger btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['status_masuk']).'"><i class="material-icons">sentiment_dissatisfied</i></button>';
                            }else{
                                $status ='<button class="btn btn-default btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['status_masuk']).'"><i class="material-icons">mood</i></button>';
                            }
                        }else{
                            $status ='<button class="btn btn-warning btn-40 rounded-circle" data-toggle="tooltip" data-placement="top" title="'.strip_tags($data_histori['kehadiran']).'"><i class="material-icons">sentiment_dissatisfied</i></button>';
                        }

                        if($data_histori['kehadiran'] == 'Hadir'){
                            $kehadiran = '';
                          }else{
                            $kehadiran = '<span class="badge badge-info">'.strip_tags($data_histori['kehadiran']).'</span>';
                        }

                        echo'
                        <div class="card border-0 mb-2">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col">
                                        <p class="text-secondary small">'.format_hari_tanggal($data_histori['tanggal']).' '.$kehadiran.'</p>
                                    </div> 

                                </div>

                                <div class="row align-items-center">
                                    <div class="col-5 align-self-center">
                                        <small class="text-secondary">CHECK IN</small>
                                        <p class="text-info">'.strip_tags($data_histori['absen_in']).'</p>
                                    </div>
                                    <div class="col align-self-center">
                                    <small class="text-secondary">CHECK OUT</small>
                                        <p class="text-success">';
                                        if($data_histori['absen_out']=='00:00:00'){
                                            echo'-';
                                        }else{
                                            echo''.strip_tags($data_histori['absen_out']).'';
                                        }
                                    echo'</p>
                                    </div>
                                    <div class="col-auto">
                                        '.$status.'
                                    </div>
                                </div>
                            </div>
                        </div>';
                    }}else{
                        echo'Saat ini belum ada data absensi!';
                    }
                    echo'
            </div>
            
    </div>
</main>';
}}?>