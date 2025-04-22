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


/** Module  */
loadData();
function loadData(){
    var lokasi = $('.lokasi').val();
    var posisi = $('.posisi').val();
    var tanggal = $('.tanggal').val();
    $.ajax({
        type: 'POST',
        url  : './sw-mod/laporan-hari-ini/sw-proses.php?action=filtering',
        data: {lokasi:lokasi,posisi:posisi,tanggal:tanggal},
        cache: false,
        success: function(data){
            $(".load-data").html(data);
        }
    });
}


$(document).on('click', '.btn-pagination', function(){ 
    var lokasi = $('.lokasi').val();
    var posisi = $('.posisi').val();
    var tanggal = $('.tanggal').val();
    var halaman = $(this).attr("data-id");
 
    $.ajax({
        url: './sw-mod/laporan-hari-ini/sw-proses.php?action=filtering&halaman='+halaman+'',
        method:"POST",
        data: {lokasi:lokasi,posisi:posisi,tanggal:tanggal},
        dataType:"text",
        cache: false,
        async: false,
        success: function (data) {
            $('.load-data').html(data);
        },
    });
});



/** Dropdown */
$(".posisi").change(function(){
    loadData();
});

$(".lokasi").change(function(){
    loadData();
});


$(".tanggal").change(function(){
    loadData();
});


/** Print */
$(document).on('click', '.btn-print', function(){
    var tipe = $(this).attr("data-tipe");
    var lokasi = $('.lokasi').val();
    var posisi = $('.posisi').val();
    var tanggal  = $('.tanggal').val();
    var url = "./sw-mod/laporan-hari-ini/sw-print.php?action=print&lokasi="+lokasi+"&posisi="+posisi+"&tanggal="+tanggal+"&tipe="+tipe+""; 
    window.open(url, '_blank');
});

