
    <?php
      //initialise la zone horaire
      // init the local time zone
      setlocale(LC_TIME, 'fr_FR.utf8', 'fr_FR');
      date_default_timezone_set('Europe/Paris');

      /**
       * Events object
       */
      class Event
      {
        public $id;
        public $date_begin;
        public $date_end;
        public $description;
        public $rank;
        public $duration;
        /**
         * date format : "yyyy-mm-dd"
         */
        public function __construct($unId, $dateBegin, $dateEnd, $descriptionText, $rankNumber)
        {
          $this->id = $unId;
          $this->date_begin = new DateTime($dateBegin);
          $this->date_end = (!empty($dateEnd))?new DateTime($dateEnd):new DateTime($dateBegin);
          $this->description = (is_string($descriptionText))?$descriptionText:'';
          switch ($rankNumber) {
            case 1:
              $this->rank = array('important'=>'low', 'color'=>'success');
              break;

            case 2:
              $this->rank = array('important'=>'medium', 'color'=>'warning');
              break;

            case 3:
            default:
              $this->rank = array('important'=>'hight', 'color'=>'danger');
              break;
          }
          $this->duration = $this->date_begin->diff($this->date_end)->days;
        }
      }

      //initialise quelques évenements
      // create somes events
      $events[0] = new Event(1, date('Y-m-d', strtotime('-37 day')), date('Y-m-d', strtotime('-4 day')), 'travel', 1);
      $events[1] = new Event(2, date('Y-m-d'), '', 'personal interview', 2);
      $events[2] = new Event(3, date('Y-m-d'), '', 'work appointment', 3);
      $events[3] = new Event(5, date('Y-m-d', strtotime('+3 day')), '', 'personal appointment', 1);
      $events[4] = new Event(6, date('Y-m-d', strtotime('-2 year')), date('Y-m-d', strtotime('+5 day')), 'studies', 3);
      $events[5] = new Event(4, date('Y-m-d', strtotime('-5 day')), date('Y-m-d', strtotime('+2 day')), 'Work on project', 2);
      $events[6] = new Event(7, date('Y-m-d'), '', 'personal', 1);
      $events[7] = new Event(9, date('Y-m-d', strtotime('+1 day')), date('Y-m-d', strtotime('+7 day')), 'Whatever...', 2);
      $events[8] = new Event(8, date('Y-m-d', strtotime('+3 day')), date('Y-m-d', strtotime('+57 day')), 'Just on more for the exemple', 1);
      $events[9] = new Event(10, date('Y-m-d'), date('Y-m-d', strtotime('+6 day')), 'Just on more for the exemple', 3);
      $events[10] = new Event(11, date('Y-m-d', strtotime('+4 day')), date('Y-m-d', strtotime('+7 day')), 'Just on more for the exemple', 1);
      $events[11] = new Event(12, date('Y-m-d', strtotime('+1 month')), date('Y-m-d', strtotime('+7 year')), 'no month', 1);
      $events[12] = new Event(13, date('Y-m-d', strtotime('-9 year')), date('Y-m-d', strtotime('-1 year')), 'no year', 1);

      //intervale d'années visibles pour l'utilisateurs {maintenant - intervale ; [...à...] ; maintenant + intervale}
      // interval of years that user can see {now - interval ; [...to...] ; now + interval}
      $intervalYear = 3;

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

      //Tri des evenements par durée, remplecer par un order by duration en SQL
      // Order of the events by duration, can be replace by a SQL order by duration
      function sortByDuration($a, $b) {
        $aDuration = $a->date_begin->diff($a->date_end);
        $bDuration = $b->date_begin->diff($b->date_end);
        if ( intval($aDuration->format('%R%a')) == intval($bDuration->format('%R%a')) ) { return 0; }
        return ( intval($aDuration->format('%R%a')) > intval($bDuration->format('%R%a')) )?-1:1;
      }
      usort($events, "sortByDuration");

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

    <div class="container">
      <form method="POST" class="form-inline" action="" id="search_year">
        <div class="form-group">
          <label for="year">Année :</label>
          <select class="form-control" name="year" id="year">
            <?php for ($i=0; $i < (($intervalYear) * 2 + 1); $i++) {
              $year = $yearNow - $intervalYear + $i; ?>
              <option value='<?=$year?>' <?=($year == $yearN)?'selected':'';?>><?=$year?></option>
            <?php } ?>
          </select>
        </div>
        <div class="form-group">
          <label for="month">Mois :</label>
          <select class="form-control" name="month" id="month">
            <?php for ($i = 1; $i < 13; $i++) { ?>
              <option value='<?=$i?>' <?=($i == $monthN)?'selected':'';?>><?=ucwords(strftime("%B", mktime(1, 1, 1, $i, 1, $yearN)))?></option>
            <?php } ?>
          </select>
        </div>
        <button type="submit" class="btn btn-default">Voir</button>
      </form>
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
                  <?php foreach ($events as $id => $e) {
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

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script type="text/javascript">
      $(function () {
        $('[data-toggle="tooltip"]').tooltip()
      })
      Array.prototype.inArray = function(element) { 
        for(var i=0, limit = this.length; i < limit; i++) { 
          if(this[i] == element) { return true; }
        }
        return false;
      };
      Array.prototype.pushIfNotExist = function(element) {
        if (!this.inArray(element)) {
          this.push(element);
        }
      };
      var days = document.getElementsByClassName('parent-day');
      var btnsMargin = [];
      for (var i = 0, limit = days.length; i < limit; i++) {
        btnsMargin = days[i].getElementsByClassName('btn-hover');
        for (var j = 0, max = btnsMargin.length; j < max; j++) {
          var envent = parseInt(btnsMargin[j].getAttribute('event-in-month'));
          btnsMargin[j].style.marginTop = (14 * envent) + 'px';
        }
        days[i].style.height = (8 + (14 * envent)) + 'px';
      }
      var btns = document.getElementsByClassName('btn-hover');
      var allDaysEvents = [];
      for (var i = 0, limit = btns.length; i < limit; i++) {
        var classes = btns[i].className.split(/\s/);
        for (var j = 0, max = classes.length; j < max; j++) {
          if (classes[j].search(/^btn\-[\d]+$/) > -1) {
            allDaysEvents[i] = document.getElementsByClassName(classes[j]);
          }
        }
        btns[i].addEventListener('mouseover', function(i){
          return function(){
            for (var j = 0, max = allDaysEvents[i].length; j < max; j++) {
              allDaysEvents[i][j].className = allDaysEvents[i][j].className.replace(/\bbtn-(success|warning|danger|info)\b/, 'btn-primary $1');
            }
          }
        }(i), false);
        btns[i].addEventListener('mouseout', function(i){
          return function(){
            for (var j = 0, max = allDaysEvents[i].length; j < max; j++) {
              allDaysEvents[i][j].className = allDaysEvents[i][j].className.replace(/\bbtn-primary (success|warning|danger|info)\b/, 'btn-$1');
            }
          }
        }(i), false);
      }
    </script>
    <?php

    function Ical_immo_css() {
        wp_register_style('panier_repas-form-css', plugin_dir_url( __FILE__ ) . 'Immo_icalFrontend.css');
        wp_print_styles('panier_repas-form-css');
    }

    add_action('wp_footer', 'Ical_immo_css');

    ?>