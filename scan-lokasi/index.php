<?php 
include_once '../sw-library/sw-config.php';
include_once '../sw-library/sw-function.php';;
require_once '../sw-library/phpqrcode/qrlib.php';

if(!empty($_GET['lokasi'])){
   $lokasi = anti_injection(epm_decode($_GET['lokasi']));
   $query_lokasi ="SELECT lokasi_id,lokasi_qrcode,lokasi_nama FROM lokasi WHERE lokasi_id='$lokasi'";
   $result_lokasi = $connection->query($query_lokasi);

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
                            <h5 class="mb-3">Arahkan Kamera ke QRCODE, Hanya bisa Absen dilokasi</h5>';
                            if($result_lokasi->num_rows > 0) {
                                $data_lokasi = $result_lokasi->fetch_assoc();
                                if(file_exists('../sw-content/lokasi/'.seo_title($data_lokasi['lokasi_qrcode']).'.jpg')){
                                    //echo 'QR code ada';
                                }else{
                                    /* --  End Random Karakter ---- */
                                    $codeContents = $data_lokasi['lokasi_qrcode'];
                                    $tempdir = '../sw-content/lokasi/';
                                    $namafile = ''.seo_title($codeContents).'.jpg';
                                    $quality = 'H'; //ada 4 pilihan, L (Low), M(Medium), Q(Good), H(High)
                                    $ukuran = 10; //batasan 1 paling kecil, 10 paling besar
                                    $padding = 1;
                                    QRCode::png($codeContents,$tempdir.$namafile,$quality,$ukuran,$padding);
                                }
                        echo'
                            <span class="id d-none">'.$data_lokasi['lokasi_id'].'</span>
                            <div class="webcame text-center">
                                <div class="card bg-info">
                                    <div class="card-body text-center">
                                        <img class="imaged" src="../sw-content/lokasi/'.strip_tags(seo_title($data_lokasi['lokasi_qrcode'])).'.jpg" height="500" style="height:400px">
                                    </div>
                                </div>
                                <p class="mt-2">Lokasi : '.$data_lokasi['lokasi_nama'].'</p>
                                <h5 id="countdown"></h5>
                            </div>';
                        }else{
                            echo'Lokasi tidak ditemukan';
                        }
                    echo'              
                    </div>
                </div>
            </div>
        </div>
        
    </main>

    <button class="btn btn-success" onclick="toggleFullScreen();">Full Screen</button>

    <span class="credits d-none">
        <a class="credits_a" id="mycredit" href="https://s-widodo.com"  target="_blank">S-widodo.com</a>
    </span>
    
    <script src="../template/js/jquery-3.3.1.min.js"></script>
    <script src="../template/js/popper.min.js"></script>
    <script src="../template/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="./sw-script.js"></script>
        <script>
        window.onload = function() {
            var el = document.documentElement,
                rfs = el.requestFullScreen
                || el.webkitRequestFullScreen
                || el.mozRequestFullScreen;
            rfs.call(el);
          };
        </script>  
    </body>
</html>';
}
?>