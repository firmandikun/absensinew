<?php 
include_once '../sw-library/sw-config.php';
include_once '../sw-library/sw-function.php';
echo'
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>'.$site_name.'</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="Aplikasi Absensi Kehadiran Karyawan Online dibuat oleh s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <meta http-equiv="Copyright" content="'.$site_name.'">
    <meta name="copyright" content="s-widodo.com">
    <meta name="csrf-token" content="'.$_SESSION['csrf_token'].'">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link rel="shortcut icon" href="../sw-content/'.$site_favicon.'">
    <link rel="apple-touch-icon" href="../sw-content/'.$site_favicon.'">
    <link rel="apple-touch-icon" sizes="72x72" href="../sw-content/'.$site_favicon.'">
    <link rel="apple-touch-icon" sizes="114x114" href="../sw-content/'.$site_favicon.'">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- swiper CSS -->
    <link href="../template/vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="../template/css/style.css" rel="stylesheet" id="style">
    <style>
        @media only screen and (min-width:1024px) {
            #reader__scan_region video{
                width:600px!important;
        }
    </style>
</head>
    
<body class="body-scroll d-flex flex-column h-100 menu-overlay">
    <main class="flex-shrink-0 main">

        <header class="header">
            <div class="row justify-content-md-center">
                <div class="text-center mt-3">
                    <img src="../sw-content/'.strip_tags($site_logo).'" class="w-30" height="30">
                </div>
            </div>
        </header>

        <div class="main-container">
            <div class="container">
                <div class="card">
                    <div class="card-body text-center">
                    <div class="row header-webcame mb-3">
                        <div class="col text-left">
                            <span class="title font-weight-light"> Selamat '.$salam.'</span>
                            <p class="subtitle text-nowrap text-success" style="width: 8rem;"></p>
                        </div>
                        <div class="col-auto">
                            <span class="title text-right">'.tgl_ind($date).' </span>
                            <p class="text-right text-success"><span class="clock"></span></p>
                        </div>
                    </div>
                        <hr>
                            <p>Arahkan Kamera ke QRCODE, Hanya bisa Absen dilokasi</p>';
                            if(isset($_COOKIE['ADMIN_KEY']) && isset($_COOKIE['KEY'])){
                            echo'
                            <div class="webcame text-center">
                                <div class="card bg-warning">
                                    <div class="card-body">
                                    <div class="container">
                                        <div class="row justify-content-md-center">
                                            <div class="col-6 col-md-auto">
                                                <div class="custom-control custom-switch">
                                                    <input type="radio" name="tipe_absen" class="custom-control-input tipe-absen" id="menu-overlay" value="masuk" checked>
                                                    <label class="custom-control-label" for="menu-overlay">Absen Masuk</label>
                                                </div>
                                            </div>
                                            <div class="col-6 col-md-auto">
                                                <div class="custom-control custom-switch">
                                                    <input type="radio" name="tipe_absen" class="custom-control-input tipe-absen" id="menu-pushcontent" value="pulang">
                                                    <label class="custom-control-label" for="menu-pushcontent">Absen Pulang</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="card">
                                    <div class="card-body text-center">
                                        <div id="reader"></div>
                                    </div>
                                </div>
                              
                            </div>';
                        }else{
                            echo'<div class="alert alert-warning">Untuk membuka Absensi ini harus login sebagai Admin terlebih dahulu</div>';
                        }
                    echo'
                    </div>
                </div>
            </div>
        </div>
    </main>

    <span class="credits d-none">
        <a class="credits_a" id="mycredit" href="https://s-widodo.com"  target="_blank">S-widodo.com</a>
    </span>
    
    <script src="../template/js/jquery-3.3.1.min.js"></script>
    <script src="../template/js/popper.min.js"></script>
    <script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="../template/js/sweetalert.min.js"></script>
    <script src="../template/vendor/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script src="../scan-absen/sw-script.js"></script>
    </body>
</html>';
?>