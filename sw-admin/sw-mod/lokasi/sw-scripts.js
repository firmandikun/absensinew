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
        table = $('.datatable-lokasi').DataTable({
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
                "url": "./sw-mod/lokasi/sw-datatable.php",
                "type": "POST",
                "data": {
                    modifikasi:modifikasi,
                    hapus:hapus,
                 }, 
            },
 
            "columnDefs": [{ 
                "targets": [ 0 ], 
                "orderable": false, 
            },],
        });
    });
}



/** Map Leatfet  */
function lokasi(){
    navigator.geolocation.getCurrentPosition(function(location) {
        var latlng = new L.LatLng(location.coords.latitude, location.coords.longitude);
        var latitude_input  = $('.latitude').val();
        var longitude_input = $('.longitude').val();
            if(latitude_input == '' && longitude_input ==''){
                $(".latitude").val(location.coords.latitude);
                $(".longitude").val(location.coords.longitude).keyup();
            var curLocation = [0,0];
            }else{
                var curLocation = [latitude_input, longitude_input];
            }   
                if (curLocation[0] == 0 && curLocation[1] == 0) {
                curLocation = latlng;
                }
                var map = L.map('MapLocation').setView(curLocation, 15);
                L.tileLayer('https://{s}.tile.osm.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
                }).addTo(map);
                map.attributionControl.setPrefix(false);
                    var marker = new L.marker(curLocation, {
                    draggable: 'true'
                }); 
    
                L = L.control.locate({
                strings: {
                    title: "Tunjukkan di mana saya berada!"
                }
                }).addTo(map);
    
    
                marker.on('dragend', function(event) {
                var position = marker.getLatLng();
                $(".latitude").val(location.coords.latitude);
                $(".longitude").val(location.coords.longitude).keyup();
    
                marker.setLatLng(position, {
                    draggable: 'true'
                }).bindPopup(position).update();
                $(".latitude").val(position.lat);
                $(".longitude").val(position.lng).keyup();
                });
                
    
                $(".latitude, .longitude").change(function() {
                var position = [parseInt($(".latitude").val()), parseInt($(".longitude").val())];
                marker.setLatLng(position, {
                    draggable: 'true',
                    showCompass: true,
                showPopup: false,
                }).bindPopup(position).update();
                map.panTo(position);
                });
                map.addLayer(marker);
            });
}



  /** Tambah Lokasi */
    $(".form-add").validate({
        // Specify validation rules
        rules: {
            field: {
                required: true
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
        },
        // in the "action" attribute of the form when valid
        submitHandler: submitForm_Add
      });

    /* handle form submit */
    function submitForm_Add() { 
        var data = $(".form-add").serialize();
        $.ajax({    
            type : 'POST',
            url  : './sw-mod/lokasi/sw-proses.php?action=add',
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
                    window.setTimeout(window.location.href = "./lokasi",2500);
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                }
            }
        });
            return false; 
    }


    /* ------ Update Lokasi ------- */
    $(".form-update").validate({
        // Specify validation rules
        rules: {
          field: {
              required: true
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
        },
        // in the "action" attribute of the form when valid
        submitHandler: submitForm_Update
      });

    /* handle form submit */
    function submitForm_Update() { 
        var data = $(".form-update").serialize();
        $.ajax({    
            type : 'POST',
            url  : './sw-mod/lokasi/sw-proses.php?action=update',
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


    /* ------------- Set Active Lokasi --------------*/
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
        url: "./sw-mod/lokasi/sw-proses.php?action=active",
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


/** Hapus data Lokasi */
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
    var name = $(this).attr("data-name");
      swal({
        text: "Anda yakin ingin menghapus lokasi "+name+".?",
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
                 url:'./sw-mod/lokasi/sw-proses.php?action=delete',
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
    