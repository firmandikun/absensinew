<?php 
include_once '../sw-library/sw-config.php';
include_once '../sw-library/sw-function.php';
 
//ob_start("minify_html");
echo'
 <!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>'.strip_tags($site_name).'</title>
        <meta name="description" content="'.$site_name.'">
        <meta name="author" content="s-widodo.com">
        <meta name="robots" content="noindex">
        <meta name="robots" content="nofollow">
        <meta name="csrf-token" content="'.$_SESSION['csrf_token'].'">
        <!-- Favicon -->
        <link rel="icon" href="../sw-content/'.$site_favicon.'" type="image/png">
        
        <link rel="stylesheet" href="../template/css/style.css">
        <link rel="stylesheet" href="../template/css/sw-custom.css">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="./main.css">

</head>
<body>';

if(!isset($_COOKIE['ADMIN_KEY'])){
    echo'<script>
        alert("Untuk menghindari kecurangan supaya tidak diakses urlnya oleh Siswa, Silahkan Login Dashboard Admin sebagai Admin terlebih dahulu");
    </script>';
}else{
/** Tampilkan Layar Absensi */
echo'
<div id="appCapsule" class="wrapper">
    <div class="logo text-center">
        <img src="../sw-content/'.$site_logo.'">
    </div>
    <div class="section mt-2">
        <div class="container-fluid mb-2">
            <div class="row">
            
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body card-body-1 text-center">
                            <div class="screen-slider">
                                <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel" data-interval="5000">
                                    <div class="carousel-inner">';
                                    $query_slide = "SELECT * FROM slider WHERE active ='Y' ORDER BY slider_id desc";
                                    $result_slide = $connection->query($query_slide);
                                    if($result_slide->num_rows > 0){$active= 0;
                                        while ($data_slide = $result_slide->fetch_assoc()){$active++;
                                        if($active==1){
                                            echo'
                                            <div class="carousel-item active">';
                                        }else{
                                            echo'
                                            <div class="carousel-item">';
                                        }
                                        
                                        if(file_exists('../../sw-content/slider/'.$data_slide['foto'].'')){
                                            echo'
                                            <img src="../template/img/sw-big.jpg" alt="'.strip_tags($data_slide['slider_nama']).'" class="d-block w-100">';
                                        }else{
                                            if($data_slide['foto']==''){
                                            echo'
                                            <img src="../template/img/sw-big.jpg" alt="'.strip_tags($data_slide['slider_nama']).'" class="d-block w-100">';
                                            }else{
                                            echo'
                                            <img src="../sw-content/slider/'.$data_slide['foto'].'" alt="'.strip_tags($data_slide['slider_nama']).'" class="d-block w-100">';
                                            }
                                        }
                                        echo'
                                        </div>';
                                        }
                                    }
                                    echo'
                                    </div>
                                    <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                            </div>

                            <h1 class="clock text-center"></h1>
                            <h1 class="kalender">'.format_hari_tanggal($date).' </h1>

                        </div>
                    </div>

                    <div class="transactions mt-3">
                        <div class="row data-counter-left" style="height:170px;">
                            <div class="col-md-6">
                                <div class="card border-0 mb-2 bg-warning">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/005-clipboard.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-white">On Time</strong>
                                                <p class="small text-white ontime">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 mb-2 bg-danger">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/004-time.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-white">Terlambat</strong>
                                                <p class="small text-white terlambat">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 mb-1 bg-info">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/002-verified.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-white">Izin</strong>
                                                <p class="text-white izin">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 mb-1  bg-secondary">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/002-verified.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-white">Cuti</strong>
                                                <p class="text-white cuti">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                </div>

                <div class="col-md-4">
            
                    <div class="card">
                        <div class="card-body card-body-2 pr-3 pl-3 pt-4 pb-5">
                            <h1 class="text-center text-white">ABSENSI DIGITAL V.5.2</h1>
                            <hr>
                            <form action="javascript:void(0);" class="form-absen mt-4" name="add"  method="POST"> 
                                <div class="form-group boxed mb-3">
                                    <input type="text" name="qrcode" class="form-control bg-warning qrcode" placeholder="Scan QRCODE Pegawai disini" required>
                                </div>
                                <hr>

                                <div class="mb-1 mb-4 text-center" style="margin-top:40px">
                                    <img src="../template/img/image.png" class="avatar-absen">
                                </div>

                                <div class="form-group text-center">
                                    <h3 class="timestamp text-white">Waktu Absen</h3>
                                </div>

                                <div class="form-group text-center">
                                    <h1 class="nama-pegawai text-white">Nama Pegawai</h1>
                                </div>

                                <div class="form-group text-center">
                                    <h3 class="status-absen text-white">Status</h3>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-info btn-md button-submit d-none">Submit</button>
                                </div>
                                <audio id="myaudio" class="d-none">
                                    <source src="../template/vendor/html5-qrcode/audio/beep.mp3" type="audio/mpeg">
                                </audio>
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="transactions">
                        <div class="row data-counter-right" style="height:170px;">

                            <div class="col-md-6">
                                <div class="card border-0 mb-2 bg-white">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/003-profile.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-secondary">Total Pegawai</strong>
                                                <p class="text-secondary total-pegawai">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 mb-2 bg-white">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/003-profile.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-secondary">Total Jabatan</strong>
                                                <p class="text-secondary total-posisi">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 mb-2 bg-white">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/002-sand-clock.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-secondary">Belum Absen</strong>
                                                <p class="text-secondary belum-absen">0</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="card border-0 mb-2 bg-white">
                                    <div class="card-body">
                                        <div class="row align-items-center">
                                            <div class="col-auto pr-0">
                                                <div class="avatar avatar-50 border-0 text-default">
                                                   <img src="../template/img/icons/007-insight.png" alt="img" class="image-block imaged w36">
                                                </div>
                                            </div>
                                            <div class="col align-self-center">
                                                <strong class="text-secondary">Total Absen</strong>
                                                <p class="text-secondary"><span class="total-absen">0</span>
                                                    <small class="text-info"><span class="material-icons ml-3" style="font-size:15px">show_chart</span> <span class="persentase ml-1">0</span>%</small>
                                                </p>

                                                
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    </div>


                    <div class="card mt-2">
                        <div class="card-body card-body-3" style="min-height:420px">
                        <h3>
                            Absensi terbaru
                        </h3>
                            <div class="table-responsive data-absensi">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

    <!-- Footer -->
    <div class="appBottomMenu  d-none bg-primary">
        <span class="credits">
            <a class="credits_a" id="mycredit" href="https://s-widodo.com"  target="_blank">S-widodo.com</a>
        </span>
    </div>

    <!-- Jquery -->
    <script src="../template/js/jquery-3.3.1.min.js"></script>
    <script src="../template/js/popper.min.js"></script>
    <script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../template/js/sweetalert.min.js"></script>
    <script src="../absen-screen/sw-script.js"></script>
    
</body>
</html>';
}
?>