//If JS is enabled add a class so we can hide the form ASAP (and only for JS enabled browsers)
document.documentElement.className = 'js';
//add the jQuery click/show/hide behaviours:
$(document).ready(function(){
     $("#reply").click(function(){
         if($("#manage_reply").is(":visible")){
           $("#manage_reply").hide();
        } else {
           $("#manage_reply").show();
        }
        //don't follow the link (optional, seen as the link is just an anchor)
        return false;
     });
  });