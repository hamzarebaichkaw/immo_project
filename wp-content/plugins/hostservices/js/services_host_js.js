
//add the jQuery click/show/hide behaviours:
$(document).ready(function(){
     $("#reply_services").click(function(){
         if($("#manage_reply").is(":visible")){
           $("#manage_reply").hide();
        } else {
           $("#manage_reply").show();
        }
        //don't follow the link (optional, seen as the link is just an anchor)
        return false;
     });
  });
$(document).ready(function(){
	
	$("#reply_services").click(function(){
         
        //don't follow the link (optional, seen as the link is just an anchor)
        $("#test_js_services").hide();
     });