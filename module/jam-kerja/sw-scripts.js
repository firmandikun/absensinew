'use strict';
function loading(){
    $('.btn-save').prop("disabled", true);
      // add spinner to button
      $('.btn-save').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
      );
     window.setTimeout(function () {
      $('.btn-save').prop("disabled", false);
      $('.btn-save').html('Simpan'
      );
    }, 2000);
}




/* -------- Modal Add Jam Kerja*/
    $(document).on('click', '.btn-add', function(){
        $('.modal-add').modal('show');
        $('.modal-title').html('Tambah Jam Kerja');
        $(".form-add").trigger("reset");
    });


    $('.master-jam-kerja').on('change', function (e) {
        var id = $(e.target).val();
        $(".load-jam-kerja").load("module/jam-kerja/sw-proses.php?action=load-jam-kerja&id="+id+"");
    });

    function Loaddata(){
        $(".load-data").load("module/jam-kerja/sw-proses.php?action=load-data");
    }
    Loaddata();

    /** Login */
    $(".form-jam-kerja").validate({
        // Specify validation rules
        rules: {
            field: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: true,
                minlength: 6,
                maxlength: 15
            },
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
            email: "Email seharusnya dalam format: @gmail.com"
          },
        },
        // in the "action" attribute of the form when valid
        submitHandler: submitForm_Jamkerja
      });

    /* handle form submit */
    function submitForm_Jamkerja() { 
        var data = $(".form-jam-kerja").serialize();
        $.ajax({    
            type : 'POST',
            url  : './module/jam-kerja/sw-proses.php?action=add',
            data : data,
            cache: false,
            async: false,
            beforeSend: function() { 
                loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Jam kerja berhasil disimpan.!', icon: 'success', timer: 1500,});
                    $(".form-jam-kerja").trigger("reset");
                    //setTimeout(function () {history.back();}, 1500);
                    Loaddata();
                    $('.modal-add').modal('hide');
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                }
            }
        });
            return false; 
    }




    /** Set Aktifkan Jam kerja */
    $(document).on('click', '.btn-active-jam', function(){
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
            url: "./module/jam-kerja/sw-proses.php?action=active",
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

      
/** Hapus data  Jam kerja  */
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
    var name = $(this).attr("data-name");
      swal({
        text: "Anda yakin ingin menghapus Shift kerja "+name+".?",
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
                 url:'./module/jam-kerja/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 1500,});
                        Loaddata();
                    } else {
                        swal({title: 'Gagal!', text: data, icon: 'error'});
                        
                    }
                 }  
            });  
       } else{  
        return false;
    }  
});
});