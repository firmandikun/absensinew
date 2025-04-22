<?php if(empty($connection)){
	header('location:./');
    exit();
} else {
/* tipe 1 untuk notifikasi admin */
$query_notifikasi   ="SELECT notifikasi_id,nama,keterangan,link,tanggal,datetime,status FROM notifikasi WHERE tipe='1' AND status='N' ORDER BY notifikasi_id DESC LIMIT 5";
$result_notifikasi = $connection->query($query_notifikasi);

$query_notifikasi_chat ="SELECT chat_id FROM chat WHERE status_user='N' AND admin_id='$current_user[admin_id]'";
$result_notifikasi_chat = $connection->query($query_notifikasi_chat);
echo'
<!DOCTYPE html>
        <html>
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Dashboard</title>
        
        <meta name="robots" content="noindex">
        <meta name="googlebot" content="noindex">
        <meta name="description" content="s-widodo.com">
        <meta name="author" content="s-widodo.com">
        
        <!-- Favicon -->
        <link rel="icon" href="../sw-content/'.$site_favicon.'" type="image/png">
        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
        <!-- Icons -->
        <link rel="stylesheet" href="sw-assets/vendor/nucleo/css/nucleo.css" type="text/css">
        <link rel="stylesheet" href="sw-assets/vendor/@fortawesome/fontawesome-free/css/all.min.css" type="text/css">
        <!-- Page plugins -->
        <link rel="stylesheet" href="sw-assets/vendor/timepicker/bootstrap-timepicker.min.css">
        <link href="sw-assets/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

        <link rel="stylesheet" href="sw-assets/vendor/select2/dist/css/select2.min.css">
        <link rel="stylesheet" href="sw-assets/vendor/Magnific-Popup/magnific-popup.css">
        <link href="../template/vendor/emojionearea/emojionearea.css" rel="stylesheet">
        <!-- Argon CSS -->
        <link rel="stylesheet" href="sw-assets/css/app.css" type="text/css">
        <link rel="stylesheet" href="sw-assets/css/argon.css" type="text/css">';
        if($mod=='lokasi'){
        echo'
        <link rel="stylesheet" href="./sw-assets/vendor/leatfet/leaflet.css">
        <link rel="stylesheet" href="./sw-assets/vendor/leatfet/L.Control.Locate.min.css">';
        }
        if($mod=='artikel'){
        echo'
        <script src="sw-assets/vendor/tinymce/tinymce.min.js"></script>';
        }
        echo'
        </head>';
        if($mod=='lokasi'){
            if(@$_GET['op'] == 'add' OR @$_GET['op'] == 'update'){
            echo'<body onload="lokasi();">';
            }else{
            echo'<body>';
            }
        }else{
            echo'<body>';
        }
        echo'
        <!-- Sidenav -->';
        /** Sidebar */
        include_once'sidebar.php';
        /** End Sidebar */
        echo'
        <div class="main-content" id="panel">
            <!-- Topnav -->
            <nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
            <div class="container-fluid">
                <div class="collapse navbar-collapse d-flex justify-content-between" id="navbarSupportedContent">
                <!-- Navbar links -->
                <ul class="navbar-nav align-items-center d-none d-xl-block">
                    <li class="nav-item">
                        <a class="nav-link" href="../" target="_blank" role="button">
                            <i class="ni ni-laptop"></i>
                        </a> 
                    </li>
                </ul>

                <ul class="navbar-nav align-items-center ml-md-auto">
                    <li class="nav-item d-xl-none">
                    <!-- Sidenav toggler -->
                    <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
                        <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        </div>
                    </div>
                    </li>

                  

                    <li class="nav-item dropdown">
                   
                    <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
                        <!-- Dropdown header -->
                        <div class="px-3 py-3">
                        <h6 class="text-sm text-muted m-0">Ada <strong class="text-primary">'.$result_notifikasi->num_rows.'</strong> notifikasi</h6>
                        </div>
                        <!-- List group -->
                        <div class="list-group list-group-flush">';
                        if($result_notifikasi->num_rows > 0) {
                            while($data_notifikasi = $result_notifikasi->fetch_assoc()) {
                                echo'
                                <a href="'.$data_notifikasi['link'].'" class="list-group-item list-group-item-action btn-notifikasi" data-id="'.$data_notifikasi['notifikasi_id'].'">
                                    <div class="row align-items-center">
                                    <div class="col ml--2">
                                        <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h4 class="mb-0 text-sm">'.strip_tags($data_notifikasi['nama']).' | '.tanggal_ind($data_notifikasi['tanggal']).'</h4>
                                        </div>
                                        <div class="text-right text-muted">
                                            <small>'.time_since(strtotime($data_notifikasi['datetime'])).'</small>
                                        </div>
                                        </div>
                                        <p class="text-sm mb-0">'.strip_tags($data_notifikasi['keterangan']).'</p>
                                    </div>
                                    </div>
                                </a>';
                            }
                        }else{
                            echo'
                            <a href="#" class="list-group-item list-group-item-action">
                                Tidak ada notifikasi 
                            </a>';
                        }
                        echo'
                        </div>
                      
                    </div>
                    </li>

                    
                </ul>
                <ul class="navbar-nav align-items-center ml-auto ml-md-0">
                    <li class="nav-item dropdown">
                    <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="media align-items-center">
                        <span class="avatar avatar-sm rounded-circle">';
                        if(file_exists('../sw-content/avatar/'.$current_user['avatar'].'')){
                            echo'<img src="data:image/gif;base64,'.base64_encode(file_get_contents('../sw-content/avatar/'.$current_user['avatar'].'')).'" height="35">';
                        }else{
                            echo' <img src="../sw-content/avatar/avatar.jpg" height="35">';
                        }

                        echo'
                        </span>
                        <div class="media-body ml-2 d-none d-lg-block">
                            <span class="mb-0 text-sm  font-weight-bold">'.strip_tags($current_user['fullname']).'</span>
                        </div>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right">
                        <div class="dropdown-header noti-title">
                        <h6 class="text-overflow m-0">Welcome!</h6>
                        </div>
                        <a href="./profile" class="dropdown-item">
                            <i class="ni ni-single-02"></i>
                            <span>My profile</span>
                        </a>

                        <a href="./setting" class="dropdown-item">
                            <i class="ni ni-settings-gear-65"></i>
                            <span>Settings</span>
                        </a>
                
                        <div class="dropdown-divider"></div>
                        <a href="./logout" class="dropdown-item">
                            <i class="ni ni-user-run"></i>
                            <span>Logout</span>
                        </a>
                    </div>
                    </li>
                </ul>
                </div>
            </div>
            </nav>
            <!-- Header -->';
    }?>