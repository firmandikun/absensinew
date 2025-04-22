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


function LoadData(){
    $(".data-recognition").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading..</p></div>');
    $(".data-recognition").load("./module/recognition/sw-proses.php?action=data-recognition");
}
LoadData();



$(document).on('click', '.btn-add', function(){
    $('.modal-add').modal('show');
    $('.modal-title').html('Tambah wajah baru');
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


/** Kamera */
const webcamElement = document.getElementById('webcam');
const canvasElement = document.getElementById('canvas');
const webcam = new Webcam(webcamElement, 'user', canvasElement);

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
    shutter.play();
    loading();

    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    ctx.beginPath();
    ctx.arc(100, 100, 50, 1.5 * Math.PI, 0.5 * Math.PI, false);
    var imgData = canvas.toDataURL();
    insert();
});

var shutter = new Audio();
//shutter.autoplay = true;
shutter.src = navigator.userAgent.match(/Firefox/) ? 'template/vendor/webcame/audio/snap.wav' : 'template/vendor/webcame/audio/snap.wav';


function insert(){
    var dataURL = canvas.toDataURL();
    $.ajax({
        type: "POST",
        url: "./module/recognition/sw-proses.php?action=add",
        data:{img:dataURL},
            success: function (data) {
                if (data=='success') {
                    swal({title: 'Berhasil!', text:'Foto berhasil disimpan!', icon: 'success', timer: 2500,});
                    LoadData();
                    wcm.stopCamera();
                    $('.modal-add').modal('hide');
                } else {
                    swal({title: 'Oops!', text:data, icon: 'error', timer: 2500,});
                }
            }
    });
}


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


/** Delete */
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
                url:'./module/recognition/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        LoadData();
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