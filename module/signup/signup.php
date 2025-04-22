<?php
if ($mod ==''){
    header('location:../../404');
}else{
echo'
<!-- Begin page content -->
<main class="flex-shrink-0 main has-footer">
    <!-- Fixed navbar -->
    <header class="header">
        <div class="row">
            <div class="ml-auto col-auto align-self-center">
                <a href="./" class="text-white">
                    Sign in
                </a>
            </div>
        </div>
    </header>
    
    <form class="form-signup" role="form" method="post" action="javascript:;" autocomplete="off">
    <div class="container h-100 text-white">
        <div class="row h-100">
            <div class="col-12 align-self-center mb-4">
                <div class="row justify-content-center">
                    <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                        <div class="text-center">
                            <img src="./sw-content/'.$site_logo.'" class="img-responsive" height="40">
                        </div>

                        <h6 class="font-weight-normal mb-5 mt-3 text-center">Registrasi akun baru</h6>
                        <div class="form-group float-label">
                            <input type="text" class="form-control text-white" name="nama_lengkap" required>
                            <label class="form-control-label text-white">Nama Lengkap</label>
                        </div>
                        
                        <div class="form-group float-label">
                            <input class="form-control text-white" name="email" required>
                            <label class="form-control-label text-white">Email</label>
                        </div>
                        
                        <div class="form-group float-label position-relative">
                            <input type="password" id="password" class="form-control text-white password" name="password" required>
                            <label class="form-control-label text-white">Password</label>
                            <div class="input-group-append">
                                <span class="input-group-custom input-group-text">
                                    <span toggle="#password" class="fas fa-eye toggle-password">
                                    </span></span>
                            </div>
                        </div>

                        <div class="form-group float-label position-relative">
                            <input type="password" id="password-field" class="form-control text-white password" name="confirm_password" required>
                            <label class="form-control-label text-white pb-5">Confirm Password</label>
                            <div class="input-group-append">
                                <span class="input-group-custom input-group-text">
                                    <span toggle="#password-field" class="fas fa-eye toggle-password">
                                    </span></span>
                            </div>
                        </div>
             
                        <p class="text-right"><a href="forgot" class="text-white">Forgot Password?</a></p>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

        <!-- footer-->
        <div class="footer no-bg-shadow py-3">
            <div class="row justify-content-center">
                <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                    <button type="submit" class="btn btn-default rounded btn-block btn-sigup"><i class="far fa-paper-plane"></i> Registrasi</button>
                </div>
            </div>
        </div>
</form>';

}?>