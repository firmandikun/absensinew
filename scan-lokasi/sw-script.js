jQuery(function($) {
    setInterval(function() {
      var date = new Date(),
          time = date.toLocaleTimeString();
      $(".clock").html(time);
    }, 1000);
});



function myGreeting() {
  var id = $('.id').html();
  $.ajax({
    url:"./sw-proses.php?action=qrcode",
    type: "POST",
    data:{id:id},
    success: function (data) {
        //setTimeout(function() {window.location.href = "./"});
        $('.imaged').attr('src','../sw-content/lokasi/'+data+'');
        //alert(data);
    },
  });
}

function selesai() {
  setTimeout(function() {
      myGreeting();
      selesai();
      mytimer();
  }, 10000);
}


$(document).ready(function() {
  //myGreeting();
  selesai();
  mytimer();
  toggleFullScreen();
});


function mytimer() {
var timeleft = 10;
  var downloadTimer = setInterval(function(){
    if(timeleft <= 0){
      clearInterval(downloadTimer);
      document.getElementById("countdown").innerHTML = "Finished";
    } else {
      document.getElementById("countdown").innerHTML = timeleft + " seconds remaining";
    }
    timeleft -= 1;
  }, 1000);

};


function toggleFullScreen() {
  if ((document.fullScreenElement && document.fullScreenElement !== null) ||  
   (!document.mozFullScreen && !document.webkitIsFullScreen)) {
    if (document.documentElement.requestFullScreen) {
      document.documentElement.requestFullScreen();
    } else if (document.documentElement.mozRequestFullScreen) {
      document.documentElement.mozRequestFullScreen();
    } else if (document.documentElement.webkitRequestFullScreen) {
      document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
    }
  } else {
    if (document.cancelFullScreen) {
      document.cancelFullScreen();
    } else if (document.mozCancelFullScreen) {
      document.mozCancelFullScreen();
    } else if (document.webkitCancelFullScreen) {
      document.webkitCancelFullScreen();
    }
  }
}