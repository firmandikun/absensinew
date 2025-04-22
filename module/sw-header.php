<?php if(empty($connection)){
  header('location:./404');
} else {

if(!empty($_COOKIE['USER_KEY'])){
/* tipe 2 untuk notifikasi ke user */
$query_notifikasi   ="SELECT notifikasi_id,nama,keterangan,link,tanggal,datetime,status FROM notifikasi WHERE user_id='$data_user[user_id]' AND tipe='2' AND status='N' ORDER BY notifikasi_id DESC LIMIT 5";
$result_notifikasi = $connection->query($query_notifikasi);

/** Notifikasi lve chat */
$query_notifikasi_chat ="SELECT chat_id FROM chat WHERE status_admin='N' AND user_id='$data_user[user_id]'";
$result_notifikasi_chat = $connection->query($query_notifikasi_chat);
}

$kembali = $base_url;
if($mod=='jam-kerja'){
    $title ='Jam Kerja';
}elseif($mod=='absen-in'){
    $title ='Absen Masuk';
}elseif($mod=='absen-out'){
    $title ='Absen Pulang';
}elseif($mod=='overtime'){
    $title ='Overtime';
}elseif($mod=='histori-absen'){
    $title ='Daftar Kehadiran';
}elseif($mod=='izin'){
    $title ='Izin';
}elseif($mod=='cuti'){
    $title ='Cuti';
}elseif($mod=='uraian-kerja'){
    $title ='Laporan Kerja';
}elseif($mod=='blog' OR $mod=='blog-details'){
    $title ='Artikel';
    $kembali = ''.$base_url.'blog';
}elseif($mod=='setting'){
    $title ='Pengaturan';
}elseif($mod=='recognition'){
    $title ='Wajah';
}elseif($mod=='profile'){
    $title ='Profile';
}elseif($mod=='kartu-nama'){
    $title ='ID Card';
}elseif($mod=='keamanan'){
    $title ='Keamanan';
}elseif($mod=='kunjungan'){
    $title ='Kunjungan';
}
else{
    $title='Home';
    $kembali = $base_url;
}
echo'
<!doctype html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>'.$website_name.'</title>
    <meta name="robots" content="noindex">
    <meta name="description" content="Aplikasi Absensi Kehadiran Karyawan Online dibuat oleh s-widodo.com">
    <meta name="author" content="s-widodo.com">
    <meta http-equiv="Copyright" content="'.$website_name.'">
    <meta name="copyright" content="s-widodo.com">
    <meta name="csrf-token" content="'.$_SESSION['csrf_token'].'">
    <meta name="apple-mobile-web-app-capable" content="yes">

    <link rel="shortcut icon" href="'.$base_url.'sw-content/'.$site_favicon.'">
    <link rel="apple-touch-icon" href="'.$base_url.'sw-content/'.$site_favicon.'">
    <link rel="apple-touch-icon" sizes="72x72" href="'.$base_url.'sw-content/'.$site_favicon.'">
    <link rel="apple-touch-icon" sizes="114x114" href="'.$base_url.'sw-content/'.$site_favicon.'">

    <!-- Material icons-->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap" rel="stylesheet">
    <!-- swiper CSS -->
    <link href="'.$base_url.'template/vendor/swiper/css/swiper.min.css" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="'.$base_url.'template/css/style.css" rel="stylesheet" id="style">
    <link href="'.$base_url.'template/vendor/bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.min.css" rel="stylesheet">
    <link href="'.$base_url.'template/css/chat.css" rel="stylesheet">
    <link href="'.$base_url.'template/vendor/emojionearea/emojionearea.css" rel="stylesheet">
    <link href="'.$base_url.'template/vendor/fontawesome/css/all.min.css" rel="stylesheet">
    <link href="'.$base_url.'template/vendor/webcame/webcam.css" rel="stylesheet">

</head>
<body class="body-scroll d-flex flex-column h-100 menu-overlay" data-page="homepage">
 <span class="base-url d-none">'.$base_url.'</span>
<!-- screen loader -->
    <div class="container-fluid h-100 loader-display">
        <div class="row h-100">
            <div class="align-self-center col">
                <div class="logo-loading">
                    <div class="loader-ellipsis">
                        <div></div>
                        <div></div>
                        <div></div>
                        <div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>';

    if(!empty($_COOKIE['USER_KEY'])){
    echo'
    <!-- Fixed navbar -->
        <header class="header">
            <div class="row">';
            if($mod =='home'){
                echo'
                <div class="col-auto px-0">
                    <button class="menu-btn btn btn-40 btn-link" type="button">
                        <span class="material-icons">menu</span>
                    </button>
                </div>';
            }else{
                echo'
                <div class="col-auto px-0">
                    <a href="'.$kembali.'" class="btn btn-40 btn-link back-btn">
                        <span class="material-icons">keyboard_arrow_left</span>
                    </a>
                </div>
                
                <div class="text-left col align-self-center">
                    <h6 class="mb-0 text-white">'.$title.'</h6>
                </div>';
            }
            echo'
               
            </div>
        </header>';
        
    echo'
    <!-- menu main -->
    <div class="main-menu">
        <div class="row mb-4 no-gutters">
            <div class="col-auto"><button class="btn btn-link btn-40 btn-close text-white"><span class="material-icons">chevron_left</span></button></div>
            <div class="col-auto">
                <div class="avatar avatar-40 rounded-circle position-relative">
                    <figure class="background">';
                    if(file_exists('./sw-content/avatar/'.$data_user['avatar'].'')){
                        echo'<img src="data:image/gif;base64,'.base64_encode(file_get_contents('./sw-content/avatar/'.$data_user['avatar'].'')).'" height="40">';
                    }else{
                        echo'<img src="./sw-content/avatar/avatar.jpg" height="40">';
                    }
                echo'
                    </figure>
                </div>
            </div>
            <div class="col pl-3 text-left align-self-center">
                <h6 class="mb-1">'.strip_tags(substr($data_user['nama_lengkap'],0, 12)).'</h6>
                <p class="small text-default-secondary">'.strip_tags($data_user['nip']).'</p>
            </div>
        </div>

        <div class="menu-container">
        
            <ul class="nav nav-pills flex-column">
                <li class="nav-item">
                    <a class="nav-link active" href="'.$base_url.'">
                        <div>
                            <span class="material-icons icon">home</span>
                            Home
                        </div>
                    </a>
                </li>

                

                <li class="nav-item">
                    <a class="nav-link active" href="./histori-absen">
                        <div>
                            <span class="material-icons icon">toc</span>
                            Daftar Kehadiran
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="./overtime">
                        <div>
                            <span class="material-icons icon">update</span>
                            Overtime
                        </div>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link active" href="./uraian-kerja">
                        <div>
                            <span class="material-icons icon">pending_actions</span>
                            Laporan Kerja
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="./izin">
                        <div>
                            <span class="material-icons icon">receipt_long</span>
                            Izin
                        </div>
                    </a>
                </li>


                <li class="nav-item">
                    <a class="nav-link active" href="./cuti">
                        <div>
                            <span class="material-icons icon">edit_calendar</span>
                            Cuti
                        </div>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active" href="./blog">
                        <div>
                            <span class="material-icons icon">wb_incandescent</span>
                            Informasi
                        </div>
                    </a>
                </li>

            </ul>
            <div class="text-center">
                <a href="./logout" class="btn btn-outline-danger text-white rounded my-3 mx-auto">Logout</a>
            </div>
        </div>
    </div>
    <div class="backdrop"></div>';
    }
}?>