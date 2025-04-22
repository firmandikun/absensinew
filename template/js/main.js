'use strict'
$(document).ready(function () {

    var body = $('body');
    var mainmenu = $('.main-menu');

    /* page load as iframe */
    if (self !== top) {
        body.addClass('iframe');
    } else {
        body.removeClass('iframe');
    }

    /* Dropdown toggle */
    //$('.dropdown-toggle').dropdown()

    /* floating input text fields */
    $('.floating-input').each(function () {
        if (!$(this).val() || $(this).val().length === 0) {
            //$(this).parent().removeClass('active')
        } else {
            $(this).parent().addClass('active')
        }
    })
    $('.floating-input').on('blur', function () {
        if (!$(this).val() || $(this).val().length === 0) {
            $(this).parent().removeClass('active')
        } else {
            $(this).parent().addClass('active')
        }

        return false;
    });


    /* menu open close */
    $('.main-menu .btn-close').on('click', function () {
        if (body.hasClass('menu-overlay') === true) {
            body.removeClass('menu-open');
        } else {
            body.removeClass('menu-active');
            body.removeClass('menu-open');
            $('html').removeClass('menu-open');
        }

        return false;
    })
    $('.menu-btn').on('click', function () {
        if (body.hasClass('menu-overlay') === true) {
            body.addClass('menu-open');
        } else {
            body.addClass('menu-active');
            body.addClass('menu-open');
            $('html').addClass('menu-open');
        }

        return false;
    });
    $('.main-menu + .backdrop').on("click", function (e) {
        if (body.hasClass('menu-open') === true) {
            body.removeClass('menu-open');
        }

        return false;
    });



    /* menu style switch */
    $('#menu-pushcontent').on('change', function () {
        if ($(this).is(':checked') === true) {
            body.addClass('menu-push-content');
            mainmenu.css('display', 'block');
            body.removeClass('menu-overlay');
        }

        return false;
    });
    $('#menu-overlay').on('change', function () {
        if ($(this).is(':checked') === true) {
            body.removeClass('menu-push-content');
            mainmenu.css('display', 'block');
            body.addClass('menu-overlay');
        }

        return false;
    });


    /* back page navigation */
    $('.back-btn').on('click', function () {
        window.history.back();

        return false;
    });

    /* float label checking input is not empty */
    $('.float-label .form-control').on('change', function () {
        if ($(this).val() || $(this).val().length != 0) {
            $(this).closest('.float-label').addClass('active');
        } else {
            $(this).closest('.float-label').removeClass('active');
        }
        return false;
    })
});


$(window).on('load', function () {
    setTimeout(function () {
        $('.loader-display').fadeOut('slow');
    }, 100);


    /* Background */
    $('.background').each(function () {
        var imgpath = $(this).find('img');
        $(this).css('background-image', 'url(' + imgpath.attr('src') + ')');
        imgpath.hide();
    })

    /* url path on menu */
    var path = window.location.href; // because the 'href' property of the DOM element is the absolute path
    $(' .main-menu ul a').each(function () {
        if (this.href === path) {
            $(' .main-menu ul a').removeClass('active');
            $(this).addClass('active');
        }
    });


});

$(window).on('scroll', function () {

    /* scroll from top and add class */
    if ($(document).scrollTop() > '10') {
        $('.header').addClass('active');
    } else {
        $('.header').removeClass('active');
    }
});


$(window).on('resize', function () {

});

function loading(){
    $('.btn-loding').prop("disabled", true);
      // add spinner to button
      $('.btn-loding').html(
        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Loading...'
      );
     window.setTimeout(function () {
      $('.btn-loding').prop("disabled", false);
      $('.btn-loding').html('<i class="far fa-save"></i> Simpan'
      );
    }, 2000);
}

//Mengirimkan Token Keamanan
$.ajaxSetup({
    headers : {
        'Csrf-Token': $('meta[name="csrf-token"]').attr('content')
    }
});

jQuery(function($) {
    setInterval(function() {
      var date = new Date(),
          time = date.toLocaleTimeString();
      $(".clock").html(time);
    }, 1000);
  });

$(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('[rel="tooltip"]').tooltip();
});

$(".sw-datatable").on("mouseover", ".btn-tooltip", function(e) {
        $('[data-toggle="tooltip"]').tooltip();
});

$("body").on("mouseover", "button", function(){
    $('[data-toggle="tooltip"]').tooltip();
});


$(window).scroll(function(){
    if ($(this).scrollTop() > 50) {
        $('.btn-floating').addClass('active');
    } else {
        $('.btn-floating').removeClass('active');
    }
});


$(document).on('click', '.btn-notifikasi', function(){ 
    var id = $(this).attr("data-id");
    $.ajax({  
            url:'./module/home/sw-proses.php?action=notifikasi',
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