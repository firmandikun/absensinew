'use strict';

function loading(){
    $('.btn-save').prop("disabled", true);
      // add spinner to button
      $('.btn-save').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
      );
     window.setTimeout(function () {
      $('.btn-save').prop("disabled", false);
      $('.btn-save').html('Simpan');
    }, 2000);
}


$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});

function LoadDataHistori(){
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $(".load-Histori").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading..</p></div>');
    $(".load-histori").load("./module/histori-absen/sw-proses.php?action=data-histori&mulai="+mulai+"&selesai="+selesai+"");
}

LoadDataHistori();

/** Pencarian */
$('.mulai').change(function(){
    LoadDataHistori();
});

$('.selesai').change(function(){
    LoadDataHistori();
})

/** Loadmore Data Absensi */
$(document).on('click','.load-more',function(){
    var id = $(this).attr("data-id");
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $('.show_more').hide();
    $.ajax({
        type:'POST',
        url:'./module/histori-absen/sw-proses.php?action=data-histori-load',
        data:{id:id,mulai:mulai,selesai:selesai},
        beforeSend:function(){
            $(".load-more").text("Loading...");
        },
        success:function(data){
            $('.show_more_main'+id).remove();
            $('.postList').append(data);
            $(".load-more").text("Show more");
        }
    });
});


$(document).on('click', '.btn-update', function(){
    var id = $(this).attr("data-id");
    var keterangan = $(this).attr("data-keterangan");
    var tanggal = $(this).attr("data-tanggal");
    $('.id').val(id);
    $('.keterangan').val(keterangan);
    $('.modal-absen').modal('show');
    $('.modal-title').html('Tambah Keterangan Absen <span class="badge badge-primary">'+tanggal+'</span>');
    //$(".form-add").trigger("reset");
});

$(document).on('click', '.btn-close', function(){
    $('.modal-absen').modal('hide');
    $(".form-absen").trigger("reset");
});



/** Update Keterangan Absen */
$(".form-absen").validate({
    // Specify validation rules
    rules: {
        field: {
            required: true
        },
        keterangan: {
            required: true,
            minlength: 10,
            maxlength: 150
        },
    },

    // Specify validation error messages
    messages: {
        field: {
            required: "Silahkan masukkan data sesuai inputan",
        },
        
    },
    // in the "action" attribute of the form when valid
    submitHandler: submitForm_absen
  });

/* handle form submit */
function submitForm_absen() { 
    var data = $(".form-absen").serialize();
    $.ajax({    
        type : 'POST',
        url  : './module/histori-absen/sw-proses.php?action=update',
        data : data,
        cache: false,
        async: false,
        beforeSend: function() { 
            loading();
        },
        success: function (data) {
            if (data == 'success') {
                swal({title: 'Berhasil!', text: 'Keterangan Absen berhasil disimpan.!', icon: 'success', timer: 1500,});
                LoadDataHistori();
                $('.modal-absen').modal('hide');
                $(".form-absen").trigger("reset");
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 1500,});
            }
        }
    });
    return false; 
}



$(document).on('click', '.btn-view', function(){
    var id = $(this).attr("data-id");
    var tanggal = $(this).attr("data-tanggal");
    $('.modal-details').modal('show');
    $('.details-title').html('Detail Absensi <span class="badge badge-primary">'+tanggal+'</span>');
    $.ajax({    
        type : 'POST',
        url  : './module/histori-absen/sw-proses.php?action=details',
        data : {id :id},
        cache: false,
        async: false,
        success: function (data) {
            $('.result-details').html(data);
        }
    });
});


$(document).on('click', '.btn-map', function(){
    var map = $(this).attr("data-map");
    var title = $(this).attr("data-title");
    $('.modal-map').modal('show');
    $('.title-map').html('Lokasi Absensi '+title+'');
    document.getElementById("iframe-map").innerHTML ='<iframe src="./module/histori-absen/sw-proses.php?action=map&lokasi='+map+'"  frameborder="0" width="100%" height="400px" marginwidth="0" marginheight="0" scrolling="no">';
});



/** Print Data Kehadiran */
$('.btn-print').click(function (e) {
    var from    = $('.mulai').val();
    var to      = $('.selesai').val();
    var url     = "./print-absensi?from="+from+"&to="+to;
    window.open(url, '_blank');
});
    