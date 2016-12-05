

<?php

function client_service_Panier_repas(){
	 global $panier_repas_load_css;
     global $panier_repas_load_js;
        // set this to true so the CSS is loaded
        $panier_repas_load_css = true;
        $panier_repas_load_js=true;
GLOBAL $wpdb;
$id=get_current_user_id();
     
 $host_services_drafts= $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join    
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");



foreach($host_services_drafts as $host_servces_draft)



      {
      	if($host_servces_draft->id==3){
?>
<div class="container" >
<div class="IconDiv">
	<div class="service_icon">
		<img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" >
</div>
<div >
<ul>
<li  >
	Liste des tarifs
</li>
	<li >
		<div class="row"> <p class="pwrap col-md-10 col-sm-10">Pique-nique Parisien <span class="fs14 txtBlack R col-md-2 col-sm-2" style="text-align:right"> <?php echo '€   '.$host_servces_draft->prix ; ?></span></p></div>

	</li>
</ul>
	
</div>

</div>
<div class="col-md-6">
<div class="calendar_repas_panier_pargraphe" >
<div class="Titre"> <?php echo $host_servces_draft->Titre ;?> </div>
 <p> Inutile d'aller faire les courses : vous pouvez disposer dès votre arrivée d'un réfrigérateur rempli de produits locaux ou de produits de base, ou vous faire livrer à n'importe quel moment de votre séjour.</p> </div><div class="calendar_repas_panier">

<?php
include"Ical.php";

 global $immo_load_css;
 $events[0] = new Eventre(1, date('Y-m-d', strtotime('-37 day')), date('Y-m-d', strtotime('-4 day')), 'travel', 1);
      $events[1] = new Eventre(2, date('Y-m-d'), '', 'personal interview', 2);
      $events[2] = new Eventre(3, date('Y-m-d'), '', 'work appointment', 3);
      $events[3] = new Eventre(5, date('Y-m-d', strtotime('+3 day')), '', 'personal appointment', 1);
      $events[4] = new Eventre(6, date('Y-m-d', strtotime('-2 year')), date('Y-m-d', strtotime('+5 day')), 'studies', 3);
      $events[5] = new Eventre(4, date('Y-m-d', strtotime('-5 day')), date('Y-m-d', strtotime('+2 day')), 'Work on project', 2);
      $events[6] = new Eventre(7, date('Y-m-d'), '', 'personal', 1);
      $events[7] = new Eventre(9, date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+7 day')), 'Whatever...', 2);
      $events[8] = new Eventre(8, date('Y-m-d', strtotime('+3 day')), date('Y-m-d', strtotime('+57 day')), 'Just on more for the exemple', 1);

        // set this to true so the CSS is loaded
        $immo_load_css = true;
         usort($events, "sortByDurations");
 $intervalYear = 6;

      //récupère les dates actuelles
      // catch actualy dates
      $dayNow = strftime("%d");
      $monthNow = strftime("%m");
      $yearNow = strftime("%Y");

      //récupération du mois et de l'année envoyés en POST
      // take months and years send in POST
      $yearN = (isset($_POST['year']))?$_POST['year']:$yearNow;
      if (isset($_POST['month'])) {
        if ($_POST['month'] < 1) {
          $_POST['month'] = 12;
          $yearN -= 1;
          $_POST['year'] = $yearN;
        }
        if ($_POST['month'] > 12) {
          $_POST['month'] = 1;
          $yearN += 1;
          $_POST['year'] = $yearN;
        }
      }
      $monthN = (isset($_POST['month']))?$_POST['month']:$monthNow;

      //nombre de jours dans le mois et numero du premier jour du mois
      // take days in one month and first day of the month
      $nbrDayInMonth = date("t", mktime(0,0,0,$monthN,1,$yearN));
      $firstDay = date("w", mktime(0,0,0,$monthN,1,$yearN));
      //ajustement du jour (si =0 (dimanche), alors =7)
      // set first day (if =0 (sunday), then =7)
      $firstDay = ($firstDay == 0)?7:$firstDay;

      //nbr de jours du moi d'avant
      // days of the previous month
      $m = ($monthN - 1 < 1)?12:$monthN - 1;
      $preDays = date("t", mktime(0,0,0,$m,1,$yearN));

      //nbr de jours du mois d'apres
      // days of the month after
      $m = ($monthN + 1 > 12)?1:$monthN + 1;
      $aftMonth = date("t", mktime(0,0,0,$m,1,$yearN));

      //ne récupère que les évennements du mois parcourus (essenciel pour l'affichage), à remplacer par un where > date dans la requête SQL
      //set only the elements of the actual month (essential for the templating), can be replace with a where > date in the SQL request
      $unsetKeys = array();
      foreach ($events as $key => $e) {
        if (intval($e->date_begin->format('Y')) <= intval($yearN) && intval($e->date_end->format('Y')) >= intval($yearN)) {
        if (intval($e->date_begin->format('Y')) == intval($yearN)) {
          if (intval($e->date_begin->format('m')) > intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        if (intval($e->date_end->format('Y')) == intval($yearN)) {
          if (intval($e->date_end->format('m')) < intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        } else {
          $unsetKeys[] = $key;
        }
      }
      foreach ($unsetKeys as $unsetKey) {
        unset($events[$unsetKey]);
      }

      $t = 1;
      $style = "";
      for($i=1; $i<7; $i++) {
        for($j=1; $j<8; $j++) {
          //on met les jours du mois précédent
          // we set days of previous month
          if ($t == 1 && $j < $firstDay) {
            $style = "color:#aaa;";
            $day = $preDays-($firstDay-($j))+1;
            $tab_cal[$i][$j] = "<div style='{$style}'>{$day}</div>";
          }
          //on met le premier jour du mois à afficher
          // we set the first day of the month to print
          elseif ($j == $firstDay && $t == 1) {
            $style = "color:#000;";
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
          //on met le premier jour du mois d'après
          // we set the first day of the next month
          elseif ($t > $nbrDayInMonth) {
            $style = "color:#aaa;";
            $tab_cal[$i][$j] = "<div style='{$style}'>1</div>";
            $t = 2;
          }
          //on met les jours suivants du mois à afficher et du mois suivant
          // we set nexts days os the month to print and the next month
          elseif ($t > 1 && $t <= $nbrDayInMonth) {
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
        }
      }

?>
     <div class="containercal">
     
      <!-- Tableau du calendrier -->
      <div class="thumbnail">
        <table class="table table-bordered">
          <caption><h1><span class="glyphicon glyphicon-calendar"></span> Calendrier :</h1></caption>
          <!-- Année -->
          <tr>
            <th colspan="7">
              <h2>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN-1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN <= $yearNow - $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=$yearN?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN+1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN >= $yearNow + $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h2>
            </th>
          </tr>
          <tr>
            <th colspan="7">
              <h3>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN-1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN <= $yearNow - 3 && $monthN == 1)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=ucwords(strftime("%B", mktime(1, 1, 1, $monthN, 1, $yearN)))?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN+1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN >= $yearNow + 3 && $monthN == 12)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h3>
            </th>
          </tr>
          <tr>
            <?php for ($i = 1; $i < 8; $i++) { ?>
                <th>
                  <?=ucwords(strftime("%a", mktime(1, 1, 1, 5, $i, 2000)))?>
                </th>
            <?php } ?>
          </tr>
          <?php for($i=1; $i<7; $i++) { ?>
            <tr>
              <?php for($j=1; $j<8; $j++) {
                //récupérer le jour dans la chaine de charactère retournée
                // catch the day in the string return
                preg_match_all('!\d+!', $tab_cal[$i][$j], $day);
                $day = (isset($day[0][1]))?$day[0][1]:0; ?>
                <td  class="<?=($monthN == $monthNow && $yearN == $yearNow && $day == $dayNow)?'info':'';?> size">
                  <h4>
                    <?=$tab_cal[$i][$j]?>
                  </h4>
                  <div class="parent-day" parent-day="day">
                  <?php 
                  foreach ($events as $id => $e) {
                    if ($e->date_begin != $e->date_end) {
                      if ($day <> null) {
                        if ($e->date_begin <= new DateTime($yearN.'-'.$monthN.'-'.$day) && $e->date_end >= new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                          <div class="container-fluid btn-<?=$id?>-parent">
                            <div class="row parent-row">
                              <button type="button" event-in-month="<?=$id?>" class="btn btn-<?=$e->rank['color']?> btn-sm btn-block btn-hover btn-<?=$id?>" data-toggle="tooltip" data-placement="top" title="<?=$e->description?> : <?=$e->date_begin->format('Y-m-d')?> -> <?=$e->date_end->format('Y-m-d')?>">
                              </button>
                            </div>
                          </div>
                        <?php }
                      }
                    }
                  }?>
                  </div>
                  <?php foreach ($events as $e) {
                    if ($e->date_begin == $e->date_end) {
                      if ($e->date_begin == new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                        <button type="button" class="btn btn-<?=$e->rank['color']?> btn-sm" data-toggle="tooltip" data-placement="top" title="<?=$e->description?>">
                        </button>
                      <?php }
                    }
                  } ?>
                </td>
              <?php } ?>
            </tr>
          <?php } ?>
        </table>
      </div> <!-- table -->
    </div><!-- container -->
           
<form method="post">
<?php /////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////?>
<div class=""> <div ><button  class="btn_ret" type="submit"> retour </button></div><div ><button  class="btn_val" type="submit"> Ajouter ce service</button></div>

</div>
</form></div>
</div></div>


<?php
}
}
}
function client_service_Transfer_areport(){
   global $panier_repas_load_css;
     global $panier_repas_load_js;
        // set this to true so the CSS is loaded
        $panier_repas_load_css = true;
        $panier_repas_load_js=true;
GLOBAL $wpdb;
$id=get_current_user_id();
$host_services_drafts = $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join    
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");




foreach($host_services_drafts as $host_servces_draft)



      {
        if($host_servces_draft->id==4){
?>

   <div class="container" >      
<form method="post">
<div class="col-md-6">
<div class="IconDiv">
  <div class="service_icon">
    <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" >
</div>
<div  >
<ul >
<li >
  Liste des tarifs
</li>
  <li >
    <div > <p >Pique-nique Parisien <span class="fs14 txtBlack R col-md-2 col-sm-2" style="text-align:right"> <?php echo '€   '.$host_servces_draft->prix ; ?></span></p></div>

  </li>
</ul>
  
</div>

</div>
</div>
<div class="col-md-6">
<div class="calendar_repas_panier_pargraphe" >
<div class="Titre"> <?php echo $host_servces_draft->Titre ;?> </div>
 <p>Nos chauffeurs viendront vous chercher à l'aéroport pour vous conduire directement à votre appartement Sweet Inn.</p> 
<h4><?php _e('choisir votre emplacement');?> </h4>
<div class="Emplacement"><input type="text" name="Emplacement"></div><br><div class="icon_flight_input">
<img class="Iconflight" src="<?php echo plugin_dir_url( __FILE__ ).'/resources/images/Flight_ON.png';?>"><img class="Iconflight" src="<?php echo plugin_dir_url( __FILE__ ).'/resources/images/Train_Off.png';?>"><div class="Emplacement"><input type="text" name="numero_flight_train"></div></div><br>
<?php _e('A l`arrivée');?> 
<br>
<input type="date" max="2020-06-25" min="2017-01-13" name="the_date">
 <div class="heure"><?php _e('Heure');?>
<input type="text" placeholder="HH:MM" data-inputmask-regex="^(?:0?[0-9]|1[0-9]|2[0-3]):[0-5][0-9]$" name="Heure">
</div><div class="Emplacement">
<?php _e('Nb de passagers');?> 
<input type="number" name="nbr_passager"class="Emplacement"></div>
</div><br><br>

<div class="btn"> <button  class="btn_ret" type="submit"> retour </button><button  class="btn_val" type="submit"> Ajouter ce service</button></div>

</div>

</form>
</div>
<?php
}
}
}

function client_service_menage(){
   global $panier_repas_load_css;
     global $panier_repas_load_js;
        // set this to true so the CSS is loaded
        $panier_repas_load_css = true;
        $panier_repas_load_js=true;
GLOBAL $wpdb;
$id=get_current_user_id();
$host_services_drafts = $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join    
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");




foreach($host_services_drafts as $host_servces_draft)



      {
        if($host_servces_draft->id==5){
?>
<div class="container">
<form method="post">
<div class="col-md-6">           
<div class="IconDiv">
  <div class="service_icon">
    <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" >
</div>
<div class="PriceList">
<ul >
<li>
  Liste des tarifs
</li>
  <li >
    <div > <p>Pique-nique Parisien <span  style="text-align:right"> <?php echo '€   '.$host_servces_draft->prix ; ?></span></p></div>

  </li>
</ul>
  </div>
</div>
</div>

<div class="col-md-6">
<div class="calendar_repas_panier_pargraphe" >

<div class="Titre"> <?php echo $host_servces_draft->Titre ;?> </div>
 <p>  Pour que vos vacances soient de véritables vacances. Nous entretiendrons votre appartement tout au long de votre séjour ou lors des jours sélectionnés.</p> 

<br><br>
<?php

include"Ical.php";

 global $immo_load_css;
 $events[0] = new Eventre(1, date('Y-m-d', strtotime('-37 day')), date('Y-m-d', strtotime('-4 day')), 'travel', 1);
      $events[1] = new Eventre(2, date('Y-m-d'), '', 'personal interview', 2);
      $events[2] = new Eventre(3, date('Y-m-d'), '', 'work appointment', 3);
      $events[3] = new Eventre(5, date('Y-m-d', strtotime('+3 day')), '', 'personal appointment', 1);
      $events[4] = new Eventre(6, date('Y-m-d', strtotime('-2 year')), date('Y-m-d', strtotime('+5 day')), 'studies', 3);
      $events[5] = new Eventre(4, date('Y-m-d', strtotime('-5 day')), date('Y-m-d', strtotime('+2 day')), 'Work on project', 2);
      $events[6] = new Eventre(7, date('Y-m-d'), '', 'personal', 1);
      $events[7] = new Eventre(9, date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+7 day')), 'Whatever...', 2);
      $events[8] = new Eventre(8, date('Y-m-d', strtotime('+3 day')), date('Y-m-d', strtotime('+57 day')), 'Just on more for the exemple', 1);

        // set this to true so the CSS is loaded
        $immo_load_css = true;
         usort($events, "sortByDurations");
 $intervalYear = 6;

      //récupère les dates actuelles
      // catch actualy dates
      $dayNow = strftime("%d");
      $monthNow = strftime("%m");
      $yearNow = strftime("%Y");

      //récupération du mois et de l'année envoyés en POST
      // take months and years send in POST
      $yearN = (isset($_POST['year']))?$_POST['year']:$yearNow;
      if (isset($_POST['month'])) {
        if ($_POST['month'] < 1) {
          $_POST['month'] = 12;
          $yearN -= 1;
          $_POST['year'] = $yearN;
        }
        if ($_POST['month'] > 12) {
          $_POST['month'] = 1;
          $yearN += 1;
          $_POST['year'] = $yearN;
        }
      }
      $monthN = (isset($_POST['month']))?$_POST['month']:$monthNow;

      //nombre de jours dans le mois et numero du premier jour du mois
      // take days in one month and first day of the month
      $nbrDayInMonth = date("t", mktime(0,0,0,$monthN,1,$yearN));
      $firstDay = date("w", mktime(0,0,0,$monthN,1,$yearN));
      //ajustement du jour (si =0 (dimanche), alors =7)
      // set first day (if =0 (sunday), then =7)
      $firstDay = ($firstDay == 0)?7:$firstDay;

      //nbr de jours du moi d'avant
      // days of the previous month
      $m = ($monthN - 1 < 1)?12:$monthN - 1;
      $preDays = date("t", mktime(0,0,0,$m,1,$yearN));

      //nbr de jours du mois d'apres
      // days of the month after
      $m = ($monthN + 1 > 12)?1:$monthN + 1;
      $aftMonth = date("t", mktime(0,0,0,$m,1,$yearN));

      //ne récupère que les évennements du mois parcourus (essenciel pour l'affichage), à remplacer par un where > date dans la requête SQL
      //set only the elements of the actual month (essential for the templating), can be replace with a where > date in the SQL request
      $unsetKeys = array();
      foreach ($events as $key => $e) {
        if (intval($e->date_begin->format('Y')) <= intval($yearN) && intval($e->date_end->format('Y')) >= intval($yearN)) {
        if (intval($e->date_begin->format('Y')) == intval($yearN)) {
          if (intval($e->date_begin->format('m')) > intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        if (intval($e->date_end->format('Y')) == intval($yearN)) {
          if (intval($e->date_end->format('m')) < intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        } else {
          $unsetKeys[] = $key;
        }
      }
      foreach ($unsetKeys as $unsetKey) {
        unset($events[$unsetKey]);
      }

      $t = 1;
      $style = "";
      for($i=1; $i<7; $i++) {
        for($j=1; $j<8; $j++) {
          //on met les jours du mois précédent
          // we set days of previous month
          if ($t == 1 && $j < $firstDay) {
            $style = "color:#aaa;";
            $day = $preDays-($firstDay-($j))+1;
            $tab_cal[$i][$j] = "<div style='{$style}'>{$day}</div>";
          }
          //on met le premier jour du mois à afficher
          // we set the first day of the month to print
          elseif ($j == $firstDay && $t == 1) {
            $style = "color:#000;";
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
          //on met le premier jour du mois d'après
          // we set the first day of the next month
          elseif ($t > $nbrDayInMonth) {
            $style = "color:#aaa;";
            $tab_cal[$i][$j] = "<div style='{$style}'>1</div>";
            $t = 2;
          }
          //on met les jours suivants du mois à afficher et du mois suivant
          // we set nexts days os the month to print and the next month
          elseif ($t > 1 && $t <= $nbrDayInMonth) {
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
        }
      }

?>
     <div class="containercal">
     
      <!-- Tableau du calendrier -->
      <div class="thumbnail">
        <table class="table table-bordered">
          <caption><h1><span class="glyphicon glyphicon-calendar"></span> Calendrier :</h1></caption>
          <!-- Année -->
          <tr>
            <th colspan="7">
              <h2>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN-1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN <= $yearNow - $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=$yearN?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN+1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN >= $yearNow + $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h2>
            </th>
          </tr>
          <tr>
            <th colspan="7">
              <h3>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN-1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN <= $yearNow - 3 && $monthN == 1)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=ucwords(strftime("%B", mktime(1, 1, 1, $monthN, 1, $yearN)))?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN+1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN >= $yearNow + 3 && $monthN == 12)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h3>
            </th>
          </tr>
          <tr>
            <?php for ($i = 1; $i < 8; $i++) { ?>
                <th>
                  <?=ucwords(strftime("%a", mktime(1, 1, 1, 5, $i, 2000)))?>
                </th>
            <?php } ?>
          </tr>
          <?php for($i=1; $i<7; $i++) { ?>
            <tr>
              <?php for($j=1; $j<8; $j++) {
                //récupérer le jour dans la chaine de charactère retournée
                // catch the day in the string return
                preg_match_all('!\d+!', $tab_cal[$i][$j], $day);
                $day = (isset($day[0][1]))?$day[0][1]:0; ?>
                <td  class="<?=($monthN == $monthNow && $yearN == $yearNow && $day == $dayNow)?'info':'';?> size">
                  <h4>
                    <?=$tab_cal[$i][$j]?>
                  </h4>
                  <div class="parent-day" parent-day="day">
                  <?php 
                  foreach ($events as $id => $e) {
                    if ($e->date_begin != $e->date_end) {
                      if ($day <> null) {
                        if ($e->date_begin <= new DateTime($yearN.'-'.$monthN.'-'.$day) && $e->date_end >= new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                          <div class="container-fluid btn-<?=$id?>-parent">
                            <div class="row parent-row">
                              <button type="button" event-in-month="<?=$id?>" class="btn btn-<?=$e->rank['color']?> btn-sm btn-block btn-hover btn-<?=$id?>" data-toggle="tooltip" data-placement="top" title="<?=$e->description?> : <?=$e->date_begin->format('Y-m-d')?> -> <?=$e->date_end->format('Y-m-d')?>">
                              </button>
                            </div>
                          </div>
                        <?php }
                      }
                    }
                  }?>
                  </div>
                  <?php foreach ($events as $e) {
                    if ($e->date_begin == $e->date_end) {
                      if ($e->date_begin == new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                        <button type="button" class="btn btn-<?=$e->rank['color']?> btn-sm" data-toggle="tooltip" data-placement="top" title="<?=$e->description?>">
                        </button>
                      <?php }
                    }
                  } ?>
                </td>
              <?php } ?>
            </tr>
          <?php } ?>
        </table>
      </div> <!-- table -->
    </div><!-- container -->
</div><br><br>

<div class="btn"> <button  class="btn_ret" type="submit"> retour </button><button  class="btn_val" type="submit"> Ajouter ce service</button></div>

</div>
</form>
</div>

<?php
}
}
}
function client_service_smartphone(){
   global $panier_repas_load_css;
     global $panier_repas_load_js;
        // set this to true so the CSS is loaded
        $panier_repas_load_css = true;
        $panier_repas_load_js=true;
GLOBAL $wpdb;
$id=get_current_user_id();
     
$host_services_drafts = $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join    
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");



foreach($host_services_drafts as $host_servces_draft)



      {
        if($host_servces_draft->id==6){
?>

           
<div class="container">
  <div class="col-md-6">
    <form method="post">

<div class="IconDiv">
  <div class="service_icon">
    <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" >
</div>
<div class="">
Liste des tarifs
<ul class="" id="">

  <li class="">
    <div class=""> <p class="">
                            La journée:  <span class="" style="text-align:right"> <?php echo '€   '.$host_servces_draft->prix ; ?></span></p></div>

  </li>
<li>  Nb d'appareils 
  <div class="select_nbr">
  <select nam Nb d'appareils e="nbr_smartphone" ><option value="1">1 </option>
<option value="2">2</option>
<option value="3">3</option>
<option value="4">4</option>

  </select></div></li>
</ul>
  
</div>

</div>



  </div>
  <div class="col-md-6">

<div class="calendar_repas_panier_pargraphe" > 
<div class="Titre"> <?php echo $host_servces_draft->Titre ;?> </div>
<p>  Pour que vos vacances soient de véritables vacances. Nous entretiendrons votre appartement tout au long de votre séjour ou lors des jours sélectionnés.</p> 

<br><br>

</div>
<?php
include"Ical.php";

 global $immo_load_css;
 $events[0] = new Eventre(1, date('Y-m-d', strtotime('-37 day')), date('Y-m-d', strtotime('-4 day')), 'travel', 1);
      $events[1] = new Eventre(2, date('Y-m-d'), '', 'personal interview', 2);
      $events[2] = new Eventre(3, date('Y-m-d'), '', 'work appointment', 3);
      $events[3] = new Eventre(5, date('Y-m-d', strtotime('+3 day')), '', 'personal appointment', 1);
      $events[4] = new Eventre(6, date('Y-m-d', strtotime('-2 year')), date('Y-m-d', strtotime('+5 day')), 'studies', 3);
      $events[5] = new Eventre(4, date('Y-m-d', strtotime('-5 day')), date('Y-m-d', strtotime('+2 day')), 'Work on project', 2);
      $events[6] = new Eventre(7, date('Y-m-d'), '', 'personal', 1);
      $events[7] = new Eventre(9, date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+7 day')), 'Whatever...', 2);
      $events[8] = new Eventre(8, date('Y-m-d', strtotime('+3 day')), date('Y-m-d', strtotime('+57 day')), 'Just on more for the exemple', 1);

        // set this to true so the CSS is loaded
        $immo_load_css = true;
         usort($events, "sortByDurations");
 $intervalYear = 6;

      //récupère les dates actuelles
      // catch actualy dates
      $dayNow = strftime("%d");
      $monthNow = strftime("%m");
      $yearNow = strftime("%Y");

      //récupération du mois et de l'année envoyés en POST
      // take months and years send in POST
      $yearN = (isset($_POST['year']))?$_POST['year']:$yearNow;
      if (isset($_POST['month'])) {
        if ($_POST['month'] < 1) {
          $_POST['month'] = 12;
          $yearN -= 1;
          $_POST['year'] = $yearN;
        }
        if ($_POST['month'] > 12) {
          $_POST['month'] = 1;
          $yearN += 1;
          $_POST['year'] = $yearN;
        }
      }
      $monthN = (isset($_POST['month']))?$_POST['month']:$monthNow;

      //nombre de jours dans le mois et numero du premier jour du mois
      // take days in one month and first day of the month
      $nbrDayInMonth = date("t", mktime(0,0,0,$monthN,1,$yearN));
      $firstDay = date("w", mktime(0,0,0,$monthN,1,$yearN));
      //ajustement du jour (si =0 (dimanche), alors =7)
      // set first day (if =0 (sunday), then =7)
      $firstDay = ($firstDay == 0)?7:$firstDay;

      //nbr de jours du moi d'avant
      // days of the previous month
      $m = ($monthN - 1 < 1)?12:$monthN - 1;
      $preDays = date("t", mktime(0,0,0,$m,1,$yearN));

      //nbr de jours du mois d'apres
      // days of the month after
      $m = ($monthN + 1 > 12)?1:$monthN + 1;
      $aftMonth = date("t", mktime(0,0,0,$m,1,$yearN));

      //ne récupère que les évennements du mois parcourus (essenciel pour l'affichage), à remplacer par un where > date dans la requête SQL
      //set only the elements of the actual month (essential for the templating), can be replace with a where > date in the SQL request
      $unsetKeys = array();
      foreach ($events as $key => $e) {
        if (intval($e->date_begin->format('Y')) <= intval($yearN) && intval($e->date_end->format('Y')) >= intval($yearN)) {
        if (intval($e->date_begin->format('Y')) == intval($yearN)) {
          if (intval($e->date_begin->format('m')) > intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        if (intval($e->date_end->format('Y')) == intval($yearN)) {
          if (intval($e->date_end->format('m')) < intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        } else {
          $unsetKeys[] = $key;
        }
      }
      foreach ($unsetKeys as $unsetKey) {
        unset($events[$unsetKey]);
      }

      $t = 1;
      $style = "";
      for($i=1; $i<7; $i++) {
        for($j=1; $j<8; $j++) {
          //on met les jours du mois précédent
          // we set days of previous month
          if ($t == 1 && $j < $firstDay) {
            $style = "color:#aaa;";
            $day = $preDays-($firstDay-($j))+1;
            $tab_cal[$i][$j] = "<div style='{$style}'>{$day}</div>";
          }
          //on met le premier jour du mois à afficher
          // we set the first day of the month to print
          elseif ($j == $firstDay && $t == 1) {
            $style = "color:#000;";
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
          //on met le premier jour du mois d'après
          // we set the first day of the next month
          elseif ($t > $nbrDayInMonth) {
            $style = "color:#aaa;";
            $tab_cal[$i][$j] = "<div style='{$style}'>1</div>";
            $t = 2;
          }
          //on met les jours suivants du mois à afficher et du mois suivant
          // we set nexts days os the month to print and the next month
          elseif ($t > 1 && $t <= $nbrDayInMonth) {
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
        }
      }

?>
     <div class="containercal">
     
      <!-- Tableau du calendrier -->
      <div class="thumbnail">
        <table class="table table-bordered">
          <caption><h1><span class="glyphicon glyphicon-calendar"></span> Calendrier :</h1></caption>
          <!-- Année -->
          <tr>
            <th colspan="7">
              <h2>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN-1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN <= $yearNow - $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=$yearN?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN+1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN >= $yearNow + $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h2>
            </th>
          </tr>
          <tr>
            <th colspan="7">
              <h3>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN-1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN <= $yearNow - 3 && $monthN == 1)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=ucwords(strftime("%B", mktime(1, 1, 1, $monthN, 1, $yearN)))?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN+1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN >= $yearNow + 3 && $monthN == 12)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h3>
            </th>
          </tr>
          <tr>
            <?php for ($i = 1; $i < 8; $i++) { ?>
                <th>
                  <?=ucwords(strftime("%a", mktime(1, 1, 1, 5, $i, 2000)))?>
                </th>
            <?php } ?>
          </tr>
          <?php for($i=1; $i<7; $i++) { ?>
            <tr>
              <?php for($j=1; $j<8; $j++) {
                //récupérer le jour dans la chaine de charactère retournée
                // catch the day in the string return
                preg_match_all('!\d+!', $tab_cal[$i][$j], $day);
                $day = (isset($day[0][1]))?$day[0][1]:0; ?>
                <td  class="<?=($monthN == $monthNow && $yearN == $yearNow && $day == $dayNow)?'info':'';?> size">
                  <h4>
                    <?=$tab_cal[$i][$j]?>
                  </h4>
                  <div class="parent-day" parent-day="day">
                  <?php 
                  foreach ($events as $id => $e) {
                    if ($e->date_begin != $e->date_end) {
                      if ($day <> null) {
                        if ($e->date_begin <= new DateTime($yearN.'-'.$monthN.'-'.$day) && $e->date_end >= new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                          <div class="container-fluid btn-<?=$id?>-parent">
                            <div class="row parent-row">
                              <button type="button" event-in-month="<?=$id?>" class="btn btn-<?=$e->rank['color']?> btn-sm btn-block btn-hover btn-<?=$id?>" data-toggle="tooltip" data-placement="top" title="<?=$e->description?> : <?=$e->date_begin->format('Y-m-d')?> -> <?=$e->date_end->format('Y-m-d')?>">
                              </button>
                            </div>
                          </div>
                        <?php }
                      }
                    }
                  }?>
                  </div>
                  <?php foreach ($events as $e) {
                    if ($e->date_begin == $e->date_end) {
                      if ($e->date_begin == new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                        <button type="button" class="btn btn-<?=$e->rank['color']?> btn-sm" data-toggle="tooltip" data-placement="top" title="<?=$e->description?>">
                        </button>
                      <?php }
                    }
                  } ?>
                </td>
              <?php } ?>
            </tr>
          <?php } ?>
        </table>
      </div> <!-- table -->
    </div><!-- container -->
<br><br>

<div class="btn"> <button  class="btn_ret" type="submit"> retour </button><button  class="btn_val" type="submit"> Ajouter ce service</button></div>



</form>
  </div>

</div>
<?php
}
}
}
function client_service_nettoyage(){
   global $panier_repas_load_css;
     global $panier_repas_load_js;
        // set this to true so the CSS is loaded
        $panier_repas_load_css = true;
        $panier_repas_load_js=true;
GLOBAL $wpdb;
$id=get_current_user_id();
     
$host_services_drafts = $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join    
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");




foreach($host_services_drafts as $host_servces_draft)



      {
        if($host_servces_draft->id==9){
?>

           
<div class="container"> 
  <form method="post">
  <div class="">
   

<div class="IconDiv">
  <div class="service_icon">
    <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" >
</div>>
<div class="PriceList">
Liste des tarifs
<ul >

  <li >
    <div > <p >
                            prix  :  <span  > <?php echo '€   '.$host_servces_draft->prix ; ?></span></p></div>

  </li>

</ul>
  
</div>

</div>
</div>
<div class="col-md-6">
<div class="calendar_repas_panier_pargraphe" > 
<div class="Titre"> <?php echo $host_servces_draft->Titre ;?> </div>

<p>  Pour que vos vacances soient de véritables vacances. Nous entretiendrons votre appartement tout au long de votre séjour ou lors des jours sélectionnés.</p> 

<br><br>
<?php
//get_calendar( 'false', 'true','true' ); 
include"Ical.php";

 global $immo_load_css;
 $events[0] = new Eventre(1, date('Y-m-d', strtotime('-37 day')), date('Y-m-d', strtotime('-4 day')), 'travel', 1);
      $events[1] = new Eventre(2, date('Y-m-d'), '', 'personal interview', 2);
      $events[2] = new Eventre(3, date('Y-m-d'), '', 'work appointment', 3);
      $events[3] = new Eventre(5, date('Y-m-d', strtotime('+3 day')), '', 'personal appointment', 1);
      $events[4] = new Eventre(6, date('Y-m-d', strtotime('-2 year')), date('Y-m-d', strtotime('+5 day')), 'studies', 3);
      $events[5] = new Eventre(4, date('Y-m-d', strtotime('-5 day')), date('Y-m-d', strtotime('+2 day')), 'Work on project', 2);
      $events[6] = new Eventre(7, date('Y-m-d'), '', 'personal', 1);
      $events[7] = new Eventre(9, date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+7 day')), 'Whatever...', 2);
      $events[8] = new Eventre(8, date('Y-m-d', strtotime('+3 day')), date('Y-m-d', strtotime('+57 day')), 'Just on more for the exemple', 1);

        // set this to true so the CSS is loaded
        $immo_load_css = true;
         usort($events, "sortByDurations");
 $intervalYear = 6;

      //récupère les dates actuelles
      // catch actualy dates
      $dayNow = strftime("%d");
      $monthNow = strftime("%m");
      $yearNow = strftime("%Y");

      //récupération du mois et de l'année envoyés en POST
      // take months and years send in POST
      $yearN = (isset($_POST['year']))?$_POST['year']:$yearNow;
      if (isset($_POST['month'])) {
        if ($_POST['month'] < 1) {
          $_POST['month'] = 12;
          $yearN -= 1;
          $_POST['year'] = $yearN;
        }
        if ($_POST['month'] > 12) {
          $_POST['month'] = 1;
          $yearN += 1;
          $_POST['year'] = $yearN;
        }
      }
      $monthN = (isset($_POST['month']))?$_POST['month']:$monthNow;

      //nombre de jours dans le mois et numero du premier jour du mois
      // take days in one month and first day of the month
      $nbrDayInMonth = date("t", mktime(0,0,0,$monthN,1,$yearN));
      $firstDay = date("w", mktime(0,0,0,$monthN,1,$yearN));
      //ajustement du jour (si =0 (dimanche), alors =7)
      // set first day (if =0 (sunday), then =7)
      $firstDay = ($firstDay == 0)?7:$firstDay;

      //nbr de jours du moi d'avant
      // days of the previous month
      $m = ($monthN - 1 < 1)?12:$monthN - 1;
      $preDays = date("t", mktime(0,0,0,$m,1,$yearN));

      //nbr de jours du mois d'apres
      // days of the month after
      $m = ($monthN + 1 > 12)?1:$monthN + 1;
      $aftMonth = date("t", mktime(0,0,0,$m,1,$yearN));

      //ne récupère que les évennements du mois parcourus (essenciel pour l'affichage), à remplacer par un where > date dans la requête SQL
      //set only the elements of the actual month (essential for the templating), can be replace with a where > date in the SQL request
      $unsetKeys = array();
      foreach ($events as $key => $e) {
        if (intval($e->date_begin->format('Y')) <= intval($yearN) && intval($e->date_end->format('Y')) >= intval($yearN)) {
        if (intval($e->date_begin->format('Y')) == intval($yearN)) {
          if (intval($e->date_begin->format('m')) > intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        if (intval($e->date_end->format('Y')) == intval($yearN)) {
          if (intval($e->date_end->format('m')) < intval($monthN)) {
            $unsetKeys[] = $key;
          }
        }
        } else {
          $unsetKeys[] = $key;
        }
      }
      foreach ($unsetKeys as $unsetKey) {
        unset($events[$unsetKey]);
      }

      $t = 1;
      $style = "";
      for($i=1; $i<7; $i++) {
        for($j=1; $j<8; $j++) {
          //on met les jours du mois précédent
          // we set days of previous month
          if ($t == 1 && $j < $firstDay) {
            $style = "color:#aaa;";
            $day = $preDays-($firstDay-($j))+1;
            $tab_cal[$i][$j] = "<div style='{$style}'>{$day}</div>";
          }
          //on met le premier jour du mois à afficher
          // we set the first day of the month to print
          elseif ($j == $firstDay && $t == 1) {
            $style = "color:#000;";
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
          //on met le premier jour du mois d'après
          // we set the first day of the next month
          elseif ($t > $nbrDayInMonth) {
            $style = "color:#aaa;";
            $tab_cal[$i][$j] = "<div style='{$style}'>1</div>";
            $t = 2;
          }
          //on met les jours suivants du mois à afficher et du mois suivant
          // we set nexts days os the month to print and the next month
          elseif ($t > 1 && $t <= $nbrDayInMonth) {
            $tab_cal[$i][$j] = "<div style='{$style}'>{$t}</div>";
            $t++;
          }
        }
      }

?>
     <div class="containercal">
     
      <!-- Tableau du calendrier -->
      <div class="thumbnail">
        <table class="table table-bordered">
          <caption><h1><span class="glyphicon glyphicon-calendar"></span> Calendrier :</h1></caption>
          <!-- Année -->
          <tr>
            <th colspan="7">
              <h2>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN-1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN <= $yearNow - $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=$yearN?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$monthN?>">
                  <button name="year" value="<?=$yearN+1?>" type="submit" class="btn btn-primary btn-sm" <?=($yearN >= $yearNow + $intervalYear)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h2>
            </th>
          </tr>
          <tr>
            <th colspan="7">
              <h3>
                <form action="" method="POST" class="visible-lg-inline pull-left">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN-1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN <= $yearNow - 3 && $monthN == 1)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-backward"></span>
                  </button>
                </form>
                <?=ucwords(strftime("%B", mktime(1, 1, 1, $monthN, 1, $yearN)))?>
                <form action="" method="POST" class="visible-lg-inline pull-right">
                  <input name="month" type="hidden" value="<?=$yearN?>">
                  <button name="month" value="<?=$monthN+1?>" type="submit" class="btn btn-primary btn-xs" <?=($yearN >= $yearNow + 3 && $monthN == 12)?'disabled':'';?>>
                    <span class="glyphicon glyphicon-forward"></span>
                  </button>
                </form>
              </h3>
            </th>
          </tr>
          <tr>
            <?php for ($i = 1; $i < 8; $i++) { ?>
                <th>
                  <?=ucwords(strftime("%a", mktime(1, 1, 1, 5, $i, 2000)))?>
                </th>
            <?php } ?>
          </tr>
          <?php for($i=1; $i<7; $i++) { ?>
            <tr>
              <?php for($j=1; $j<8; $j++) {
                //récupérer le jour dans la chaine de charactère retournée
                // catch the day in the string return
                preg_match_all('!\d+!', $tab_cal[$i][$j], $day);
                $day = (isset($day[0][1]))?$day[0][1]:0; ?>
                <td  class="<?=($monthN == $monthNow && $yearN == $yearNow && $day == $dayNow)?'info':'';?> size">
                  <h4>
                    <?=$tab_cal[$i][$j]?>
                  </h4>
                  <div class="parent-day" parent-day="day">
                  <?php 
                  foreach ($events as $id => $e) {
                    if ($e->date_begin != $e->date_end) {
                      if ($day <> null) {
                        if ($e->date_begin <= new DateTime($yearN.'-'.$monthN.'-'.$day) && $e->date_end >= new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                          <div class="container-fluid btn-<?=$id?>-parent">
                            <div class="row parent-row">
                              <button type="button" event-in-month="<?=$id?>" class="btn btn-<?=$e->rank['color']?> btn-sm btn-block btn-hover btn-<?=$id?>" data-toggle="tooltip" data-placement="top" title="<?=$e->description?> : <?=$e->date_begin->format('Y-m-d')?> -> <?=$e->date_end->format('Y-m-d')?>">
                              </button>
                            </div>
                          </div>
                        <?php }
                      }
                    }
                  }?>
                  </div>
                  <?php foreach ($events as $e) {
                    if ($e->date_begin == $e->date_end) {
                      if ($e->date_begin == new DateTime($yearN.'-'.$monthN.'-'.$day)) { ?>
                        <button type="button" class="btn btn-<?=$e->rank['color']?> btn-sm" data-toggle="tooltip" data-placement="top" title="<?=$e->description?>">
                        </button>
                      <?php }
                    }
                  } ?>
                </td>
              <?php } ?>
            </tr>
          <?php } ?>
        </table>
      </div> <!-- table -->
    </div><!-- container -->

<?php


?>
</div><br><br>

<div class="btn"> <button  class="btn_ret" type="submit"> retour </button><button  class="btn_val" type="submit"> Ajouter ce service</button></div>

</div>

</form>
</div>

<?php
}
}
}
function client_services(){
   global $panier_repas_load_css;
     global $panier_repas_load_js;
        // set this to true so the CSS is loaded
        $panier_repas_load_css = true;
        $panier_repas_load_js=true;
GLOBAL $wpdb;
$id=get_current_user_id();
     
$host_services_drafts = $wpdb->get_results("select f.Titre,f.info,f.img_services,b.prix ,f.id from wpio_services_global f inner join    
  wpio_service_booker b on(f.id=b.id_services and b.id_booker=$id )  ");



foreach($host_services_drafts as $host_servces_draft)



      {?>



<div class="HangarWrapper">


    <?php
        if($host_servces_draft->id==9){
?>
        <div><div>
          Sélectionnez des services supplémentaires <br>
        </div>
          
           <div> 
              C'est le moment de personnaliser votre séjour.<br>
1. Sélectionnez le service désiré<br>
2. Sélectionnez une date de livraison<br>
3. Revenez à la liste des services pour en sélectionner un autre
             </div>
 <img src="<?php echo plugin_dir_url( __FILE__ ).$host_servces_draft->img_services; ?>" alt="" >
        </div>
   

<?php
}
}
?>
</div>
<?php
}

function services_panier_repas_css() {
    wp_register_style('panier_repas-form-css', plugin_dir_url( __FILE__ ) . '/resources/css/Panier_repas.css');
}

add_action('init', 'services_panier_repas_css');
 function services_panier_repas_js(){
 wp_enqueue_script('jquery');
     wp_register_script('services_js',plugin_dir_url( __FILE__ ) .'services.js',array( 'jquery'),'',true);
 }

add_action('init', 'services_panier_repas_js');


      function custom_enqueue_script() {
         

           
             wp_enqueue_script('services_js');
      }
 add_action( 'wp_footer', 'custom_enqueue_script' );



function gestion_services_panier_repas_css() {
    global $panier_repas_load_css;
 
    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $panier_repas_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('panier_repas-form-css');
}
add_action('wp_footer', 'gestion_services_panier_repas_css');

add_shortcode('client_panier_repas','client_service_Panier_repas');
//client_service_Transfer_areport client_service_smartphone
add_shortcode('client_Transfer_areport','client_service_Transfer_areport');
add_shortcode('client_manage','client_service_menage');
add_shortcode('client_smartphone','client_service_smartphone');
//client_service_nettoyageclient_services
add_shortcode('client_servicess','client_services');
add_shortcode('client_nettoyage','client_service_nettoyage');
?>