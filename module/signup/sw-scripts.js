'use strict';
    function btn_sigup(){
        $('.btn-sigup').prop("disabled", true);
        // add spinner to button
        $('.btn-sigup').html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
        );
        window.setTimeout(function () {
        $('.btn-sigup').prop("disabled", false);
        $('.btn-sigup').html('Registrasi');
        }, 2000);
    }

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

      
    

    /** Registrasi */
    $(".form-signup").validate({
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
        submitHandler: submitForm_Sigup
      });

    /* handle form submit */
    function submitForm_Sigup() { 
        var data = $(".form-signup").serialize();
        $.ajax({    
            type : 'POST',
            url  : './module/signup/sw-proses.php?action=signup',
            data : data,
            cache: false,
            async: false,
            beforeSend: function() { 
                btn_sigup();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Registrasi akun baru berhasil.!', icon: 'success', timer: 2500,});
                    $(".form-signup").trigger("reset");
                    setTimeout("location.href = './';",2500);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                }
            }
        });
            return false; 
    }




    