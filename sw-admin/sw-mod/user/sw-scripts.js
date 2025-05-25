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


/** Module User/Pegawai */
loadData();
function loadData(){
    var modifikasi = $('.modifikasi').html();
    var hapus = $('.hapus').html();
    var table;
    $(document).ready(function() {
        //datatables
        table = $('.datatable-user').DataTable({
            "fnDrawCallback": function () {
                $('.open-popup-link').magnificPopup({
                type: 'image',
                removalDelay: 300,
                mainClass: 'mfp-fade',
                    gallery: {
                        enabled: true
                    },
                    zoom: {
                        enabled: true,
                        duration: 300,
                        easing: 'ease-in-out',
                        opener: function (openerElement) {
                            return openerElement.is('img') ? openerElement : openerElement.find('img');
                        }
                    }
                });
            },
            "processing": true, 
            "serverSide": false, 
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy" : true,
            "paging": true,
            "ssSorting" : [[0, 'desc']],
            "iDisplayLength": 25,
            "order": [],
            "aLengthMenu": [
                [25, 30, 50, -1],
                [25, 30, 50, "All"]
            ],
            language: {
              paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
              }
            },
            "ajax": {
                "url": "./sw-mod/user/sw-datatable.php",
                "type": "POST",
                "data": {
                    modifikasi: modifikasi,
                    hapus: hapus,
                },
            },
            "columns": [
                {"data": "no"}, 
                {"data": "nip"},
                {"data": "nama_lengkap"},
                {"data": "email"},
                {"data": "jenis_kelamin"},
                {"data": "posisi_nama", "render": function(data, type, row) {
                    return data ? strip_tags(data) : '-'; 
                }},
                {"data": "status"},
                {"data": "actions"}
            ],
            "columnDefs": [
                {"targets": [0], "orderable": false},
                {"targets": [5], "className": "text-left"},
                {"targets": [6,7], "className": "text-center"}
            ],
        });
    });
}

    $("body").on("click", ".datepicker", function(){
        $(this).datepicker({
          format: 'dd-mm-yyyy',
          autoclose:true
        });
        $(this).datepicker("show");
    });


    /** Tambah User/Pegawai */
    $('.password').keypress(function( e ) {
        if(e.which === 32) 
        return false;
    });

    $(".toggle-password").click(function() {
        $(this).toggleClass("fa-eye fa-eye-slash");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
          input.attr("type", "text");
        } else {
          input.attr("type", "password");
        }
    });


    $(".form-add").validate({
        // Specify validation rules
        rules: {
            field: {
                required: true
            },
            email: {
                required: true,
                email: true
            },

            telp: {
                required: true,
                number: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 15
            },
            alamat: {
                required: true,
                minlength: 10,
                maxlength: 150
            }
        },

        // Specify validation error messages
        messages: {
            field: {
                required: "Silahkan masukkan data sesuai inputan",
            },
          password: {
            required: "Please provide a password",
            minlength: "Password anda paling sedikit berisi 6 karakter"
          },
          email: {
            required: "Silahkan masukkan alamat email anda",
            email: "Email seharusnya dalam format: swidodo.com@gmail.com"
          },
        },
        // in the "action" attribute of the form when valid
        submitHandler: submitForm_Add
      });

    /* handle form submit */
    function submitForm_Add() { 
        var data = $(".form-add").serialize();
        $.ajax({    
            type : 'POST',
            url  : './sw-mod/user/sw-proses.php?action=add',
            data : data,
            cache: false,
            async: false,
            beforeSend: function() { 
                loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
                    $(".form-add").trigger("reset");
                    window.setTimeout(window.location.href = "./user",2500);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                }
            }
        });
            return false; 
    }


    /* ------ Update User/Pegawai ------- */
    $(".form-update").validate({
        // Specify validation rules
        rules: {
            field: {
                required: true
            },
            email: {
                required: true,
                email: true
            },

            telp: {
                required: true,
                number: true
            },

            alamat: {
                required: true,
                minlength: 10,
                maxlength: 150
            }
        },

        // Specify validation error messages
        messages: {
            field: {
                required: "Silahkan masukkan data sesuai inputan",
            },
          email: {
            required: "Silahkan masukkan alamat email anda",
            email: "Email seharusnya dalam format: swidodo.com@gmail.com"
          },
        },
        // in the "action" attribute of the form when valid
        submitHandler: submitForm_Update
      });

    /* handle form submit */
    function submitForm_Update() { 
        var data = $(".form-update").serialize();
        $.ajax({    
            type : 'POST',
            url  : './sw-mod/user/sw-proses.php?action=update',
            data : data,
            cache: false,
            async: false,
            beforeSend: function() { 
                loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
                    $(".form-add").trigger("reset");
                    //window.setTimeout(window.location.href = "./user",2500);
                    setTimeout(function(){history.back();}, 3000);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                }
            }
        });
            return false; 
    }

    /** ------- Forgot ---------- */
    $(document).on('click', '.btn-forgot', function(){ 
        var id = $(this).attr("data-id");
        var name = $(this).attr("data-name");
          swal({
            title: "Resset Password!",
            text: "Anda yakin ingin meresset password "+name+".?\r\nPassword baru: 123456",
            icon: "info",
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
                     url:'./sw-mod/user/sw-proses.php?action=forgot',
                     type:'POST',    
                     data:{id:id},  
                    success:function(data){ 
                        if (data == 'success') {
                            swal({title: 'Berhasil!', text: 'Password berhasil diresset.!', icon: 'success', timer: 2500,});
                            loadData();
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



    /* ------------- Set Active User --------------*/
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
            url: "./sw-mod/user/sw-proses.php?action=active",
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


$(document).on('click', '.btn-qrcode', function(){
    $('.modal-id-card').modal('show');
});


/** Dropdown */
$(".posisi").change(function(){
    var posisi = $(this).val(); 
        $.ajax({
            type: 'POST',
            url  : './sw-mod/user/sw-proses.php?action=dropdown',
            data: {posisi:posisi},
            cache: false,
            success: function(data){
            $(".pegawai").html(data);
          }
      });
});

/** Export ID card */

$(document).on('click', '.btn-export-id-card', function(){
    var posisi = $('.posisi').val(); 
    var pegawai = $('.pegawai').val(); 
    var url = "./sw-mod/user/sw-print.php?action=print&posisi="+posisi+"&pegawai="+pegawai+""; 
    window.open(url, '_blank');
});

/** Import User/Pegawai */
$(document).on('click', '.btn-import', function(){
    $('.modal-import').modal('show');
    $(".form-import").trigger("reset");
});

$('.form-import').submit(function (e) {
    e.preventDefault();
    loading();
        $.ajax({
            url:"./sw-mod/user/sw-proses.php?action=import",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { 
              loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
                    $(".form-import").trigger("reset");
                    $('.modal-import').modal('hide');
                    loadData();
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                }
  
            },
            complete: function () {
                
            },
        });
  }); 


/** Update Avatar */
$(document).on('change','.foto',function(){
    var file_data = $('.foto').prop('files')[0];  
    var image_name = file_data.name;
    var image_extension = image_name.split('.').pop().toLowerCase();
  
      if(jQuery.inArray(image_extension,['gif','jpg','jpeg','png']) == -1){
          swal({title: 'Oops!', text: 'File yang di unggah tidak sesuai dengan format, File harus jpg, jpeg, gif, png.!', icon: 'error', timer: 2500,});
      }
  
    var form_data = new FormData();
    form_data.append("avatar",file_data);
    var id = $('.id').val();
    $.ajax({
      url:'./sw-mod/user/sw-proses.php?action=avatar&id='+id+'',
      method:'POST',
      data:form_data,
      contentType:false,
      cache:false,
      processData:false,
      success:function(data){
            if (data == 'success') {
                swal({title: 'Berhasil!', text: 'Avatar berhasil disimpan.!', icon: 'success', timer: 1500,});
               setTimeout(function(){location.reload(); }, 1500);
               $('.foto').val('');
            } else {
                swal({title: 'Oops!', text: data, icon: 'error'});
                $('.foto').val('');
            }
      }
    });
  });

/** Hapus data User/pegawai */
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
    var name = $(this).attr("data-name");
      swal({
        text: "Anda yakin ingin menghapus user "+name+".?",
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
                 url:'./sw-mod/user/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        loadData();
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
