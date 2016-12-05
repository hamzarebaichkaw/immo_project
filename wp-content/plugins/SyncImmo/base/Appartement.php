<?php

/**
 * Created by PhpStorm.
 * User: chkaw
 * Date: 30/11/2016
 * Time: 15:49
 */
class Appartement
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
