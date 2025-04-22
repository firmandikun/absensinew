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

function LoadDataKunjungan(){
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $(".load-kunjungan").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading..</p></div>');
    $(".load-kunjungan").load("./module/kunjungan/sw-proses.php?action=data-kunjungan&mulai="+mulai+"&selesai="+selesai+"");
}
LoadDataKunjungan();

/** Pencarian */
$('.search').change(function(){
    LoadDataKunjungan();
})



/** Loadmore Data kunjungan */
$(document).on('click','.load-more',function(){
    var id = $(this).attr("data-id");
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $('.show_more').hide();
    $.ajax({
        type:'POST',
        url:'./module/kunjungan/sw-proses.php?action=data-kunjungan-load',
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
    $('.modal-title').html('Tambah Kunjungan');
    $(".form-add").trigger("reset");
    $('.id').val('');

    $('.file-upload-image').attr('src','./template/img/sw-small.jpg');
    $('.image-upload-wrap').show();
    $('.file-upload-content').hide();
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
            minlength: 6
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
    var dataURL = canvas.toDataURL();
    var foto = $('.foto').val();
    var lokasi = $('.lokasi').val();
    var keterangan = $('.keterangan').val();
    if(id==''){
        var action = './module/kunjungan/sw-proses.php?action=add';
    }else{
        var action = './module/kunjungan/sw-proses.php?action=update'
    }

        $.ajax({    
            type : 'POST',
            url  : action,
            data:{id:id,lokasi:lokasi,keterangan:keterangan,img:dataURL,foto:foto},
            cache: false,
            beforeSend: function() { 
                loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Kunjungan berhasil disimpan!', icon: 'success', timer: 2500,});
                    $(".form-add").trigger("reset");
                    $('.modal-add').modal('hide');
                    LoadDataKunjungan();
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
    $('.modal-title').html('Ubah kunjungan tanggal <span class="badge badge-primary">'+tanggal+'</span>');
    $.ajax({
        type: 'POST',
        url  : './module/kunjungan/sw-proses.php?action=get-data-update',
        data: {id:id},
        dataType:'json',
        success: function(response) {
            $('.id').val(response.kunjungan_id);
            $('.lokasi').val(response.lokasi);
            $('.keterangan').val(response.keterangan);
            //$('.files').val(response.files);
            $('.file-upload-image').attr('src','./sw-content/kunjungan/'+response.foto+'');
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


/** Open Kamera */
$(document).on('click', '.btn-kamera', function(){
    $('.modal-kamera').modal('show');
    cameraStarted();
    webcam.start()
    .then(result =>{
        cameraStarted();
        console.log("webcam started");
    })
    .catch(err => {
        displayError();
    });
});


$(document).on('click', '.btn-close-kamera', function(){
    $('.modal-kamera').modal('hide');
    webcam.stop()
});


const webcamElement = document.getElementById('webcam');
const canvasElement = document.getElementById('canvas');
const webcam = new Webcam(webcamElement, 'environment', canvasElement);

$('.md-modal').addClass('md-show');

console.log("webcam started");
$("#webcam-switch").change(function () {
    if(this.checked){
        $('.md-modal').addClass('md-show');
        webcam.start()
            .then(result =>{
            cameraStarted();
            console.log("webcam started");
            })
            .catch(err => {
                displayError();
            });
    }
    else {        
        cameraStopped();
        webcam.stop();
        console.log("webcam stopped");
        }        
 });

$('.cameraFlip').click(function() {
    webcam.flip();
    webcam.start();  
});


function displayError(err = ''){
    if(err!=''){
        $("#errorMsg").html(err);
    }
    $("#errorMsg").removeClass("d-none");
}

function cameraStarted(){
    $("#webcam-caption").html("on");
    $("#webcam-control").removeClass("webcam-off");
    $("#webcam-control").addClass("webcam-on");
    $(".webcam-container").removeClass("d-none");
    if( webcam.webcamList.length > 1){
        $(".cameraFlip").removeClass('d-none');
    }
    $("#wpfront-scroll-top-container").addClass("d-none");
    window.scrollTo(0, 0); 
    //$('body').css('overflow-y','hidden');
}

$(".take-photo").click(function () {
    beforeTakePhoto();
    let picture = webcam.snap(300,300);
    afterTakePhoto();
    var img = new Image();
    img.src = picture;

    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.beginPath();
    ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
    var imgData = canvas.toDataURL();
    //insert();
    $('.file-upload-image').attr('src', imgData);
    $('.foto').val(imgData);
    $('.modal-kamera').modal('hide');
    $('.image-upload-wrap').hide();
    $('.file-upload-content').show();
});





function beforeTakePhoto(){
    $('#webcam-control').addClass('d-none');
    $('.take-photo').addClass('d-none');
    $('.cameraFlip').addClass('d-none');
    $('.resume-camera').removeClass('d-none');
}

function afterTakePhoto(){
    webcam.stop();
    $('#canvas').removeClass('d-none');
    $('#reflay').removeClass('d-none');
}

function removeCapture(){
    $('#canvas').addClass('d-none');
    $('#reflay').addClass('d-none');
    $('#webcam-control').removeClass('d-none');
    $('#cameraControls').removeClass('d-none');
    $('.take-photo').removeClass('d-none');
    $('.resume-camera').addClass('d-none');
    $('.cameraFlip').removeClass('d-none');
    
}

$(".resume-camera").click(function () {
    webcam.stream()
    .then(facingMode =>{
        removeCapture();
    });
});

/** Delete kunjungan */
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
                url:'./module/kunjungan/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus!', icon: 'success', timer: 2500,});
                        LoadDataKunjungan();
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


/** Print Data kunjungan */
$('.btn-print').click(function (e) {
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    var url = "./print-kunjungan?mulai="+mulai+"&selesai="+selesai+"";
    window.open(url, '_blank');
});