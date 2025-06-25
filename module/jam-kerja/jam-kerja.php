<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{
echo'
<main class="flex-shrink-0 main has-footer">  
    <div class="main-container">
    <div class="container mb-4">
        <div class="row mb-3">
            <div class="col">
                <h6 class="subtitle mb-0 mt-2">Shift/Jam Kerja</h6>
            </div>
            <div class="col-auto">
                <button type="button" class="btn btn-default btn-sm btn-add">
                <span class="material-icons">add</span></button>
            </div>
        </div>
        <hr>

            <div class="alert alert-warning" role="alert">
                Silahkan buat jadwal/jam kerja baru, pilih sesuaikan dengan jadwal keja Anda.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>

        <div class="load-data">
        </div>
    </div>
</main>

<!-- Modals  -->
<div class="modal fade modal-add" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form class="form-jam-kerja" role="form" method="post" action="#" autocomplete="off">
                    <div class="form-group">
                        <label>Shift Jam kerja</label>
                        <select class="form-control master-jam-kerja" name="jam_kerja_master_id" required>
                            <option value="">Pilih Jam kerja</option>';
                            $query = "SELECT jam_kerja_master_id,nama FROM jam_kerja_master ORDER BY nama ASC";
                            $result = $connection->query($query);
                            while ($data = $result->fetch_assoc()){
                                echo'<option value="'.$data['jam_kerja_master_id'].'">'.strip_tags($data['nama']).'</option>';
                            }
                        echo'
                        </select>
                    </div>

                    <div class="form-group load-jam-kerja"></div>
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-default btn-save">Simpan</button>
                    </div>
                </form>
            </div>
       

        </div>
    </div>
</div>';


}?>