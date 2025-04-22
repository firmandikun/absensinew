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
            url  : './sw-mod/laporan-hari/sw-proses.php?action=dropdown',
            data: {lokasi:lokasi},
            cache: false,
            success: function(data){
            $(".pegawai").html(data);
          }
      });
});

$(".posisi").change(function(){
    var lokasi = $('.lokasi').val(); 
    var posisi = $(this).val(); 
        $.ajax({
            type: 'POST',
            url  : './sw-mod/laporan-hari/sw-proses.php?action=dropdown',
            data: {lokasi:lokasi,posisi:posisi},
            cache: false,
            success: function(data){
            $(".pegawai").html(data);
          }
      });
});


$(document).on('click', '.btn-filter', function(){
    loadData();
});
loadData();
function loadData() {
    var lokasi = $('.lokasi').val();
    var posisi  = $('.posisi').val();
    var pegawai = $('.pegawai').val();
    var bulan   = $('.bulan').val();
    var tahun   = $('.tahun').val();
    $.ajax({
        type: 'POST',
        url  : './sw-mod/laporan-hari/sw-proses.php?action=filtering',
        data: {lokasi:lokasi,posisi:posisi,pegawai:pegawai,bulan:bulan,tahun:tahun},
        cache: false,
        success: function(data){
            $(".load-data").html(data);
        }
    }); 
};


/** Pagination */

$(document).on('click', '.btn-pagination', function(){ 
    var lokasi = $('.lokasi').val();
    var posisi = $('.posisi').val();
    var pegawai = $('.pegawai').val();
    var bulan = $('.bulan').val();
    var tahun = $('.tahun').val();

    var month_d = new Array();
    month_d[0] = "Januari";
    month_d[1] = "Februari";
    month_d[2] = "Maret";
    month_d[3] = "April";
    month_d[4] = "Mei";
    month_d[5] = "Juni";
    month_d[6] = "Juli";
    month_d[7] = "Agustus";
    month_d[8] = "September";
    month_d[9] = "Oktober";
    month_d[10] = "November";
    month_d[11] = "Desember";
    var halaman = $(this).attr("data-id");
    var d     = new Date(bulan);
    var n     = month_d[d.getMonth()];
    $('.result-month').html(n);
    $.ajax({
        url: './sw-mod/laporan-hari/sw-proses.php?action=filtering&halaman='+halaman+'',
        method:"POST",
        data: {lokasi:lokasi,posisi:posisi,pegawai:pegawai,bulan:bulan,tahun:tahun},
        dataType:"text",
        cache: false,
        async: false,
        success: function (data) {
            $('.load-data').html(data);
        },
    });
});


/** Print */
$(document).on('click', '.btn-print', function(){
    var tipe        = $(this).attr("data-tipe");
    var lokasi      = $('.lokasi').val();
    var posisi      = $('.posisi').val();
    var pegawai     = $('.pegawai').val();
    var bulan       = $('.bulan').val();
    var tahun       = $('.tahun').val();
    var url         = "./sw-mod/laporan-hari/sw-print.php?action=print&lokasi="+lokasi+"&posisi="+posisi+"&pegawai="+pegawai+"&bulan="+bulan+"&tahun="+tahun+"&tipe="+tipe+""; 
    window.open(url, '_blank');
});


