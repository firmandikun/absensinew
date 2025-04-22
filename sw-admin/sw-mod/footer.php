<?php if(empty($connection)){
	header('location:./');
} else {
$mod = "home";
$mod = htmlentities(@$_GET['mod']);
// Get number
function get_numbers() {
  for ($i = 1; $i <= 500; $i++) {yield $i;}
}
$result = get_numbers();
function convertkb($size){
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}
echo'
    <!-- Footer -->
      <footer class="footer pt-0">
        <div class="row align-items-center justify-content-lg-between">
          <div class="col-lg-12">
            <div class="copyright text-center text-lg-left text-muted d-none "  >
            <!-- Dilarang menghapus credits, hargai developmentnya -->
              &copy; App. Absensi v.5 '.$date.' | <a href="./" class="font-weight-bold ml-1" target="_blank">'.$site_name.'</a>
              <span class="credits d-none">
                <a class="credits_a" id="mycredit" href="https://s-widodo.com"  target="_blank">S-widodo.com</a>
              </span>
            </div>
          </div>

        </div>
      </footer>
    </div>
  </div>';
    require_once'./sw-mod/chat/chat.php';
  
    echo'
    <!-- Argon Scripts -->
    <script src="sw-assets/vendor/jquery/dist/jquery.min.js"></script>
    <script src="sw-assets/vendor/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="sw-assets/vendor/js-cookie/js.cookie.js"></script>
    <script src="sw-assets/vendor/jquery.scrollbar/jquery.scrollbar.min.js"></script>
    <script src="sw-assets/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js"></script>
    <!-- Optional JS -->
    <script src="sw-assets/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
    <script src="sw-assets/vendor/timepicker/bootstrap-timepicker.js"></script>
    <!-- Datatable -->
    <script src="sw-assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="sw-assets/vendor/datatables/dataTables.bootstrap4.min.js"></script>

    <script src="sw-assets/vendor/Magnific-Popup/jquery.magnific-popup.min.js"></script>
    <!-- Select -->
    <script src="sw-assets/vendor/select2/dist/js/select2.min.js"></script>
    <script src="sw-assets/js/jquery.validate.min.js"></script>
    <script src="sw-assets/js/sweetalert.min.js"></script>
    <!-- Argon JS -->
    <script src="sw-assets/js/argon.js?v=1.1.0"></script>
    <script src="sw-assets/js/demo.min.js"></script>
    <script src="../template/vendor/emojionearea/emojionearea.min.js"></script>
    <script src="sw-assets/js/sw-scripts.js"></script>';
    if($mod=='lokasi'){
      echo'
      <script src="./sw-assets/vendor/leatfet/leaflet.js"></script>
      <script src="./sw-assets/vendor/leatfet/L.Control.Locate.js"></script>';
    }
    if($mod=='artikel'){
    echo'
    <script src="sw-assets/vendor/tinymce/tinymce.min.js"></script>';
    }
    if(file_exists('sw-mod/'.$mod.'/sw-scripts.js')){
    echo'
    <script src="sw-mod/'.$mod.'/sw-scripts.js"></script>';
    }
echo'
</body>
</html>';}