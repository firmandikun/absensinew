
'use strict';
function loading(){
    // add spinner to button
    $('.loading-pesan').show();
    $('.loading-pesan').html('Loading...');
   window.setTimeout(function () {
    $('.loading-pesan').hide();
  }, 500);
}



$(document).on('click', '.btn-notifikasi', function(){ 
    var id = $(this).attr("data-id");
    $.ajax({  
            url:'./sw-mod/home/sw-proses.php?action=notifikasi',
            type:'POST',    
            data:{id:id},  
        success:function(data){ 
            if (data == 'success') {
                console.log('Success Notifikasi');
            } else {
                console.log(data);
            } 
        }
    });  
}); 



$(".emojioneArea").emojioneArea();


function loadChat(id){
    $(".load-data-frendlist").html('<div class="text-center mt-4"><span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> <p>Loading data...</p></div>');
    $(".load-data-frendlist").load("./sw-mod/chat/sw-proses.php?action=frendlist&id="+id+"");
}


$(document).on('click', '.btn-open-chat', function(){
    loadChat(1);
    $('.chatbox').addClass('active');
});

$(document).on('click', '.btn-close-chat', function(){
    $('.chatbox').removeClass('active');
});

/** Cari Kontak Pertemanan */
$(".search-frendlist").on('change', function(){
    var search = $('.search-frendlist').val();
    if(search == ''){
        loadChat(1);
    }else{
        $.ajax({
            type:'POST',
            url:'./sw-mod/chat/sw-proses.php?action=search-frendlist',
            data:{search:search},
            beforeSend:function(){
                $(".load-data-frendlist").text("Loading...");
            },
            success:function(data){
                $('.load-data-frendlist').html(data);
            }
        });
    }
});


/** Tambah Data ke live chat */
$(document).on('click', '.add-chat', function(){
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'POST',
        url  : './sw-mod/chat/sw-proses.php?action=add-frendlist',
        data: {id:id},
        success: function(data) {
            if (data == 'success') {
                loadChat(1);
            } else {
                swal({title: 'Oops!', text: data, icon: 'error', timer: 2500,});
            }
        },
        error: function(data){
           console.log(data);
        }
    });
});


$(document).on('click', '.get-open-cat', function(){
    var id = $(this).attr('data-id');
    $.ajax({
        type: 'POST',
        url  : './sw-mod/chat/sw-proses.php?action=get-open-cat',
        data: {id:id},
        dataType:'json',
        success: function(response){
            $('.parent-user').val(response.user_id);
            $('.nama-lengkap').html(response.nama_lengkap);
            $('.status-time').html(response.time);
            $('.avatar-chat').attr('src',''+response.avatar+'');
            $('.chatview').show();
            $('.friendslist').hide();
            LiveChat();
            startLoop();

        }, error: function(response){
           console.log(response.responseText);
        }
    });
});

$(document).on('click', '.close-live-chat', function(){
    $('.chatview').hide();
    $('.friendslist').show();
    stopLoop();
});


/** Open Live chat mesage */

function LiveChat(){
    var id = $('.parent-user').val();
    var oldscrollHeight = $(".chat-messages").prop("scrollHeight");
 
    $.ajax({
        type: 'POST',
        url  : './sw-mod/chat/sw-proses.php?action=message',
        data: {id:id},
        success: function(data) {
            if (data == 'stoploop') {
                stopLoop();
                $(".load-data-message").html('<div class="text-center text-mutted mt-4">Belum ada percakapan, silahkan kirim pesan...</div>');
            } else {
                $(".load-data-message").html(data);
               
                var newscrollHeight = $(".chat-messages").prop("scrollHeight");
                if (newscrollHeight > oldscrollHeight) {
                    $(".chat-messages").animate({
                        scrollTop: newscrollHeight
                    }, 'normal'); //Autoscroll to bottom of div
                }
            }
        },
        error: function(data){
           console.log(data);
        }
    });
}
 

/** Looping Live chat Message */
var keepGoing = true;
function myLoop() {
    LiveChat();
    if(keepGoing) {
        setTimeout(myLoop, 5000);
    }
}

function startLoop() {
    keepGoing = true;
    myLoop();
}

function stopLoop() {
    keepGoing = false;
}
/** Looping Live chat Message */

/** Tambah pesan baru */
$('.form-add-chat').submit(function (e) {
    e.preventDefault();
        $.ajax({
            url:"./sw-mod/chat/sw-proses.php?action=add",
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
                    LiveChat()
                    $(".form-add").trigger('reset');
                    $('emojionearea-editor').html('');
                } else {
                    swal({title: 'Oops!', text: data, icon: 'error', timer:1500,});
                }
  
            },
        });
  });