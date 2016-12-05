<?php
 function calendar_services($mois,$annee){

$nbr_jour=cal_days_in_month(CAl_GREGORIAN,$mois, $annee);
echo "<table>";
echo "<tr>";
echo "<th>Lundi</th><th>mardi</th><th>mercredi </th><th>jeudi</th><th>vendredi</th><th>samdi</th><th>dimanche</th>";
echo "</tr>";
for ($i=1; $i <=$nbr_jour ; $i++) { 
	 
$jour=cal_to_jd(CAl_GREGORIAN, $mois,$i , $annee);
$jour_semaine=JDDayOfWeek($jour);
if ($i== $nbr_jour) {
            
            if ($jour_semaine==1) {
            	echo "<tr>";
            }
            echo "<td class='case'>".$i."</td></tr>";

}elseif ($i==1) {
 
 echo "<tr>";
 if ($jour_semaine==0) {
 	$jour_semaine=7;
 }
for ($k=0; $k !=$jour_semaine ; $k++) { 
	    
                     echo "<td></td>";

	}
echo "<td class='case'>".$i."</td>";
if ($jour_semaine==7)
{
   echo "</tr>";
}

 }else{
 	if ($jour_semaine==1) {
 		echo "<tr>";
 	}
 	echo"<td class='case'>".$i."</td>";
if ($jour_semaine==0) {
	echo"</tr> ";
}

}
}
echo "</table>";
 }

?>