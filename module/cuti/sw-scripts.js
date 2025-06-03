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

function confirmDelete(id, name) {
    swal({
        text: "Anda yakin ingin menghapus user " + name + "?",
        icon: "warning",
        buttons: {
            cancel: true,
            confirm: true,
        },
        dangerMode: true,
    }).then((value) => {
        if (value) {
            loading();
            $.ajax({  
                url: './sw-mod/user/sw-proses.php?action=delete',
                type: 'POST',    
                data: {id: id},  
                success: function(data) { 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus!', icon: 'success', timer: 2500});
                        $('.datatable-user').DataTable().ajax.reload();
                    } else {
                        swal({title: 'Gagal!', text: data, icon: 'error', timer: 2500});
                    }
                }  
            });  
        }
    });
}


/** Upload Drag and Drop */
function readURL(input) {
    if (input.files && input.files[0]) {
        var file = input.files[0];
        $('.image-upload-wrap').hide();
        $('.file-upload-content').show();

        if(file.type === 'application/pdf') {
            // Handle PDF file
            $('.image-preview').hide();
            $('.pdf-preview').show();
            $('.pdf-filename').html('<strong>' + file.name + '</strong>');
            $('.file-upload-image').attr('src', ''); // Clear image
        } else {
            // Handle image file
            $('.pdf-preview').hide();
            $('.image-preview').show();
            var reader = new FileReader();
            reader.onload = function(e) {
                $('.file-upload-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    }
}

function removeUpload() {
    $('.file-upload-input').val('');
    $('.image-preview').hide();
    $('.pdf-preview').hide();
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
}
  $('.image-upload-wrap').bind('dragover', function () {
      $('.image-upload-wrap').addClass('image-dropping');
  });
      $('.image-upload-wrap').bind('dragleave', function () {
      $('.image-upload-wrap').removeClass('image-dropping');
  });
  

$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});


function LoadDatacuty(){
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $(".load-cuty").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading..</p></div>');
    $(".load-cuty").load("./module/cuti/sw-proses.php?action=data-cuti&mulai="+mulai+"&selesai="+selesai+"");
}
LoadDatacuty();

/** Pencarian */
$('.search').change(function(){
    LoadDatacuty();
})

/** Loadmore Data Cuty */
$(document).on('click','.load-more',function(){
    var id = $(this).attr("data-id");
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $('.show_more').hide();
    $.ajax({
        type:'POST',
        url:'./module/cuti/sw-proses.php?action=data-cuti-load',
        data:{id:id,mulai,mulai,selesai,selesai},
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
    $('.modal-title').html('Tambah Cuti baru');
    $(".form-add").trigger("reset");
});

$(".form-add").validate({
    // Specify validation rules
    rules: {
        field: {
            required: true
        },

        files: {
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
        field: {
            required: "Silahkan masukkan data sesuai inputan",
        },

    },
    // in the "action" attribute of the form when valid
    submitHandler: submitForm_Add
  });

/* handle form submit */
function submitForm_Add() { 
    var id = $('.id').val();
    if(id==''){
        var action = './module/cuti/sw-proses.php?action=add';
    }else{
        var action = './module/cuti/sw-proses.php?action=update'
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
                swal({title: 'Berhasil!', text: 'Permohonan Cuti berhasil disimpan!', icon: 'success', timer: 2500,});
                $(".form-add").trigger("reset");
                $('.modal-add').modal('hide');
                LoadDatacuty();
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
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
    $('.modal-title').html('Ubah Cuti tanggal <span class="badge badge-primary">'+tanggal+'</span>');
    $.ajax({
        type: 'POST',
        url  : './module/cuti/sw-proses.php?action=get-data-update',
        data: {id:id},
        dataType:'json',
        success: function(response) {
            $('.id').val(response.cuti_id);
            $('.jenis').val(response.jenis);
            $('.tanggal-mulai').val(response.tanggal_mulai);
            $('.tanggal-selesai').val(response.tanggal_selesai);
            $('.keterangan').val(response.keterangan);

            $('.file-upload-image').attr('src','./sw-content/cuti/'+response.files+'');
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

/** Delete cuti */
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
                url:'./module/cuti/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        LoadDatacuty();
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


/** Print Data Cuti */
$('.btn-print').click(function (e) {
    var search       = $('.search').val();
    var url = "./print-cuti?search="+search+"";
    window.open(url, '_blank');
});

