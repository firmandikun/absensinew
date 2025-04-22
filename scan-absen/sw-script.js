
navigator.geolocation.getCurrentPosition(function(location) {
    var latitude =""+location.coords.latitude+","+location.coords.longitude+"";
    console.log(latitude);

    var html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox:280,facingMode: "environment"});
    function onScanSuccess(decodedText, decodedResult) {
        var selected = document.querySelector('input[type=radio][name=tipe_absen]:checked');
        var tipe_absen = selected.value;
        if(tipe_absen =='masuk') {
            var url = "./sw-proses.php?action=absen-in";
        }else{
            var url = "./sw-proses.php?action=absen-out";
        }
        $.ajax({
            type: "POST",
            url: url,
            data: {qrcode:decodedText,latitude:latitude},
            success: function (data) {
                var results = data.split("/");
                if (results[0]=='success') {
                        swal({title: 'Berhasil!', text:results[1], icon: 'success', timer: 2500,});
                } else {
                    swal({title: 'Oops!', text:data, icon: 'error',});
                }
            }
        });
    }
    html5QrcodeScanner.render(onScanSuccess);

});
