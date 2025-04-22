<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{
    $query_tema ="SELECT foto FROM kartu_nama WHERE active='Y'";
    $result_tema = $connection->query($query_tema);
    $data_tema = $result_tema->fetch_assoc();
    $query_posisi = "SELECT posisi_nama FROM posisi WHERE posisi_id ='$data_user[posisi_id]'";
    $result_posisi = $connection->query($query_posisi);
    if($result_posisi->num_rows > 0){
        $data_posisi = $result_posisi->fetch_assoc();
        $posisi = strip_tags($data_posisi['posisi_nama']);
    }else{
        $posisi ='-';
    }
echo'
<main class="flex-shrink-0 main has-footer">
    <div class="main-container">

        <div class="container mb-4">
            <div class="row justify-content-md-center">
                <div class="col col-lg-3 text-center">
                    <div class="thema-id-card" style="background: url(./sw-content/tema/'.$data_tema['foto'].')">
                        <div class="logo">
                            <img src="./sw-content/'.$site_logo.'">
                        </div>

                        <div class="qrcode">
                            <img src="./sw-content/qrcode/'.seo_title($data_user['qrcode']).'.jpg">
                        </div>

                        <div class="description">
                            <p class="bold">'.strip_tags($data_user['nama_lengkap']).'</p>
                            <p>'.strip_tags($data_user['nip']).'</p>
                        </div>

                        <div class="avatar">';
                            if(file_exists('./sw-content/avatar/'.$data_user['avatar'].'')){
                                echo'<img src="data:image/gif;base64,'.base64_encode(file_get_contents('./sw-content/avatar/'.$data_user['avatar'].'')).'" height="40">';
                            }else{
                                echo'<img src="./sw-content/avatar/avatar.jpg" height="40">';
                            }
                        echo'
                        </div>
                        <div class="position">
                            <p>'.$posisi.'</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<div class="btn-floating">
    <button type="submit" class="btn btn-info btn-print"><span class="material-icons">print</span></button>
</div>';
}?>