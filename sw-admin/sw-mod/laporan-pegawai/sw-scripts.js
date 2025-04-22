'use strict';
function loading(){
    $('.btn-save').prop("disabled", true);
      // add spinner to button
      $('.btn-save').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
      );
     window.setTimeout(function () {
      $('.btn-save').prop("disabled", false);
      $('.btn-save').html('<i class="far fa-save"></i> Simpan'
      );
    }, 2000);
}


$('.timepicker').timepicker({
    showInputs: false,
    showMeridian: false,
    use24hours: true,
    format :'HH:mm'
});

$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});



/** Dropdown */
$(".lokasi").change(function(){
    var lokasi = $(this).val(); 
        $.ajax({
            type: 'POST',
            url  : './sw-mod/laporan-pegawai/sw-proses.php?action=dropdown',
            data: {lokasi:lokasi},
            cache: false,
            success: function(data){
            $(".pegawai").html(data);
          }
      });
});



$(document).on('click', '.btn-filter', function(){
    var pegawai = $('.pegawai').val();
    var bulan = $('.bulan').val();
    var tahun = $('.tahun').val();

    if(pegawai == '' || bulan == '' || tahun == ''){
        swal({title: 'Oops!', text: 'Silahkan pilih filter datanya', icon: 'error', timer: 1500,});
    }else{
        $.ajax({
            type: 'POST',
            url  : './sw-mod/laporan-pegawai/sw-proses.php?action=filtering',
            data: {pegawai:pegawai,bulan:bulan,tahun:tahun},
            cache: false,
            success: function(data){
                $(".load-data").html(data);
            }
        });
    }  
});



/** Print */
$(document).on('click', '.btn-print', function(){
    var tipe = $(this).attr("data-tipe");
    var pegawai = $('.pegawai').val();
    var bulan  = $('.bulan').val();
    var tahun  = $('.tahun').val();
    var url = "./sw-mod/laporan-pegawai/sw-print.php?action=print&pegawai="+pegawai+"&bulan="+bulan+"&tahun="+tahun+"&tipe="+tipe+""; 
    window.open(url, '_blank');
});

