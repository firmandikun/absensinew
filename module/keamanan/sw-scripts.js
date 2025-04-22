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


$('.password').keypress(function( e ) {
  if(e.which === 32) 
  return false;
});

$(".toggle-password").click(function() {
  $(this).toggleClass("fa-eye");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
    $('.toggle-password').removeClass('fa-eye-slash');
    $('.toggle-password').addClass('fa-eye');
  } else {
    input.attr("type", "password");
    $('.toggle-password').addClass('fa-eye-slash');
  }
});

$(".toggle-passwordb").click(function() {
  $(this).toggleClass("fa-eye");
  var input = $($(this).attr("toggle"));
  if (input.attr("type") == "password") {
    input.attr("type", "text");
    $('.toggle-passwordb').removeClass('fa-eye-slash');
    $('.toggle-passwordb').addClass('fa-eye');
  } else {
    input.attr("type", "password");
    $('.toggle-passwordb').addClass('fa-eye-slash');
  }
});


    /** Update Password  */
    $(".form-password").validate({
        // Specify validation rules
        rules: {
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
        submitHandler: submitForm_Password
      });

    /* handle form submit */
    function submitForm_Password() { 
        var data = $(".form-password").serialize();
        $.ajax({    
            type : 'POST',
            url  : './module/keamanan/sw-proses.php?action=update',
            data : data,
            cache: false,
            async: false,
            beforeSend: function() { 
                loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Password Anda berhasil disimpan.!', icon: 'success', timer: 1500,});
                    //$(".form-profile").trigger("reset");
                    setTimeout(function () {history.back();}, 1500);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error'});
                }
            }
        });
        return false; 
    }


    