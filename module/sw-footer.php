<?php if(empty($connection)){
  header('location:./404');
} else {

if(isset($_COOKIE['USER_KEY'])){
if($mod =='absen-in' OR $mod =='absen-out'){}
    else{
    echo'
    <!-- footer-->
    <div class="footer">
    <div class="appBottomMenu">
        <a href="'.$base_url.'" class="item">
            <div class="col">
                <span class="material-icons">holiday_village</span>
                <strong>Home</strong>
            </div>
        </a>';

        if(!$result_absen > 0){
        echo'
        <a href="'.$base_url.'absen-in" class="item">
            <div class="col">
                <div class="action-button large">
                    <span class="material-icons">camera</span>
                </div>
            </div>
        </a>';
        }else{
            foreach ($data_absen['data_absensi'] as $row_absensi) {
                if($row_absensi['absen_out']=='00:00:00'){
                echo'
                <a href="'.$base_url.'absen-out" class="item">
                    <div class="col">
                        <div class="action-button large">
                            <span class="material-icons">camera</span>
                        </div>
                    </div>
                </a>'; 
                }else{
                echo'
                <a href="javascript:void(0);" class="item">
                    <div class="col">
                        <div class="action-button large">
                            <span class="material-icons">camera</span>
                        </div>
                    </div>
                </a>';

                }
            }
        }

        echo'
        <a href="'.$base_url.'setting" class="item">
            <div class="col">
                <span class="material-icons">account_circle</span>
                <strong>Akun</strong>
            </div>
        </a>
    </div>
    </div>';
    }
}

echo'
<!-- color settings style switcher -->
    <div class="color-picker">
        <div class="row">
            <div class="col text-left">
                <div class="selectoption">
                    <input type="checkbox" id="darklayout" name="darkmode">
                    <label for="darklayout">Dark</label>
                </div>
                <div class="selectoption mb-0">
                    <input type="checkbox" id="rtllayout" name="layoutrtl">
                    <label for="rtllayout">RTL</label>
                </div>
            </div>
            <div class="col-auto">
                <button class="btn btn-link text-secondary btn-round colorsettings2"><span class="material-icons">close</span></button>
            </div>
        </div>

        <hr class="mt-2">

        <div class="colorselect">
            <input type="radio" id="templatecolor1" name="sidebarcolorselect">
            <label for="templatecolor1" class="bg-dark-blue" data-title="app"></label>
        </div>

        
       
        <div class="colorselect">
            <input type="radio" id="templatecolor2" name="sidebarcolorselect">
            <label for="templatecolor2" class="bg-dark-purple" data-title="dark-purple"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor4" name="sidebarcolorselect">
            <label for="templatecolor4" class="bg-dark-gray" data-title="dark-gray"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor6" name="sidebarcolorselect">
            <label for="templatecolor6" class="bg-dark-brown" data-title="dark-brown"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor3" name="sidebarcolorselect">
            <label for="templatecolor3" class="bg-maroon" data-title="maroon"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor5" name="sidebarcolorselect">
            <label for="templatecolor5" class="bg-dark-pink" data-title="dark-pink"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor8" name="sidebarcolorselect">
            <label for="templatecolor8" class="bg-red" data-title="red"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor13" name="sidebarcolorselect">
            <label for="templatecolor13" class="bg-amber" data-title="amber"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor7" name="sidebarcolorselect">
            <label for="templatecolor7" class="bg-dark-green" data-title="dark-green"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor11" name="sidebarcolorselect">
            <label for="templatecolor11" class="bg-teal" data-title="teal"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor12" name="sidebarcolorselect">
            <label for="templatecolor12" class="bg-skyblue" data-title="skyblue"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor10" name="sidebarcolorselect">
            <label for="templatecolor10" class="bg-blue" data-title="blue"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor9" name="sidebarcolorselect">
            <label for="templatecolor9" class="bg-purple" data-title="purple"></label>
        </div>
        <div class="colorselect">
            <input type="radio" id="templatecolor14" name="sidebarcolorselect">
            <label for="templatecolor14" class="bg-gray" data-title="gray"></label>
        </div>
    </div>';
    if(!empty($_COOKIE['USER_KEY'])){
        require_once('module/chat/chat.php');
    }
    echo'
    <footer class="text-muted text-center d-none">
        <p>© 2024 - '.$year.' '.$site_name.' - Design By:
            <span class="credits">
                <a class="credits_a" id="mycredit" href="https://s-widodo.com"  target="_blank">S-widodo.com</a>
            </span>
        </p>
    </footer>

    <!-- Required jquery and libraries -->
    <script src="'.$base_url.'template/js/jquery-3.3.1.min.js"></script>
    <script src="'.$base_url.'template/js/popper.min.js"></script>
    <script src="'.$base_url.'template/vendor/bootstrap/js/bootstrap.min.js"></script>
    <script src="'.$base_url.'template/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="'.$base_url.'template/js/jquery.cookie.js"></script>
    <script src="'.$base_url.'template/vendor/swiper/js/swiper.min.js"></script>
    <!-- Customized jquery file  -->
    <script src="'.$base_url.'template/js/main.js"></script>
    <script src="'.$base_url.'template/js/color-scheme-demo.js"></script>
    <script src="'.$base_url.'template/js/jquery.validate.min.js"></script>
    <script src="'.$base_url.'template/js/sweetalert.min.js"></script>
    <script src="'.$base_url.'template/vendor/html5-qrcode/minified/html5-qrcode.min.js"></script>
    <script defer src="'.$base_url.'template/vendor/webcame/face-api.min.js"></script>
    <script src="'.$base_url.'template/vendor/emojionearea/emojionearea.min.js"></script>
    <script src="'.$base_url.'template/js/sw-chat.js"></script>';
    ob_end_flush();
    if($mod =='recognition' OR $mod=='absen-in' OR $mod=='absen-out' OR $mod=='kunjungan'){
    echo'
    <script src="'.$base_url.'template/vendor/webcame/webcam-easy.min.js"></script>';
    }
    echo'
    <!-- page level custom script -->
    <script src="'.$base_url.'template/js/app.js"></script>';
    if(file_exists('module/'.$mod.'/sw-scripts.js')){
    echo'
    <script src="module/'.$mod.'/sw-scripts.js"></script>';
    }
    if($mod == 'absen-in' OR $mod=='absen-out'){
        require_once("module/$mod/javascript.php");
    }
echo'
</body>

</html>';
}?>