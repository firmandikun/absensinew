<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{
    if (isset($_GET['details'])){
        $details = mysqli_real_escape_string($connection,$_GET['details']);
        $blog = str_replace('-',' ',$details);
        $query_artikel ="SELECT * FROM artikel WHERE active='Y' AND artikel_id='$details'"; 
        $result_artikel = $connection->query($query_artikel);
    }

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="main-container">
        <div class="container mb-4">';
        if($result_artikel->num_rows > 0){
            $data_artikel = $result_artikel->fetch_assoc();
            $kategori = str_replace('-',' ',$data_artikel['kategori']);
            $kategori = ucfirst($kategori);

            $statistik = $data_artikel['statistik']+1;
            $update = "UPDATE artikel SET statistik='$statistik' WHERE artikel_id='$data_artikel[artikel_id]'";
            $connection->query($update);

            echo'
            <article class="blog-details">
                <h6>'.strip_tags($data_artikel['judul']).'</h6>
                <ul class="meta">
                    <li><i class="far fa-user"></i><a href="#">'.strip_tags($data_artikel['penerbit']).'</a></li>
                    <li><i class="far fa-calendar-alt"></i><a href="#">'.tgl_indo($data_artikel['date']).'</a></li>
                    <li><i class="fa fa-tags"></i><a href="#">'.$kategori.'</a></li>
                    <li><i class="fa fa-eye"></i><a href="#">'.$data_artikel['statistik'].' Pembaca</a></li>
                </ul>

                <div class="deskripsi">
                    '.$data_artikel['deskripsi'].'
                </div>
            </article>';
        }else{
            echo'
            <div class="col-12 col-md-6 col-lg-4 align-self-center text-center my-3 mx-auto">
                <div class="icon icon-120 bg-danger-light text-danger rounded-circle mb-3">
                    <i class="material-icons display-4">error_outline</i>
                </div>
                <h2 class="display-2">404</h2>
                <h5 class="text-secondary mb-4 text-uppercase">Page not found </h5>
                <p class="text-secondary">Halaman yang Anda cari tidak tersedia, silakan periksa ulang URL atau coba lagi nanti.</p>
                <br>
                <a href="'.$base_url.'" class="btn btn-default rounded">Go back to Home</a>
            </div>';
        }
        echo'   
        </div>
    </div>
</main>';
}?>