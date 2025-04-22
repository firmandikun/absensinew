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

function LoadDataOvertime(){
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $(".load-overtime").html('<div class="text-center"><div class="spinner-border" role="status"></div><p>Loading..</p></div>');
    $(".load-overtime").load("./module/overtime/sw-proses.php?action=data-overtime&mulai="+mulai+"&selesai="+selesai+"");
}
LoadDataOvertime();

/** Pencarian */
$('.search').change(function(){
    LoadDataOvertime();
})

/** Loadmore Data Overtime */
$(document).on('click','.load-more',function(){
    var id = $(this).attr("data-id");
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    $('.show_more').hide();
    $.ajax({
        type:'POST',
        url:'./module/overtime/sw-proses.php?action=data-overtime-load',
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

/**  Overtime Checkin */
navigator.geolocation.getCurrentPosition(function(location) {
    var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
    console.log(latitude);
    
    $(document).on('click', '.btn-checkin', function(){
        swal({
            text: 'Keterengan',
            content: "input",
            button: {
            text: "Check In",
            },
        })
      .then((value) => {
        if(value) {
            $.ajax({  
                url:'./module/overtime/sw-proses.php?action=check-in',
                type:'POST',    
                data:{latitude:latitude,keterangan:value},  
                success: function (data) {
                    const myArray = data.split("/");
                    var results = myArray[0];
                    var results2 = myArray[1];
                    if (results=='success') {
                        swal({title: 'Berhasil!', text:results2, icon: 'success', timer: 2000,});
                        LoadDataOvertime();
                        $('.btn-checkin').addClass(".btn-outline-secondary");
                        $('.btn-checkin').removeClass(".btn-primary");
                        $('.btn-checkin').prop('disabled', true);
                    } else {
                        swal({title: 'Oops!', text:data, icon: 'error',timer: 2000,});
                    }
                }
           });  
        } else{  
        return false;   
        }
      })
    });


    $(document).on('click', '.btn-checout', function(){
        $.ajax({  
            url:'./module/overtime/sw-proses.php?action=check-out',
            type:'POST',    
            data:{latitude:latitude},  
            success: function (data) {
                const myArray = data.split("/");
                var results = myArray[0];
                var results2 = myArray[1];
                if (results=='success') {
                    swal({title: 'Berhasil!', text:results2, icon: 'success', timer: 2000,});
                    LoadDataOvertime();
                    $('.btn-checout').addClass(".btn-outline-secondary");
                    $('.btn-checout').removeClass(".btn-primary");
                    $('.btn-checout').prop('disabled', true);
                } else {
                    swal({title: 'Oops!', text:data, icon: 'error',timer: 2000,});
                }
            }
        });  
    });
      
});


/** Delete Overtime */
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
                url:'./module/overtime/sw-proses.php?action=delete',
                 type:'POST',    
                 data:{id:id},  
                success:function(data){ 
                    if (data == 'success') {
                        swal({title: 'Berhasil!', text: 'Data berhasil dihapus.!', icon: 'success', timer: 2500,});
                        LoadDataOvertime();
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


$('.btn-print').click(function (e) {
    var mulai = $('.mulai').val();
    var selesai = $('.selesai').val();
    var url = "./print-overtime?mulai="+mulai+"&selesai="+selesai+"";
    window.open(url, '_blank');
})
    