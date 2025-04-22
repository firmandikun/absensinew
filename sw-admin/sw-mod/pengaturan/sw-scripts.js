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

loadSetting(1);
function loadSetting(id){
    $(".load-form").html('<div class="text-center"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <p>Loading data...</p></div>');
    $(".load-form").load("sw-mod/pengaturan/sw-form.php?id="+id+"");
}


/** Setting Logo  */
$(document).on('change','.logo',function(e){
  var file_data = $('.logo').prop('files')[0];  
  var image_name = file_data.name;
  var image_extension = image_name.split('.').pop().toLowerCase();

  if(jQuery.inArray(image_extension,['gif','jpg','jpeg','png']) == -1){
    swal({title: 'Oops!', text: 'File yang di unggah tidak sesuai dengan format, File harus jpg, jpeg, gif, png.!', icon: 'error', timer: 2000,});
  }

  var form_data = new FormData();
  form_data.append("file",file_data);
  $.ajax({
    url:'sw-mod/pengaturan/sw-proses.php?action=logo',
    method:'POST',
    data:form_data,
    contentType:false,
    cache:false,
    processData:false,
    beforeSend:function(){
      loading();
    },
    success:function(data){
        if (data == 'success') {
            swal({title: 'Behasil!', text:'Berhasil menyimpan logo website.!', icon: 'success', timer: 1500,});
            loadSetting(1);
        } else {
            swal({title: 'Oops!', text: data, icon: 'error', timer: 2000,});
        }
    }
  });
});

/** Setting Favicon */
$(document).on('change','.favicon',function(){
  var file_data = $('.favicon').prop('files')[0];  
  var image_name = file_data.name;
  var image_extension = image_name.split('.').pop().toLowerCase();

  if(jQuery.inArray(image_extension,['gif','jpg','jpeg','png']) == -1){
    swal({title: 'Oops!', text: 'File yang di unggah tidak sesuai dengan format, File harus jpg, jpeg, gif, png.!', icon: 'error', timer: 2000,});
  }
  var form_data = new FormData();
  form_data.append("file",file_data);
  $.ajax({
    url:'sw-mod/pengaturan/sw-proses.php?action=favicon',
    method:'POST',
    data:form_data,
    contentType:false,
    cache:false,
    processData:false,
    beforeSend:function(){
      
    },
    success:function(data){
          if (data == 'success') {
              swal({title: 'Behasil!', text:'Berhasil menyimpan favicon.!', icon: 'success', timer: 1500,});
              loadSetting(1);
          } else {
              swal({title: 'Oops!', text: data, icon: 'error', timer: 2000,});
          }
    }
  });
});

/** Setting Web */
$(".load-form").on("submit", ".form-setting", function(e) {
        e.preventDefault();
        $.ajax({
          url:"sw-mod/pengaturan/sw-proses.php?action=setting-web",
          type: "POST",
          data: new FormData(this),
          processData: false,
          contentType: false,
          cache: false,
          async: false,
          beforeSend: function() { 
              loading();
          },
          success: function (data) {
              if (data == 'success') {
                  swal({title: 'Berhasil!', text: 'Pengaturan Web berhasil disimpan.!', icon: 'success', timer: 2500,});
                  loadSetting(1);
              } else {
                  swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
              }
          },
        });
  });


/** Setting Absensi */
$(".load-form").on("submit", ".form-setting-absensi", function(e) {
  e.preventDefault();
  $.ajax({
    url:"sw-mod/pengaturan/sw-proses.php?action=setting-absensi",
    type: "POST",
    data: new FormData(this),
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    beforeSend: function() { 
        loading();
    },
    success: function (data) {
        if (data == 'success') {
            swal({title: 'Berhasil!', text: 'Pengaturan Absen berhasil disimpan.!', icon: 'success', timer: 2500,});
            loadSetting(2);
        } else {
            swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
        }
    },
  });
});



/** Setting Server */
$(".load-form").on("submit", ".form-setting-server", function(e) {
  e.preventDefault();
  $.ajax({
    url:"sw-mod/pengaturan/sw-proses.php?action=setting-server",
    type: "POST",
    data: new FormData(this),
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    beforeSend: function() { 
        loading();
    },
    success: function (data) {
        if (data == 'success') {
            swal({title: 'Berhasil!', text: 'Pengaturan Server berhasil disimpan.!', icon: 'success', timer: 2500,});
            loadSetting(3);
        } else {
            swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
        }
    },
  });
});

/** Setting APi WhatsApp */
$(".load-form").on("submit", ".form-setting-whatsapp", function(e) {
  e.preventDefault();
  $.ajax({
    url:"sw-mod/pengaturan/sw-proses.php?action=setting-whatsapp",
    type: "POST",
    data: new FormData(this),
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    beforeSend: function() { 
        loading();
    },
    success: function (data) {
        if (data == 'success') {
            swal({title: 'Berhasil!', text: 'Pengaturan Api WHatsApp berhasil disimpan.!', icon: 'success', timer: 2500,});
            loadSetting(4);
        } else {
            swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
        }
    },
  });
});


/** Setting APi WhatsApp */
$(".load-form").on("submit", ".form-setting-whatsapp-v2", function(e) {
  e.preventDefault();
  $.ajax({
    url:"sw-mod/pengaturan/sw-proses.php?action=setting-whatsapp-v2",
    type: "POST",
    data: new FormData(this),
    processData: false,
    contentType: false,
    cache: false,
    async: false,
    beforeSend: function() { 
        loading();
    },
    success: function (data) {
        if (data == 'success') {
            swal({title: 'Berhasil!', text: 'Pengaturan Api WHatsApp berhasil disimpan.!', icon: 'success', timer: 2500,});
            loadSetting(5);
        } else {
            swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
        }
    },
  });
});

