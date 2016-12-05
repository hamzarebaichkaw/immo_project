jQuery(function( $ ) {
    <?php $mois=date('M');
          $annee=date('Y');  
    
    
    ?>
    var mois=<?php echo $mois; ?>
        var annee=<?php echo $annee; ?>
      $(document).ready(function(){
          
          $("#content").lead("calendar_services.php?mois="+mois+"&anne="+annee);
          
          
      });


$(document).ready(function(){
$("#clearfix").css({
                     position : 'fixed',
                     top : '0',
                     color:'black'
                });

});
     
});