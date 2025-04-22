/* On click password reset button */
$('.passwordResetBtn').click(function () {
  $('.forgot-show').show();
  $('.login-show').hide(); 
});

/* On click Loginbutton */
$('.loginBtn').click(function () {
  $('.forgot-show').hide();
  $('.login-show').show(); 
});

/* ----------- LOGIN ------------*/
$('.login').submit(function (e) {
    e.preventDefault();
    if($('.username').val()=='' && $('.password').val()==''){    
         swal({title:'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 2500,});
        return false;
        //loading();
    }
    else{
        //loading();
        $.ajax({
            url:"./sw-proses.php?action=login",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { 
             // loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Selamat datang.!', icon: 'success', timer: 1500,});
                    setTimeout(function() {window.location.href = "../"}, 2000);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2000,});
                }

            },
            complete: function () {
                
            },
        });
    }
});


/* ----------- Forgot ------------*/
$('.form-forgot').submit(function (e) {
    e.preventDefault();
    if($('.text').val()==''){    
         swal({title:'Oops!', text: 'Harap bidang inputan tidak boleh ada yang kosong.!', icon: 'error', timer: 2500,});
        return false;
    }
    else{
        $.ajax({
            url:"./sw-proses.php?action=forgot",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
      
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Selamat Password Anda berhasil kami Resset Ulang.!', icon: 'success', timer: 3500,});
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 3500,});
                }

            },
            complete: function () {
                
            },
        });
    }
});