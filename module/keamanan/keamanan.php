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
                        <div class="avatar avatar-40 bg-default-light text-default rounded mr-2"><span class="material-icons vm">lock</span></div>
                        Ganti Password
                    </h6>
                </div>
                <div class="card-body">
                    <form class="form-password" role="form" method="post" action="#" autocomplete="off">
                        <div class="form-group">
                            <label class="form-control-label">Email</label>
                            <input type="email" class="form-control" name="email" value="'.strip_tags($data_user['email']).'" required>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Password lama</label>
                            <div class="input-group">
                            <input type="password" class="form-control password" id="password-field" name="password_lama" style="min-width:50%" required>
                                <div class="input-group-append">
                                <span class="input-group-text"><span toggle="#password-field" class="far fa-eye-slash toggle-password"></span></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-control-label">Password baru</label>
                            <div class="input-group">
                            <input type="password" class="form-control password" id="password-baru" name="password_baru" style="min-width:50%" required>
                                <div class="input-group-append">
                                <span class="input-group-text"><span toggle="#password-baru" class="far fa-eye-slash toggle-passwordb"></span></span>
                                </div>
                            </div>
                        </div>

                    
                        <div class="card-footer">
                            <button type="submit" class="btn btn-block btn-default rounded btn-save btn-profile">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>
</main>';


}?>