var popupWindow = function(string, button) {
   var okno = window.open("","_blank","status=no,menubar=no,location=no,scrollbars=yes,toolbar=no,titlebar=no,width=1000,height=600,resizable=yes,left=0,top=0");
   okno.document.write(string);
   var timer = window.setInterval(function() {
     if(okno.closed) {
       window.clearInterval(timer);
       document.body.appendChild(button);
     }
   }, 100);
};