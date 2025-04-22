<?php

$query_modul = "SELECT modul_id FROM role WHERE lihat='Y' AND level_id='$current_user[level]'";
$result_modul  = $connection->query($query_modul);
if($result_modul->num_rows > 0){
    $response = array();
    $response["data_role"] = array();
    while ($data_modul = $result_modul->fetch_assoc()){
        $data_row['modul_id'] = $data_modul['modul_id'];
        array_push($response['data_role'], $data_row);
    }
    $response = json_encode($response);
    $data = json_decode($response, true);
    $result_modul = $result_modul->num_rows;
}else{
    $response ="tidak ada data";
    $result_modul ='';
}

    echo'
    <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
            <div class="scrollbar-inner">
            <!-- Brand -->
            <div class="sidenav-header d-flex align-items-center">
                <a class="navbar-brand" href="./">
                UPY Absensi
                </a>
                <div class="ml-auto">
                <!-- Sidenav toggler -->
                <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
                    <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    <i class="sidenav-toggler-line"></i>
                    </div>
                </div>
                </div>
            </div>
            <div class="navbar-inner">
                <!-- Collapse -->
                <div class="collapse navbar-collapse" id="sidenav-collapse-main">
                <!-- Nav items -->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="./home">
                        <i class="ni ni-shop text-primary"></i>
                            <span class="nav-link-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                      

                    </li>';

                foreach ($data['data_role'] as $row) {
                    if($row['modul_id']==1){
                    echo'
                    <li class="nav-item">
                        <a class="nav-link" href="#navbar-maps" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-maps">
                            <i class="fas fa-file-alt text-primary"></i>
                            <span class="nav-link-text">Artikel</span>
                        </a>
                        <div class="collapse" id="navbar-maps">
                            <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="./artikel" class="nav-link">Artikel</a>
                            </li>
                            <li class="nav-item">
                                <a href="./kategori" class="nav-link">Kategori</a>
                            </li>
                            </ul>
                        </div>
                    </li>';
                    }
                }
                echo'
                    <li class="nav-item">
                    <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
                        <i class="ni ni-ungroup text-orange"></i>
                        <span class="nav-link-text">Master Data</span>
                    </a>

                    <div class="collapse" id="navbar-examples">
                        <ul class="nav nav-sm flex-column">';
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==2){
                            echo'
                            <li class="nav-item">
                                <a href="./user" class="nav-link">Pegawai/User</a>
                            </li>';
                            }
                        }
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==3){
                        echo'
                            <li class="nav-item">
                                <a href="./lokasi" class="nav-link">Lokasi</a>
                            </li>';
                            }
                        }
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==4){
                            echo'
                            <li class="nav-item">
                                <a href="./jam-kerja" class="nav-link">Jam Kerja</a>
                            </li>';
                            }
                        }
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==5){
                            echo'
                            <li class="nav-item">
                                <a href="./posisi" class="nav-link">Posisi</a>
                            </li>';
                            }
                        }
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==15){
                            echo'
                            <li class="nav-item">
                                <a href="./hak-cuti" class="nav-link">Hak Cuti</a>
                            </li>';
                            }
                        }
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==6){
                            echo'
                            <li class="nav-item">
                                <a href="./libur" class="nav-link">Libur</a>
                            </li>';
                            }
                        }
                       
                    echo'
                        </ul>
                    </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="#izin" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="aben-mengajar">
                        <i class="ni ni ni-single-copy-04 text-success"></i>
                            <span class="nav-link-text">Izin & Cuti</span>
                        </a>

                        <div class="collapse" id="izin">
                            <ul class="nav nav-sm flex-column">';
                            foreach ($data['data_role'] as $row) {
                                if($row['modul_id']==8){
                                echo'
                                <li class="nav-item">
                                    <a href="./izin" class="nav-link">Izin</a>
                                </li>';
                                }
                            }
                            foreach ($data['data_role'] as $row) {
                                if($row['modul_id']==9){
                                echo'
                                <li class="nav-item">
                                    <a href="./cuti" class="nav-link">Cuti</a>
                                </li>';
                                }
                            }
                            echo'
                            </ul>
                        </div>
                    </li>';

                foreach ($data['data_role'] as $row) {
                    if($row['modul_id']==10){
                    echo'
                    <li class="nav-item">
                    <a class="nav-link" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
                        <i class="fas fa-print  text-default"></i>
                        
                        <span class="nav-link-text">Laporan</span>
                    </a>
                    <div class="collapse" id="navbar-tables">
                        <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="./laporan-pegawai" class="nav-link">Laporan Per Bulan</a>
                        </li>
                        </ul>
                    </div>
                    </li>';
                    }
                }

                echo'
                    <li class="nav-item">
                    <a class="nav-link" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
                        <i class="ni ni-settings-gear-65 text-pink"></i>
                        <span class="nav-link-text">Pengaturan Web</span>
                    </a>
                    <div class="collapse" id="navbar-forms">
                        <ul class="nav nav-sm flex-column">';
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==11){
                            echo'
                            <li class="nav-item">
                                <a href="./pengaturan" class="nav-link">Pengaturan</a>
                            </li>';
                            }
                        }
                        foreach ($data['data_role'] as $row) {
                            if($row['modul_id']==12){
                            echo'
                            <li class="nav-item">
                                <a href="./slider" class="nav-link">Slider</a>
                            </li>';
                            }
                        }
                        echo'
                        </ul>
                    </div>
                    </li>';
               
                foreach ($data['data_role'] as $row) {
                    if($row['modul_id']==13){
                    echo'
                    <li class="nav-item">
                        <a class="nav-link" href="./admin">
                            <i class="fas fa-user text-green"></i>
                            <span class="nav-link-text">Admin</span>
                        </a>
                    </li>';
                    }
                }
                    
            echo'
                </ul>
                <!-- Divider -->
                <hr class="my-3">
                <!-- Heading -->
                
                <!-- Navigation -->
                <ul class="navbar-nav mb-md-3">
                <li class="nav-item">
                    <a class="nav-link" href="./logout">
                        <i class="fas fa-sign-out-alt text-danger"></i>
                        <span class="nav-link-text">Logout</span>
                    </a>
                </li>
                </ul>
                </div>
            </div>
            </div>
        </nav>';