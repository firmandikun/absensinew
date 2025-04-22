<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{

echo'
<main class="flex-shrink-0 main has-footer">
    <div class="main-container">

        <div class="container mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="form-group float-label position-relative mb-1">
                        <div class="bottom-right ">
                            <span class="btn btn-sm btn-link text-dark btn-40 rounded text-mute"><i class="material-icons">search</i></span>
                        </div>
                        <input type="text" class="form-control search">
                        <label class="form-control-label">Search</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="container mb-4">
            <div class="col">
                <h6 class="subtitle mb-4">Informasi</h6>
            </div>

            <div class="swiper-container categories2tab1 text-center mb-4 swiper-container-horizontal">
                <div class="swiper-wrapper">';
                    $query_kategori="SELECT title,seotitle FROM kategori ORDER BY title ASC";
                    $result_kategori = $connection->query($query_kategori);
                    while ($data_kategori=$result_kategori->fetch_assoc()){
                    echo'
                    <div class="swiper-slide">
                        <button class="btn btn-sm btn-outline-default rounded mr-2 btn-kategori" data-kategori="'.strip_tags($data_kategori['seotitle']).'">'.strip_tags(ucfirst($data_kategori['title'])).'
                        </button>
                    </div>';
                    }
            echo'
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination white-pagination text-left mb-3"></div>
                <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>

            <div class="load-blog row postList">
                
            </div>
        </div>
    </div>
</main>';
}?>