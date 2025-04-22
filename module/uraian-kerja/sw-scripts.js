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

/*$(window).scroll(function(){
    if ($(this).scrollTop() > 50) {
        $('.btn-floating').addClass('active');
    } else {
        $('.btn-floating').removeClass('active');
    }
});*/


$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});



/** Upload Drag and Drop */
function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('.image-upload-wrap').hide();
        $('.file-upload-image').attr('src', e.target.result);
        $('.file-upload-content').show();
        //$('.image-title').html(input.files[0].name);
      };
      reader.readAsDataURL(input.files[0]);
    } else {
      removeUpload();
    }
  }

  function removeUpload() {
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
    $('.foto').val('');
    removeCapture();
  }
  $('.image-upload-wrap').bind('dragover', function () {
      $('.image-upload-wrap').addClass('image-dropping');
  });
      $('.image-upload-wrap').bind('dragleave', function () {
      $('.image-upload-wrap').removeClass('image-dropping');
  });


function LoadDatakerja(){
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $(".load-kerja").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading..</p></div>');
    $(".load-kerja").load("./module/uraian-kerja/sw-proses.php?action=data-uraian-kerja&mulai="+mulai+"&selesai="+selesai+"");
}
LoadDatakerja();

/** Pencarian */
$('.search').change(function(){
    LoadDatakerja();
})

/** Loadmore Data*/
$(document).on('click','.load-more',function(){
    var id = $(this).attr("data-id");
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $('.show_more').hide();
    $.ajax({
        type:'POST',
        url:'./module/uraian-kerja/sw-proses.php?action=data-uraian-kerja-load',
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


$(document).on('click', '.btn-add', function(){
    $('.modal-add').modal('show');
    $('.modal-title').html('Tambah laporan kerja');
    $(".form-add").trigger("reset");
    $(".id").val('');
});

$(".form-add").validate({
    // Specify validation rules
    rules: {

        nama: {
            required:true,
        },
        
        keterangan: {
            required: true,
            minlength: 6,
            maxlength: 150
        },
    },

    // Specify validation error messages
    messages: {
        nama: "Silakan masukkan Judul uraian kerja Anda",
        keterangan: "Silakan masukkan Keterangan uraian kerja Anda",
      },
    // in the "action" attribute of the form when valid
    submitHandler: submitForm_Add
  });

/* handle form submit */
function submitForm_Add() { 
    var id = $('.id').val();
    if(id==''){
        var action = './module/uraian-kerja/sw-proses.php?action=add';
    }else{
        var action = './module/uraian-kerja/sw-proses.php?action=update'
    }
    
    $.ajax({    
        type : 'POST',
        url  : action,
        data : new FormData($(".form-add")[0]),
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() { 
            loading();
        },
        success: function (data) {
            if (data == 'success') {
                swal({title: 'Berhasil!', text: 'Data berhasil disimpan!', icon: 'success', timer: 2500,});
                $(".form-add").trigger("reset");
                $('.modal-add').modal('hide');
                LoadDatakerja();
            } else {
                //swal({title: 'Oops!', text: data, icon: 'error',});
            }
        }
    });
        return false; 
}


/** Update */
$(document).on('click', '.btn-update', function(){
    var id = $(this).attr('data-id');
    var tanggal = $(this).attr('data-tanggal');
    $('.modal-add').modal('show');
    $('.modal-title').html('Ubah Uraian Kerja tanggal <span class="badge badge-primary">'+tanggal+'</span>');
    $.ajax({
        type: 'POST',
        url  : './module/uraian-kerja/sw-proses.php?action=get-data-update',
        data: {id:id},
        dataType:'json',
        success: function(response) {
            $('.id').val(response.uraian_kerja_id);
            $('.nama').val(response.nama);
            $('.tanggal').val(response.tanggal);
            $('.keterangan').val(response.keterangan);
            $('.file-upload-image').attr('src','./sw-content/laporan-kerja/'+response.files+'');
            $('.image-upload-wrap').hide();
            $('.file-upload-content').show();
   
        }, error: function(response){
           console.log(response.responseText);
        }
    });
});


$(document).on('click', '.btn-close', function(){
    $('.modal-add').modal('hide');
    $(".form-add").trigger("reset");
});

/** Delete uraian-kerja */
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
      swal({
        text: "Anda yakin ingin menghapus data ini?",
        icon: "warning",
          buttons: {
            cancel: true,
            confirm: true,
          },
        value: "yes",
      })

      .then((value) => {
        if(value) {
            loading();
            $.ajax({  
                url:'./module/uraian-kerja/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        LoadDatakerja();
                    } else {
                        swal({title: 'Gagal!', text: data, icon: 'error', timer:2500,});
                        
                    }
                 }  
            });  
       } else{  
        return false;
        }  
    });
}); 


/** Print Data */
$('.btn-print').click(function (e) {
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    var url         = "./print-uraian-kerja?mulai="+mulai+"&selesai="+selesai+"";
    window.open(url, '_blank');
});