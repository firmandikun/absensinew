
jQuery(function($) {
    setInterval(function() {
      var date = new Date(),
          time = date.toLocaleTimeString();
      $(".clock").html(time);
    }, 1000);
});



/** Scan Absensi Dengan mesin */
$('.qrcode').focus();
$('body').click(function(){
    $('.qrcode').focus();
 });


document.onload = function() {
   $('.qrcode').focus();
};

$(document).on('keyup', '.qrcode', function(){
    var qrcode     = $('.qrcode').val();
    panjang = qrcode.length;
    setTimeout(function(){
        if(panjang > 6){
            $('.button-submit').trigger('click');
            document.getElementById("myaudio").play();
        }
    }, 300);
 });



$(document).on('submit', '.form-absen', function(e){ 
    e.preventDefault();
    $.ajax({
      url: './sw-proses.php?action=absen',
      type: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      cache: false,
      async: false,
      success: function (response) {
            console.log(response);
            setTimeout(function(){
                $(".form-absen").trigger('reset');
                $('.qrcode').focus();
                $('.qrcode').val('');
            }, 500);

            var datajson = ''+response+'';
            let data = JSON.parse(datajson); 

            tampildata();
            function tampildata(){
                $('.nama-pegawai').html(data.nama_pegawai);
                $('.status-absen').html(data.status);
                $('.timestamp').html(data.timestamp);
                $('.avatar-absen').attr('src',''+data.avatar+'');

                window.setTimeout(function () {
                    $('.nama-pegawai').html('Nama Pegawai');
                    $('.status-absen').html('Status');
                    $('.timestamp').html('Waktu Absen');
                    $('.avatar-absen').attr('src','../template/img/image.png');
                }, 5000);
            }

            loaddata();
            loaddatacouter();
      },
    });
});


 loaddata();
 function loaddata(){
     $(".data-absensi").html('<div class="text-center text-white"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <p>Loading data...</p></div>');
     $(".data-absensi").load("./sw-proses.php?action=data-absensi");
 }

 loaddatacouter();
 function loaddatacouter(){
     $.ajax({
        type: 'POST',
        url  : './sw-proses.php?action=data-counter',
        dataType:'json',
        success: function(response) {
            $('.ontime').html(response.on_time);
            $('.terlambat').html(response.terlambat);
            $('.izin').html(response.izin);
            $('.cuti').html(response.cuti);

            $('.total-pegawai').html(response.total_pegawai);
            $('.total-posisi').html(response.total_posisi);
            $('.belum-absen').html(response.belum_absen);
            $('.total-absen').html(response.total_absen);
            $('.persentase').html(response.persentase);
        }
    });

 }
 