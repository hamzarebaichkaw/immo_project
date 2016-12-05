<?php
/**
 * Created by PhpStorm.
 * User: chkaw
 * Date: 30/11/2016
 * Time: 16:27
 */
class Eventre
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

function sortByDurations($a, $b) {
        $aDuration = $a->date_begin->diff($a->date_end);
        $bDuration = $b->date_begin->diff($b->date_end);
        if ( intval($aDuration->format('%R%a')) == intval($bDuration->format('%R%a')) ) { return 0; }
        return ( intval($aDuration->format('%R%a')) > intval($bDuration->format('%R%a')) )?-1:1;
      }
     

function immos_cal(){
}
//add_shortcode('ical_immo','immo_cal');
/*function immo_css() {
    wp_register_style('panier_repas-form-css', plugin_dir_url( __FILE__ ) . 'Immo_icalFrontend.css');
}

add_action('init', 'immo_css');
function gestion_immo_css() {
    global $immo_load_css;
 
    // this variable is set to TRUE if the short code is used on a page/post
    if ( ! $immo_load_css )
        return; // this means that neither short code is present, so we get out of here
 
    wp_print_styles('gestion_immo_css');
}