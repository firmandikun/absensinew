<?php
if ($mod =='' OR !isset($_COOKIE['USER_KEY'])){
    header('location:../404');
    echo'404';
}else{
echo'
<main class="flex-shrink-0 main has-footer">  
    <div class="main-container">
        <div class="container">
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="subtitle mb-0">
                        <div class="avatar avatar-40 bg-default-light text-default rounded mr-2">
                            <span class="material-icons">person</span>
                        </div>
                        Profile
                    </h6>
                </div>
                <div class="card-body">
                <form class="form-profile" role="form" method="post" action="#" autocomplete="off">
                    <div class="form-group">
                        <label class="form-control-label">NIP</label>
                        <input type="text" class="form-control" name="nip" value="'.strip_tags($data_user['nip']).'" required>
                    </div>
                    <div class="form-group">
                        <label class="form-control-label">Nama Lengkap</label>
                        <input type="text" class="form-control" name="nama_lengkap" value="'.strip_tags($data_user['nama_lengkap']).'" required> 
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Tempat Lahir</label>
                        <input type="text" class="form-control" name="tempat_lahir" value="'.strip_tags($data_user['tempat_lahir']).'" required>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Tanggal Lahir</label>
                        <input type="text" class="form-control datepicker" name="tanggal_lahir" value="'.tanggal_ind($data_user['tanggal_lahir']).'" required> 
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">Jenis Kelamin</label>
                        <select class="form-control" name="jenis_kelamin" required>';
                            if($data_user['jenis_kelamin'] =='Laki-laki'){
                                echo'<option value="Laki-laki" selected>Laki-laki</option>';
                            }else{
                                echo'<option value="Laki-laki">Laki-laki</option>';
                            }
                            if($data_user['jenis_kelamin'] =='Perempuan'){
                                echo'<option value="Perempuan" selected>Perempuan</option>';
                            }else{
                                echo'<option value="Perempuan">Perempuan</option>';
                            }
                            echo'
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-control-label">No. WhatsApp</label>
                        <input type="number" class="form-control" name="telp" value="'.strip_tags($data_user['telp']).'" required>
                    </div>

                    <div class="form-group">
                    <label class="form-control-label">Penempatan</label>
                    <select class="form-control" name="lokasi" required>
                        <option value="">==Pilih Lokasi Kankor==</option>';
                        $query_lokasi = "SELECT lokasi_id,lokasi_nama FROM lokasi  ORDER BY lokasi_nama ASC";
                        $result_lokasi = $connection->query($query_lokasi);
                        while ($data_lokasi = $result_lokasi->fetch_assoc()){
                            if($data_user['lokasi_id'] == $data_lokasi['lokasi_id']){
                            echo'
                            <option value="'.$data_lokasi['lokasi_id'].'" selected>'.strip_tags($data_lokasi['lokasi_nama']).'</option>';
                            }else{
                            echo'
                            <option value="'.$data_lokasi['lokasi_id'].'">'.strip_tags($data_lokasi['lokasi_nama']).'</option>';
                            }
                        }
                        echo'
                        </select>
                    </div>

                    <div class="form-group">
                  <label class="form-control-label">Posisi</label>
                    <select class="form-control" name="posisi" required>
                        <option value="">==Pilih Posisi/Jabatan==</option>';
                        $query_posisi = "SELECT posisi_id,posisi_nama FROM posisi ORDER BY posisi_nama ASC";
                        $result_posisi = $connection->query($query_posisi);
                        if($result_posisi->num_rows > 0){
                        while ($data_posisi = $result_posisi->fetch_assoc()){
                            if($data_user['posisi_id']== $data_posisi['posisi_id']){
                            echo'
                            <option value="'.$data_posisi['posisi_id'].'" selected>'.strip_tags($data_posisi['posisi_nama']).'</option>';
                            }else{
                            echo'
                            <option value="'.$data_posisi['posisi_id'].'">'.strip_tags($data_posisi['posisi_nama']).'</option>';
                            }
                        }
                        }
                        echo'
                    </select>
                </div>


                <div class="form-group">
                  <label class="form-control-label">Alamat Lengkap</label>
                  <textarea class="form-control" name="alamat" rows="3" required>'.strip_tags($data_user['alamat']).'</textarea>
                </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-block btn-default rounded btn-save btn-profile">Simpan</button>
                </div>
                </form>
            </div>
            
        </div>
    </div>
</main>';


}?>