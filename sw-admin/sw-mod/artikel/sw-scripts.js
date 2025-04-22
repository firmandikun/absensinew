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
        table = $('.datatable-user').DataTable({
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
                "url": "./sw-mod/artikel/sw-datatable.php",
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

    $("body").on("click", ".datepicker", function(){
        $(this).datepicker({
          format: 'dd-mm-yyyy',
          autoclose:true
        });
        $(this).datepicker("show");
    });




    tinymce.init({
        selector: ".swEditorText",
        theme: 'modern',
        skin:'custom',
        statusbar: false,
        content_style: "p { font-size: 15px;}",
        plugins: 'template, codemirror, preview, wordcount, advlist, autolink, lists, link, image, charmap, print, preview, hr, anchor pagebreak searchreplace wordcount, visualblocks, visualchars, fullscreen, insertdatetime, media, imagetools,  contextmenu, nonbreaking, save, table, contextmenu, directionality, emoticons, paste, textcolor, colorpicker, textpattern',
    
        toolbar1: "undo redo bold italic underline bullist numlist alignleft aligncenter alignright alignjustify table blockquote  removeformat  forecolor backcolor ",
        
        toolbar2: "styleselect fontsizeselect formatselect link unlink insertdatetime image media  code preview fullscreen",
    
    
        templates: [
            {title: 'Button Info', content: '<a href="#" class="btn btn-info btn-lg">Download</a>'},
            {title: 'Button Success', content: '<a href="#" class="btn btn-success btn-lg">Download</a>'},
            {title: 'Button Warning', content: '<a href="#" class="btn btn-warning btn-lg">Download</a>'},
            {title: 'Button Danger', content: '<a href="#" class="btn btn-danger btn-lg">Download</a>'},
            {title: 'Button Default', content: '<a href="#" class="btn btn-default btn-lg">Download</a>'}
        ],
    
        codemirror: {
            indentOnInit: true,
            path: 'codemirror-4.8',
            config: {
              lineNumbers: true       
            }
          },
    
        style_formats: [
            {title: 'Open Sans', inline: 'span', styles: { 'font-family':'Open Sans'}},
            {title: 'Arial', inline: 'span', styles: { 'font-family':'arial'}},
            {title: 'Book Antiqua', inline: 'span', styles: { 'font-family':'book antiqua'}},
            {title: 'Comic Sans MS', inline: 'span', styles: { 'font-family':'comic sans ms,sans-serif'}},
            {title: 'Georgia', inline: 'span', styles: { 'font-family':'georgia,palatino'}},
            {title: 'Helvetica', inline: 'span', styles: { 'font-family':'helvetica'}},
            {title: 'Impact', inline: 'span', styles: { 'font-family':'impact,chicago'}},
            {title: 'Tahoma', inline: 'span', styles: { 'font-family':'tahoma'}},
            {title: 'Terminal', inline: 'span', styles: { 'font-family':'terminal,monaco'}},
            {title: 'Times New Roman', inline: 'span', styles: { 'font-family':'times new roman,times'}},
            {title: 'Verdana', inline: 'span', styles: { 'font-family':'Verdana'}}
        ],             
    
        content_css: [
          //'//https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600',
          'https://tinymce.com/css/codepen.min.css',
          './sw-assets/css/tiny.css'
        ],
        
    image_advtab: true,
    relative_urls : false,
    remove_script_host : true,
    convert_urls:false,
    fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
    file_browser_callback: function(field, url, type, win) {
        tinyMCE.activeEditor.windowManager.open({
            file: 'sw-assets/kcfinder/browse.php?opener=tinymce4&field=' + field + '&type=' + type,
            title: 'File Manager',
            width: 1000,
            height: 500,
            inline: true,
            close_previous: false
        }, {
            window: win,
            input: field
        });
        return false;
    }
    });


    
/** Upload Drag and Drop */
 function readURL(input) {
    if (input.files && input.files[0]) {
      var reader = new FileReader();
      reader.onload = function(e) {
        $('.image-upload-wrap').hide();
        $('.file-upload-image').attr('src', e.target.result);
        $('.file-upload-content').show();
        //$('.image-title').html(input.files[0].name);
      };
      reader.readAsDataURL(input.files[0]);
    } else {
      removeUpload();
    }
  }

  function removeUpload() {
    $('.file-upload-input').replaceWith($('.file-upload-input').clone());
    $('.file-upload-content').hide();
    $('.image-upload-wrap').show();
  }
  $('.image-upload-wrap').bind('dragover', function () {
      $('.image-upload-wrap').addClass('image-dropping');
  });
      $('.image-upload-wrap').bind('dragleave', function () {
      $('.image-upload-wrap').removeClass('image-dropping');
  });


/** Tambah Baru */
$('.form-add').submit(function (e) {
    e.preventDefault();
    tinyMCE.triggerSave();
    loading();
        $.ajax({
            url:"./sw-mod/artikel/sw-proses.php?action=add",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { 
              loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
                    $(".form-add").trigger("reset");
                    window.setTimeout(window.location.href = "./artikel",2500);
    
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                    loadData();
                }
  
            },
            complete: function () {
                
            },
        });
  }); 

    

/** Update Artikel */
$('.form-update').submit(function (e) {
    e.preventDefault();
    tinyMCE.triggerSave();
    loading();
        $.ajax({
            url:"./sw-mod/artikel/sw-proses.php?action=update",
            type: "POST",
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            async: false,
            beforeSend: function () { 
              loading();
            },
            success: function (data) {
                if (data == 'success') {
                    swal({title: 'Berhasil!', text: 'Data berhasil disimpan.!', icon: 'success', timer: 2500,});
                    $(".form-add").trigger("reset");
                    window.setTimeout(window.location.href = "./artikel",2500);
    
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
                    loadData();
                }
  
            },
            complete: function () {
                
            },
        });
  }); 


/** Hapus data Artikel*/
$(document).on('click', '.btn-delete', function(){ 
    var id = $(this).attr("data-id");
    var name = $(this).attr("data-name");
      swal({
        text: "Anda yakin ingin menghapus  data ini?",
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
                 url:'./sw-mod/artikel/sw-proses.php?action=delete',
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
    

$(document).on('click', '.btn-kategori', function(){ 
    swal("Tambah kategori baru", {
        content: "input",
    })
    .then((value) => {
        if(value) {
            $.ajax({  
                url:'./sw-mod/artikel/sw-proses.php?action=add-kategori',
                type:'POST',    
                data:{title:value},  
               success:function(data){
                    var results = data.split("/");
                     var error = results[0];
                     var notif = results[1];
                    if(error == "error"){
                        swal({title: 'Gagal!', text: notif, icon: 'error', timer:1500,});
                    }else{
                        $(".kategori").html(data);
                        swal({title: 'Berhasil!', text: 'Kategori berhasil ditambah.!', icon: 'success', timer: 1500,});

                    }
                }  
           });  
        }
       
    });
});