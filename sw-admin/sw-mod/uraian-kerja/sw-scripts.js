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


$('.timepicker').timepicker({
    showInputs: false,
    showMeridian: false,
    use24hours: true,
    format :'HH:mm'
});

$("body").on("click", ".datepicker", function(){
    $(this).datepicker({
      format: 'dd-mm-yyyy',
      autoclose:true
    });
    $(this).datepicker("show");
});


$(".lokasi").change(function(){
  var lokasi = $(this).val(); 
      $.ajax({
          type: 'POST',
          url  : './sw-mod/uraian-kerja/sw-proses.php?action=dropdown',
          data: {lokasi:lokasi},
          cache: false,
          success: function(data){
          $(".pegawai").html(data);
        }
    });
});


/** Module  */
loadData();
function loadData(){
    var table;
    $(document).ready(function() {
        //datatables
        var lokasi  = $('.lokasi').val();
        var pegawai = $('.pegawai').val();
        var bulan  = $('.bulan').val();
        var tahun  = $('.tahun').val();
        table = $('.datatable').DataTable({
            "scrollY": false,
            "scrollX": false,
            "processing": true, 
            "serverSide": false, 
            "bAutoWidth": true,
            "bSort": true,
            "bStateSave": true,
            "bDestroy" : true,
            "paging": true,
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

            "ssSorting" : [[0, 'desc']],
            "iDisplayLength": 25,
           // "order": [[1, 'desc']],
            
            "aLengthMenu": [
                [25, 30, 50, -1],
                [25, 30, 50, "All"]
            ],
            
            buttons: [
              'copy', 'excel', 'pdf'
            ],
            language: {
              paginate: {
                previous: "<i class='fas fa-angle-left'>",
                next: "<i class='fas fa-angle-right'>"
              }
            },
            "ajax": {
                "url": "./sw-mod/uraian-kerja/sw-datatable.php",
                "type": "POST",
                "data": {
                    lokasi:lokasi,
                    pegawai:pegawai,
                    bulan:bulan,
                    tahun:tahun,
                 },
            },
 
            "columnDefs": [{ 
                "targets": [ 0 ], 
                "orderable": false, 
            },],
        });
    });

}

/** Dropdown */
$(".lokasi").change(function(){
  loadData();
});


$(".pegawai").change(function(){
    loadData();
});


$(".bulan").change(function(){
    loadData();
});

$(".tahun").change(function(){
    loadData();
});


$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
    var name = $(this).attr("data-name");
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
                 url:'./sw-mod/uraian-kerja/sw-proses.php?action=delete',
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


/** Print */
$(document).on('click', '.btn-print', function(){
    var tipe = $(this).attr("data-tipe");
    var lokasi  = $('.lokasi').val();
    var pegawai = $('.pegawai').val();
    var bulan  = $('.bulan').val();
    var tahun  = $('.tahun').val();
    var url = "./sw-mod/uraian-kerja/sw-print.php?action=print&lokasi="+lokasi+"&pegawai="+pegawai+"&bulan="+bulan+"&tahun="+tahun+"&tipe="+tipe+""; 
    window.open(url, '_blank');
});