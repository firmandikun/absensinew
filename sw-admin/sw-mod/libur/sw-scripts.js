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


$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});



loadLibur(1);
function loadLibur(id){
    $(".load-form").html('<div class="text-center"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <p>Loading data...</p></div>');
    $(".load-form").load("sw-mod/libur/sw-form.php?id="+id+"");
    
}



$(document).on('click', '.btn-active', function(){
    var id = $(this).attr("data-id");
    var active = $(".active"+id).attr("data-active");
    if(active == "Y"){
        var dataactive = "N";
    }else{
        var dataactive = "Y";
    }
     var dataString = 'id='+ id + '&active='+ dataactive;
    $.ajax({
        type: "POST",
        url: "./sw-mod/libur/sw-proses.php?action=active",
        data: dataString,
        success: function (data) {
            if(active == "Y"){
                $(".active"+id).attr("data-active","N");
            }else{
                $(".active"+id).attr("data-active","Y");
            }

          if (data == 'success') {
                console.log('Successfully set active');
            }else{
               console.log(data);
            }
        }
    });
  });

/* -------- MODAL ADD */
$(document).on('click', '.btn-add', function(){
    $('.modal-add').modal('show');
    $('.modal-title').html('Tambah Libur Nasional');
    $(".form-add").trigger("reset");
    $('.id').val('');
});


$(document).on('submit', '.form-add', function(e){ 
    e.preventDefault();
    $.ajax({
      url:"sw-mod/libur/sw-proses.php?action=add",
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      beforeSend: function() { 
          loading();
      },
      success: function (data) {
          if (data == 'success') {
              swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
              loadLibur(2);
              $('.modal-add').modal('hide');
          } else {
              swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
          }
      },
    });
});

    /**  Update kelas*/
    $(document).on('click', '.btn-update', function(){
        var id = $(this).attr("data-id");
        var tanggal = $(this).attr("data-tanggal");
        var keterangan = $(this).attr("data-keterangan");
        $('.id').val(id);
        $('.tanggal').val(tanggal);
        $('.keterangan').val(keterangan);

        $('.modal-add').modal('show');
        $('.modal-title').html('Update Libur '+tanggal+'');
        //$(".form-add").trigger("reset");
    });


/** Hapus data Posisi */
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
                 url:'./sw-mod/libur/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        loadLibur(2);
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
    