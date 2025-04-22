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
    
    <form class="form-forgot" role="form" method="post" action="javascript:;" autocomplete="off">
    <div class="container h-100 text-white">
        <div class="row h-100">
            <div class="col-12 align-self-center mb-4 mt-5">
                <div class="row justify-content-center">
                    <div class="col-11 col-sm-7 col-md-6 col-lg-5 col-xl-4">
                        <div class="text-center">
                            <img src="./sw-content/'.$site_logo.'" class="img-responsive" height="40">
                        </div>

                        <h6 class="font-weight-normal mb-5 mt-3 text-center">
                            Masukkan Email untuk resset password Anda
                        </h6>
                
                        
                        <div class="form-group float-label">
                            <input type="email" class="form-control text-white" name="email" required>
                            <label class="form-control-label text-white">Email</label>
                        </div>
             
                        <button type="submit" class="btn btn-default rounded btn-block btn-sigup mt-3"><i class="far fa-paper-plane"></i> Resset Password</button>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</main>

</form>';

}?>