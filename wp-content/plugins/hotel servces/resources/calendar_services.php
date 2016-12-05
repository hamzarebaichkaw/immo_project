<?php
$mois=$_POST['mois'];
$annee=$_POST['annee'];

include"function_calendar_services.php";
?>
<div>
	<?php
     calendar_services($mois,$annee);
          
	?>


</div>