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
            url  : './sw-mod/laporan-overtime/sw-proses.php?action=dropdown',
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
        var bulan   = $('.bulan').val();
        var tahun   = $('.tahun').val();
        table = $('.datatable').DataTable({
            "scrollY": false,
            "scrollX": false,
            "processing": true, 
            "serverSide": false, 
            "bAutoWidth": true,
            "bSort": false,
            "bStateSave": true,
            "bDestroy" : true,
            "paging": true,
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
                "url": "./sw-mod/laporan-overtime/sw-datatable.php?lokasi="+lokasi+"&pegawai="+pegawai+"&bulan="+bulan+"&tahun="+tahun+"",
                "type": "POST"
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

$(document).on('click', '.setactive', function(){
  var id = $(this).attr("data-id");
  var active = $("#set"+id).attr("data-active");
  
  if(active == "Y"){
      var dataactive = "N";
  }else{
      var dataactive = "Y";
  }
  $("#set"+id).html("waiting");

  $.ajax({
      type: "POST",
      url: "sw-mod/laporan-overtime/sw-proses.php?action=setactive",
      data:{id:id,status:dataactive},  
      success: function(data){
          if(active == "Y"){
              $("#set"+id).attr("data-active","N");
              $("#set"+id).attr("class","btn btn-outline-danger btn-sm setactive");
              $("#set"+id).html("Tidak Disetujui");
          }else{
              $("#set"+id).attr("data-active","Y");
              $("#set"+id).attr("class","btn btn-outline-success btn-sm setactive");
              $("#set"+id).html("Setujui");
          }
      }
  });
});


/** Print */
$(document).on('click', '.btn-print', function(){
    var tipe = $(this).attr("data-tipe");
    var pegawai = $('.pegawai').val();
    var bulan  = $('.bulan').val();
    var tahun  = $('.tahun').val();
    var url = "./sw-mod/laporan-overtime/sw-print.php?action=print&pegawai="+pegawai+"&bulan="+bulan+"&tahun="+tahun+"&tipe="+tipe+""; 
    window.open(url, '_blank');
});